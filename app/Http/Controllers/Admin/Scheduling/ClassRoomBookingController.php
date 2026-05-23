<?php

namespace App\Http\Controllers\Admin\Scheduling;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\BookingRequest;
use App\Models\Class_Room_Bookings;
use App\Models\Room;
use Illuminate\Http\Request;

class ClassRoomBookingController extends Controller
{
    private $adminVerifier;

    public function __construct(AdminVerificationController $adminVerifier)
    {
        $this->middleware('auth:sanctum');
        $this->adminVerifier = $adminVerifier;
    }

    public function index()
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return Class_Room_Bookings::where('Is_Deleted', false)
            ->with(['room.flow.branch', 'bookRequest.course', 'bookRequest.batch', 'bookRequest.user'])
            ->get();
    }

    public function availableRooms(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'BookRequest_ID' => 'required|integer|exists:BookingRequest,BookRequest_ID',
        ]);

        $bookingRequest = BookingRequest::where('Is_Deleted', false)->find($validated['BookRequest_ID']);

        if (!$bookingRequest) {
            return response()->json(['message' => 'Booking request not found'], 404);
        }

        return Room::where('Is_Deleted', false)
            ->where('Room_Availability', '1')
            ->where('Max_Student_Count', '>=', $bookingRequest->Expected_Student_Count)
            ->whereIn('Room_Type', $this->allowedRoomTypes($bookingRequest->Class_Type))
            ->whereDoesntHave('classRoomBookings', function ($query) use ($bookingRequest) {
                $query->where('Is_Deleted', false)
                    ->whereHas('bookRequest', function ($bookingQuery) use ($bookingRequest) {
                        $bookingQuery->where('Is_Deleted', false)
                            ->where('Status', '!=', 'Rejected')
                            ->where('Class_Start_Time', '<', $bookingRequest->Class_End_Time)
                            ->where('Class_End_Time', '>', $bookingRequest->Class_Start_Time);
                    });
            })
            ->with('flow.branch')
            ->get();
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'Room_ID' => 'required|integer|exists:rooms,Room_ID',
            'BookRequest_ID' => 'required|integer|exists:BookingRequest,BookRequest_ID',
            'CRB_Discription' => 'nullable|string',
        ]);

        $bookingRequest = BookingRequest::where('Is_Deleted', false)->find($validated['BookRequest_ID']);
        $room = Room::where('Is_Deleted', false)->find($validated['Room_ID']);

        if (!$bookingRequest || !$room) {
            return response()->json(['message' => 'Booking request or room not found'], 404);
        }

        if ($bookingRequest->Status === 'Rejected') {
            return response()->json(['message' => 'Rejected booking requests cannot be assigned a room'], 422);
        }

        if ($room->Room_Availability !== '1') {
            return response()->json(['message' => 'Room is not available for scheduling'], 422);
        }

        if ($room->Max_Student_Count < $bookingRequest->Expected_Student_Count) {
            return response()->json(['message' => 'Room capacity is lower than the expected student count'], 422);
        }

        if (!in_array($room->Room_Type, $this->allowedRoomTypes($bookingRequest->Class_Type), true)) {
            return response()->json(['message' => 'Room type does not match the class type'], 422);
        }

        $roomConflict = Class_Room_Bookings::where('Is_Deleted', false)
            ->where('Room_ID', $room->Room_ID)
            ->whereHas('bookRequest', function ($query) use ($bookingRequest) {
                $query->where('Is_Deleted', false)
                    ->where('Status', '!=', 'Rejected')
                    ->where('Class_Start_Time', '<', $bookingRequest->Class_End_Time)
                    ->where('Class_End_Time', '>', $bookingRequest->Class_Start_Time);
            })
            ->first();

        if ($roomConflict) {
            return response()->json([
                'message' => 'Room is already booked during the requested time',
                'conflict' => $roomConflict->load('bookRequest'),
            ], 422);
        }

        $existingAssignment = Class_Room_Bookings::where('Is_Deleted', false)
            ->where('BookRequest_ID', $bookingRequest->BookRequest_ID)
            ->first();

        if ($existingAssignment) {
            return response()->json([
                'message' => 'This booking request already has a room assignment',
                'assignment' => $existingAssignment->load('room'),
            ], 422);
        }

        $assignment = Class_Room_Bookings::create([
            'Room_ID' => $room->Room_ID,
            'BookRequest_ID' => $bookingRequest->BookRequest_ID,
            'CRB_Discription' => $validated['CRB_Discription'] ?? '',
            'Is_Deleted' => false,
        ]);

        $bookingRequest->update(['Status' => 'Confirmed']);

        return $assignment->load(['room.flow.branch', 'bookRequest.course', 'bookRequest.batch', 'bookRequest.user']);
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!is_numeric($param)) {
            return response()->json(['message' => 'Invalid parameter format'], 400);
        }

        $assignment = Class_Room_Bookings::find($param);

        if (!$assignment || $assignment->Is_Deleted) {
            return response()->json(['message' => 'Room assignment not found'], 404);
        }

        $assignment->update(['Is_Deleted' => true]);
        $assignment->bookRequest()->update(['Status' => 'Pending']);

        return response()->json(['message' => 'Room assignment removed successfully']);
    }

    private function allowedRoomTypes(string $classType): array
    {
        if ($classType === 'Practical') {
            return ['Laboratory'];
        }

        return ['Class'];
    }
}
