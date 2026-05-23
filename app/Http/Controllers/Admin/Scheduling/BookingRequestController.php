<?php

namespace App\Http\Controllers\Admin\Scheduling;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingRequestController extends Controller
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

        return BookingRequest::where('Is_Deleted', false)
            ->with(['course', 'batch', 'user', 'equipmentRequestList.equipment', 'roomBookings.room'])
            ->orderBy('Class_Start_Time')
            ->get();
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'Course_ID' => 'required|integer|exists:courses,Course_ID',
            'Batch_ID' => 'required|integer|exists:Batches,Batch_ID',
            'User_ID' => 'required|integer|exists:users,User_ID',
            'ERL_ID' => 'nullable|integer|exists:EquipmentRequestList,ERL_ID',
            'Class_Type' => ['required', Rule::in(['Practical', 'Theory', 'Lesson'])],
            'Expected_Student_Count' => 'required|integer|min:1',
            'Class_Start_Time' => 'required|date',
            'Class_End_Time' => 'required|date|after:Class_Start_Time',
            'Status' => ['sometimes', Rule::in(['Confirmed', 'Pending', 'Rejected'])],
        ]);

        $conflict = $this->findPeopleConflict(
            (int) $validated['Batch_ID'],
            (int) $validated['User_ID'],
            $validated['Class_Start_Time'],
            $validated['Class_End_Time']
        );

        if ($conflict) {
            return response()->json([
                'message' => 'This batch or lecturer already has a class during the requested time',
                'conflict' => $conflict,
            ], 422);
        }

        $validated['Status'] = $validated['Status'] ?? 'Pending';
        $validated['Is_Deleted'] = false;

        return BookingRequest::create($validated)
            ->load(['course', 'batch', 'user', 'equipmentRequestList.equipment']);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return $this->index();
        }

        if (!is_numeric($param)) {
            return response()->json(['message' => 'Invalid parameter format'], 400);
        }

        $bookingRequest = BookingRequest::where('Is_Deleted', false)
            ->with(['course', 'batch', 'user', 'equipmentRequestList.equipment', 'roomBookings.room'])
            ->find($param);

        if (!$bookingRequest) {
            return response()->json(['message' => 'Booking request not found'], 404);
        }

        return $bookingRequest;
    }

    public function update(Request $request, BookingRequest $bookingRequest)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($bookingRequest->Is_Deleted) {
            return response()->json(['message' => 'Booking request not found'], 404);
        }

        $validated = $request->validate([
            'Course_ID' => 'sometimes|required|integer|exists:courses,Course_ID',
            'Batch_ID' => 'sometimes|required|integer|exists:Batches,Batch_ID',
            'User_ID' => 'sometimes|required|integer|exists:users,User_ID',
            'ERL_ID' => 'sometimes|nullable|integer|exists:EquipmentRequestList,ERL_ID',
            'Class_Type' => ['sometimes', 'required', Rule::in(['Practical', 'Theory', 'Lesson'])],
            'Expected_Student_Count' => 'sometimes|required|integer|min:1',
            'Class_Start_Time' => 'sometimes|required|date',
            'Class_End_Time' => 'sometimes|required|date',
            'Status' => ['sometimes', 'required', Rule::in(['Confirmed', 'Pending', 'Rejected'])],
        ]);

        $startTime = $validated['Class_Start_Time'] ?? $bookingRequest->Class_Start_Time;
        $endTime = $validated['Class_End_Time'] ?? $bookingRequest->Class_End_Time;
        $batchId = (int) ($validated['Batch_ID'] ?? $bookingRequest->Batch_ID);
        $userId = (int) ($validated['User_ID'] ?? $bookingRequest->User_ID);

        if (strtotime((string) $endTime) <= strtotime((string) $startTime)) {
            return response()->json(['message' => 'Class end time must be after class start time'], 422);
        }

        $conflict = $this->findPeopleConflict($batchId, $userId, $startTime, $endTime, $bookingRequest->BookRequest_ID);

        if ($conflict) {
            return response()->json([
                'message' => 'This batch or lecturer already has a class during the requested time',
                'conflict' => $conflict,
            ], 422);
        }

        $bookingRequest->update($validated);

        return $bookingRequest->load(['course', 'batch', 'user', 'equipmentRequestList.equipment', 'roomBookings.room']);
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!is_numeric($param)) {
            return response()->json(['message' => 'Invalid parameter format'], 400);
        }

        $bookingRequest = BookingRequest::find($param);

        if (!$bookingRequest || $bookingRequest->Is_Deleted) {
            return response()->json(['message' => 'Booking request not found'], 404);
        }

        $bookingRequest->update(['Is_Deleted' => true]);

        return response()->json(['message' => 'Booking request marked as deleted successfully']);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!is_numeric($param)) {
            return response()->json(['message' => 'Invalid parameter format'], 400);
        }

        $bookingRequest = BookingRequest::find($param);

        if (!$bookingRequest) {
            return response()->json(['message' => 'Booking request not found'], 404);
        }

        if (!$bookingRequest->Is_Deleted) {
            return response()->json(['message' => 'Booking request is not deleted'], 400);
        }

        $bookingRequest->update(['Is_Deleted' => false]);

        return response()->json(['message' => 'Booking request recovered successfully']);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return BookingRequest::where('Is_Deleted', true)
                ->with(['course', 'batch', 'user', 'equipmentRequestList.equipment', 'roomBookings.room'])
                ->get();
        }

        if (!is_numeric($param)) {
            return response()->json(['message' => 'Invalid parameter format'], 400);
        }

        $bookingRequest = BookingRequest::where('Is_Deleted', true)
            ->with(['course', 'batch', 'user', 'equipmentRequestList.equipment', 'roomBookings.room'])
            ->find($param);

        if (!$bookingRequest) {
            return response()->json(['message' => 'Deleted booking request not found'], 404);
        }

        return $bookingRequest;
    }

    private function findPeopleConflict(int $batchId, int $userId, string $startTime, string $endTime, ?int $ignoreId = null): ?array
    {
        $query = BookingRequest::where('Is_Deleted', false)
            ->where('Status', '!=', 'Rejected')
            ->where(function ($query) use ($batchId, $userId) {
                $query->where('Batch_ID', $batchId)
                    ->orWhere('User_ID', $userId);
            })
            ->where('Class_Start_Time', '<', $endTime)
            ->where('Class_End_Time', '>', $startTime);

        if ($ignoreId) {
            $query->where('BookRequest_ID', '!=', $ignoreId);
        }

        $booking = $query->first();

        if (!$booking) {
            return null;
        }

        return [
            'BookRequest_ID' => $booking->BookRequest_ID,
            'Course_ID' => $booking->Course_ID,
            'Batch_ID' => $booking->Batch_ID,
            'User_ID' => $booking->User_ID,
            'Class_Start_Time' => $booking->Class_Start_Time,
            'Class_End_Time' => $booking->Class_End_Time,
        ];
    }
}
