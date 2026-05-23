<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\BookingRequest;
use App\Models\LectureAllocation;
use App\Models\UserWorkDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LectureAllocationController extends Controller
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

        return LectureAllocation::where('Is_Deleted', false)
            ->with(['lecturer', 'batch', 'course', 'classRoom.room'])
            ->orderBy('Allocation_Date')
            ->orderBy('Session_Start_Time')
            ->get();
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $this->validateAllocation($request);
        $validated['Day_Of_Week'] = Carbon::parse($validated['Allocation_Date'])->format('l');

        $availabilityError = $this->validateLecturerAvailability($validated);
        if ($availabilityError) {
            return $availabilityError;
        }

        $conflict = $this->findLecturerConflict($validated);
        if ($conflict) {
            return response()->json([
                'message' => 'This lecturer is already allocated or booked during the requested time',
                'conflict' => $conflict,
            ], 422);
        }

        $batchConflict = $this->findBatchConflict($validated);
        if ($batchConflict) {
            return response()->json([
                'message' => 'This batch already has a session during the requested time',
                'conflict' => $batchConflict,
            ], 422);
        }

        $classConflict = $this->findClassConflict($validated);
        if ($classConflict) {
            return response()->json([
                'message' => 'This class is already allocated during the requested time',
                'conflict' => $classConflict,
            ], 422);
        }

        $validated['Is_Deleted'] = false;

        return LectureAllocation::create($validated)
            ->load(['lecturer', 'batch', 'course', 'classRoom.room']);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return $this->index();
        }

        if (strpos($param, '-') !== false) {
            [$start, $end] = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return LectureAllocation::where('Is_Deleted', false)
                    ->whereBetween('LA_ID', [$start, $end])
                    ->with(['lecturer', 'batch', 'course', 'classRoom.room'])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $allocation = LectureAllocation::with(['lecturer', 'batch', 'course', 'classRoom.room'])->find($param);
            if (!$allocation || $allocation->Is_Deleted) {
                return response()->json(['message' => 'Lecture allocation not found'], 404);
            }

            return $allocation;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, LectureAllocation $lectureAllocation)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($lectureAllocation->Is_Deleted) {
            return response()->json(['message' => 'Lecture allocation not found'], 404);
        }

        $validated = $this->validateAllocation($request, true);
        $merged = array_merge($lectureAllocation->toArray(), $validated);
        $merged['Day_Of_Week'] = Carbon::parse($merged['Allocation_Date'])->format('l');

        if (strtotime((string) $merged['Session_End_Time']) <= strtotime((string) $merged['Session_Start_Time'])) {
            return response()->json(['message' => 'Session end time must be after session start time'], 422);
        }

        $availabilityError = $this->validateLecturerAvailability($merged);
        if ($availabilityError) {
            return $availabilityError;
        }

        $conflict = $this->findLecturerConflict($merged, $lectureAllocation->LA_ID);
        if ($conflict) {
            return response()->json([
                'message' => 'This lecturer is already allocated or booked during the requested time',
                'conflict' => $conflict,
            ], 422);
        }

        $batchConflict = $this->findBatchConflict($merged, $lectureAllocation->LA_ID);
        if ($batchConflict) {
            return response()->json([
                'message' => 'This batch already has a session during the requested time',
                'conflict' => $batchConflict,
            ], 422);
        }

        $classConflict = $this->findClassConflict($merged, $lectureAllocation->LA_ID);
        if ($classConflict) {
            return response()->json([
                'message' => 'This class is already allocated during the requested time',
                'conflict' => $classConflict,
            ], 422);
        }

        $validated['Day_Of_Week'] = $merged['Day_Of_Week'];
        $lectureAllocation->update($validated);

        return $lectureAllocation->load(['lecturer', 'batch', 'course', 'classRoom.room']);
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            LectureAllocation::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All lecture allocations marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            [$start, $end] = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                LectureAllocation::whereBetween('LA_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Lecture allocations from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $allocation = LectureAllocation::find($param);
            if (!$allocation || $allocation->Is_Deleted) {
                return response()->json(['message' => 'Lecture allocation not found'], 404);
            }

            $allocation->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'Lecture allocation marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            LectureAllocation::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All lecture allocations recovered successfully']);
        }

        if (!is_numeric($param)) {
            return response()->json(['message' => 'Invalid parameter format'], 400);
        }

        $allocation = LectureAllocation::find($param);
        if (!$allocation) {
            return response()->json(['message' => 'Lecture allocation not found'], 404);
        }

        if (!$allocation->Is_Deleted) {
            return response()->json(['message' => 'Lecture allocation is not deleted'], 400);
        }

        $data = $allocation->toArray();
        $availabilityError = $this->validateLecturerAvailability($data);
        if ($availabilityError) {
            return $availabilityError;
        }

        $conflict = $this->findLecturerConflict($data, $allocation->LA_ID);
        if ($conflict) {
            return response()->json([
                'message' => 'Cannot recover because this lecturer is already booked during this time',
                'conflict' => $conflict,
            ], 422);
        }

        $allocation->update(['Is_Deleted' => false]);
        return response()->json(['message' => 'Lecture allocation recovered successfully']);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return LectureAllocation::where('Is_Deleted', true)
                ->with(['lecturer', 'batch', 'course', 'classRoom.room'])
                ->get();
        }

        if (strpos($param, '-') !== false) {
            [$start, $end] = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return LectureAllocation::where('Is_Deleted', true)
                    ->whereBetween('LA_ID', [$start, $end])
                    ->with(['lecturer', 'batch', 'course', 'classRoom.room'])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $allocation = LectureAllocation::where('LA_ID', $param)
                ->where('Is_Deleted', true)
                ->with(['lecturer', 'batch', 'course', 'classRoom.room'])
                ->first();

            if (!$allocation) {
                return response()->json(['message' => 'Deleted lecture allocation not found'], 404);
            }

            return $allocation;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    private function validateAllocation(Request $request, bool $isUpdate = false): array
    {
        $required = $isUpdate ? 'sometimes|required' : 'required';

        return $request->validate([
            'Lecturer_User_ID' => "$required|integer|exists:users,User_ID",
            'Batch_ID' => "$required|integer|exists:Batches,Batch_ID",
            'Course_ID' => 'sometimes|nullable|integer|exists:courses,Course_ID',
            'Cls_ID' => 'sometimes|nullable|integer|exists:classes,Cls_ID',
            'Allocation_Date' => "$required|date",
            'Session_Start_Time' => "$required|date_format:H:i",
            'Session_End_Time' => "$required|date_format:H:i",
            'Session_Type' => [$isUpdate ? 'sometimes' : 'required', Rule::in(['Theory', 'Practical', 'Examination', 'Viva'])],
            'Is_Cancelled' => 'sometimes|boolean',
            'Is_Additional_Working_Situation' => 'sometimes|boolean',
            'Lecturer_Comment' => 'sometimes|nullable|string',
            'Coordinator_Comment' => 'sometimes|nullable|string',
        ]);
    }

    private function validateLecturerAvailability(array $data)
    {
        if (!empty($data['Is_Cancelled'])) {
            return null;
        }

        if (strtotime((string) $data['Session_End_Time']) <= strtotime((string) $data['Session_Start_Time'])) {
            return response()->json(['message' => 'Session end time must be after session start time'], 422);
        }

        if (!empty($data['Is_Additional_Working_Situation'])) {
            return null;
        }

        $workDay = UserWorkDay::where('Is_Deleted', false)
            ->where('Status', 'Active')
            ->where('User_ID', $data['Lecturer_User_ID'])
            ->where('Day_Of_Week', $data['Day_Of_Week'])
            ->first();

        if (!$workDay) {
            return response()->json([
                'message' => 'Lecturer is off on this day. Mark as an additional working situation to allow this allocation.',
                'field' => 'Is_Additional_Working_Situation',
            ], 422);
        }

        if ($data['Session_Start_Time'] < $workDay->Work_Start_Time || $data['Session_End_Time'] > $workDay->Work_End_Time) {
            return response()->json([
                'message' => 'Session time is outside the lecturer working duration. Mark as an additional working situation to allow this allocation.',
                'working_day' => $workDay,
            ], 422);
        }

        return null;
    }

    private function findLecturerConflict(array $data, ?int $ignoreId = null): ?array
    {
        if (!empty($data['Is_Cancelled'])) {
            return null;
        }

        $allocationQuery = LectureAllocation::where('Is_Deleted', false)
            ->where('Is_Cancelled', false)
            ->where('Lecturer_User_ID', $data['Lecturer_User_ID'])
            ->where('Allocation_Date', $data['Allocation_Date'])
            ->where('Session_Start_Time', '<', $data['Session_End_Time'])
            ->where('Session_End_Time', '>', $data['Session_Start_Time']);

        if ($ignoreId) {
            $allocationQuery->where('LA_ID', '!=', $ignoreId);
        }

        $allocation = $allocationQuery->first();
        if ($allocation) {
            return [
                'type' => 'LectureAllocation',
                'LA_ID' => $allocation->LA_ID,
                'Allocation_Date' => $allocation->Allocation_Date,
                'Session_Start_Time' => $allocation->Session_Start_Time,
                'Session_End_Time' => $allocation->Session_End_Time,
            ];
        }

        $startDateTime = $data['Allocation_Date'] . ' ' . $data['Session_Start_Time'];
        $endDateTime = $data['Allocation_Date'] . ' ' . $data['Session_End_Time'];

        $booking = BookingRequest::where('Is_Deleted', false)
            ->where('Status', '!=', 'Rejected')
            ->where('User_ID', $data['Lecturer_User_ID'])
            ->where('Class_Start_Time', '<', $endDateTime)
            ->where('Class_End_Time', '>', $startDateTime)
            ->first();

        if (!$booking) {
            return null;
        }

        return [
            'type' => 'BookingRequest',
            'BookRequest_ID' => $booking->BookRequest_ID,
            'Class_Start_Time' => $booking->Class_Start_Time,
            'Class_End_Time' => $booking->Class_End_Time,
        ];
    }

    private function findBatchConflict(array $data, ?int $ignoreId = null): ?array
    {
        if (!empty($data['Is_Cancelled'])) {
            return null;
        }

        $query = LectureAllocation::where('Is_Deleted', false)
            ->where('Is_Cancelled', false)
            ->where('Batch_ID', $data['Batch_ID'])
            ->where('Allocation_Date', $data['Allocation_Date'])
            ->where('Session_Start_Time', '<', $data['Session_End_Time'])
            ->where('Session_End_Time', '>', $data['Session_Start_Time']);

        if ($ignoreId) {
            $query->where('LA_ID', '!=', $ignoreId);
        }

        $allocation = $query->first();

        if (!$allocation) {
            return null;
        }

        return [
            'LA_ID' => $allocation->LA_ID,
            'Batch_ID' => $allocation->Batch_ID,
            'Allocation_Date' => $allocation->Allocation_Date,
            'Session_Start_Time' => $allocation->Session_Start_Time,
            'Session_End_Time' => $allocation->Session_End_Time,
        ];
    }

    private function findClassConflict(array $data, ?int $ignoreId = null): ?array
    {
        if (empty($data['Cls_ID']) || !empty($data['Is_Cancelled'])) {
            return null;
        }

        $query = LectureAllocation::where('Is_Deleted', false)
            ->where('Is_Cancelled', false)
            ->where('Cls_ID', $data['Cls_ID'])
            ->where('Allocation_Date', $data['Allocation_Date'])
            ->where('Session_Start_Time', '<', $data['Session_End_Time'])
            ->where('Session_End_Time', '>', $data['Session_Start_Time']);

        if ($ignoreId) {
            $query->where('LA_ID', '!=', $ignoreId);
        }

        $allocation = $query->first();

        if (!$allocation) {
            return null;
        }

        return [
            'LA_ID' => $allocation->LA_ID,
            'Cls_ID' => $allocation->Cls_ID,
            'Allocation_Date' => $allocation->Allocation_Date,
            'Session_Start_Time' => $allocation->Session_Start_Time,
            'Session_End_Time' => $allocation->Session_End_Time,
        ];
    }
}
