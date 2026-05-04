<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
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

        $rooms = Room::where('Is_Deleted', false)->with('flow')->get();

        if ($rooms->isEmpty()) {
            return response()->json(['message' => 'No active rooms found'], 404);
        }

        return $rooms;
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check for duplicate room number in the same flow
        $exists = Room::where('Room_Number', $request->Room_Number)
            ->where('Fl_ID', $request->Fl_ID)
            ->where('Is_Deleted', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A room with this number already exists in this flow',
                'field' => 'Room_Number'
            ], 422);
        }

        $validated = $request->validate([
            'Fl_ID' => 'required|exists:Flows,Fl_ID',
            'Room_Number' => 'required|string|max:200',
            'Room_Discrption' => 'required|string',
            'Room_Availability' => 'required|in:0,1,1*',
            'Room_Type' => 'required|in:Library,Class,Laboratory,StudyArea',
            'Max_Student_Count' => 'required|integer|min:1',
            'Max_Chair_Count' => 'required|integer|min:0',
            'Max_Power_Outlets' => 'required|integer|min:0',
            'Max_Table_Count' => 'required|integer|min:0',
            'Is_WhiteBoard_Avilable' => 'required|boolean',
            'Is_Projector_Avilable' => 'required|boolean',
            'Is_Smart_board_Avilable' => 'required|boolean'
        ]);

        $validated['Is_Deleted'] = false;
        $room = Room::create($validated);
        return $room->load('flow');
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return Room::where('Is_Deleted', false)->with('flow')->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Room::where('Is_Deleted', false)
                    ->whereBetween('Room_ID', [$start, $end])
                    ->with('flow')
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $room = Room::with('flow')->find($param);
            if (!$room || $room->Is_Deleted) {
                return response()->json(['message' => 'Room not found'], 404);
            }
            return $room;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, Room $room)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($room->Is_Deleted) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        if ($request->has('Room_Number')) {
            $exists = Room::where('Room_Number', $request->Room_Number)
                ->where('Fl_ID', $request->Fl_ID ?? $room->Fl_ID)
                ->where('Room_ID', '!=', $room->Room_ID)
                ->where('Is_Deleted', false)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'A room with this number already exists in this flow',
                    'field' => 'Room_Number'
                ], 422);
            }
        }

        $validated = $request->validate([
            'Fl_ID' => 'sometimes|required|exists:Flows,Fl_ID',
            'Room_Number' => 'sometimes|required|string|max:200',
            'Room_Discrption' => 'sometimes|required|string',
            'Room_Availability' => 'sometimes|required|in:0,1,1*',
            'Room_Type' => 'sometimes|required|in:Library,Class,Laboratory,StudyArea',
            'Max_Student_Count' => 'sometimes|required|integer|min:1',
            'Max_Chair_Count' => 'sometimes|required|integer|min:0',
            'Max_Power_Outlets' => 'sometimes|required|integer|min:0',
            'Max_Table_Count' => 'sometimes|required|integer|min:0',
            'Is_WhiteBoard_Avilable' => 'sometimes|required|boolean',
            'Is_Projector_Avilable' => 'sometimes|required|boolean',
            'Is_Smart_board_Avilable' => 'sometimes|required|boolean'
        ]);

        $room->update($validated);
        return $room->load('flow');
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Room::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All rooms marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Room::whereBetween('Room_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Rooms from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $room = Room::find($param);
            if (!$room || $room->Is_Deleted) {
                return response()->json(['message' => 'Room not found'], 404);
            }
            $room->Is_Deleted = true;
            $room->save();
            return response()->json(['message' => 'Room marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Room::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All rooms recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Room::whereBetween('Room_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Rooms from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $room = Room::find($param);
            if (!$room) {
                return response()->json(['message' => 'Room not found'], 404);
            }
            if (!$room->Is_Deleted) {
                return response()->json(['message' => 'Room is not deleted'], 400);
            }
            $room->Is_Deleted = false;
            $room->save();
            return response()->json(['message' => 'Room recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $rooms = Room::where('Is_Deleted', true)->with('flow')->get();
            if ($rooms->isEmpty()) {
                return response()->json(['message' => 'No deleted rooms found'], 404);
            }
            return $rooms;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $rooms = Room::where('Is_Deleted', true)
                    ->whereBetween('Room_ID', [$start, $end])
                    ->with('flow')
                    ->get();

                if ($rooms->isEmpty()) {
                    return response()->json(['message' => "No deleted rooms found in range $start-$end"], 404);
                }
                return $rooms;
            }
        }

        if (is_numeric($param)) {
            $room = Room::with('flow')
                    ->where('Room_ID', $param)
                    ->where('Is_Deleted', true)
                    ->first();
            if (!$room) {
                return response()->json(['message' => 'Deleted room not found'], 404);
            }
            return $room;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
