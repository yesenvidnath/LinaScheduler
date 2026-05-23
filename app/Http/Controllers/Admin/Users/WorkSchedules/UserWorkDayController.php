<?php

namespace App\Http\Controllers\Admin\Users\WorkSchedules;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\UserWorkDay;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserWorkDayController extends Controller
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

        return UserWorkDay::where('Is_Deleted', false)
            ->with(['user', 'workMode'])
            ->orderBy('User_ID')
            ->orderByRaw("FIELD(Day_Of_Week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->get();
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $this->validateWorkDay($request);

        if (strtotime((string) $validated['Work_End_Time']) <= strtotime((string) $validated['Work_Start_Time'])) {
            return response()->json(['message' => 'Work end time must be after work start time'], 422);
        }

        $conflict = $this->findDuplicateWorkDay(
            (int) $validated['User_ID'],
            $validated['Day_Of_Week']
        );

        if ($conflict) {
            return response()->json([
                'message' => 'This user already has an active schedule for this day',
                'field' => 'Day_Of_Week',
                'conflict' => $conflict,
            ], 422);
        }

        $validated['Is_Deleted'] = false;

        return UserWorkDay::create($validated)->load(['user', 'workMode']);
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
                return UserWorkDay::where('Is_Deleted', false)
                    ->whereBetween('UWD_ID', [$start, $end])
                    ->with(['user', 'workMode'])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $workDay = UserWorkDay::with(['user', 'workMode'])->find($param);
            if (!$workDay || $workDay->Is_Deleted) {
                return response()->json(['message' => 'User work day not found'], 404);
            }

            return $workDay;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, UserWorkDay $workDay)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($workDay->Is_Deleted) {
            return response()->json(['message' => 'User work day not found'], 404);
        }

        $validated = $this->validateWorkDay($request, true);

        $userId = (int) ($validated['User_ID'] ?? $workDay->User_ID);
        $dayOfWeek = $validated['Day_Of_Week'] ?? $workDay->Day_Of_Week;
        $startTime = $validated['Work_Start_Time'] ?? $workDay->Work_Start_Time;
        $endTime = $validated['Work_End_Time'] ?? $workDay->Work_End_Time;

        if (strtotime((string) $endTime) <= strtotime((string) $startTime)) {
            return response()->json(['message' => 'Work end time must be after work start time'], 422);
        }

        $conflict = $this->findDuplicateWorkDay($userId, $dayOfWeek, $workDay->UWD_ID);

        if ($conflict) {
            return response()->json([
                'message' => 'This user already has an active schedule for this day',
                'field' => 'Day_Of_Week',
                'conflict' => $conflict,
            ], 422);
        }

        $workDay->update($validated);

        return $workDay->load(['user', 'workMode']);
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            UserWorkDay::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All user work days marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            [$start, $end] = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                UserWorkDay::whereBetween('UWD_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "User work days from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $workDay = UserWorkDay::find($param);
            if (!$workDay || $workDay->Is_Deleted) {
                return response()->json(['message' => 'User work day not found'], 404);
            }

            $workDay->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'User work day marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            UserWorkDay::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All user work days recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            [$start, $end] = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                UserWorkDay::whereBetween('UWD_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "User work days from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $workDay = UserWorkDay::find($param);
            if (!$workDay) {
                return response()->json(['message' => 'User work day not found'], 404);
            }

            if (!$workDay->Is_Deleted) {
                return response()->json(['message' => 'User work day is not deleted'], 400);
            }

            $conflict = $this->findDuplicateWorkDay(
                (int) $workDay->User_ID,
                $workDay->Day_Of_Week,
                $workDay->UWD_ID
            );

            if ($conflict) {
                return response()->json([
                    'message' => 'Cannot recover because this user already has an active schedule for this day',
                    'conflict' => $conflict,
                ], 422);
            }

            $workDay->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'User work day recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return UserWorkDay::where('Is_Deleted', true)
                ->with(['user', 'workMode'])
                ->get();
        }

        if (strpos($param, '-') !== false) {
            [$start, $end] = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return UserWorkDay::where('Is_Deleted', true)
                    ->whereBetween('UWD_ID', [$start, $end])
                    ->with(['user', 'workMode'])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $workDay = UserWorkDay::where('UWD_ID', $param)
                ->where('Is_Deleted', true)
                ->with(['user', 'workMode'])
                ->first();

            if (!$workDay) {
                return response()->json(['message' => 'Deleted user work day not found'], 404);
            }

            return $workDay;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    private function validateWorkDay(Request $request, bool $isUpdate = false): array
    {
        $required = $isUpdate ? 'sometimes|required' : 'required';

        return $request->validate([
            'User_ID' => "$required|integer|exists:users,User_ID",
            'Work_Mode_ID' => "$required|integer|exists:Work_Modes,Work_Mode_ID",
            'Day_Of_Week' => [
                $isUpdate ? 'sometimes' : 'required',
                Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']),
            ],
            'Work_Start_Time' => "$required|date_format:H:i",
            'Work_End_Time' => "$required|date_format:H:i",
            'Status' => [$isUpdate ? 'sometimes' : 'required', Rule::in(['Active', 'Inactive'])],
        ]);
    }

    private function findDuplicateWorkDay(int $userId, string $dayOfWeek, ?int $ignoreId = null): ?array
    {
        $query = UserWorkDay::where('Is_Deleted', false)
            ->where('User_ID', $userId)
            ->where('Day_Of_Week', $dayOfWeek);

        if ($ignoreId) {
            $query->where('UWD_ID', '!=', $ignoreId);
        }

        $workDay = $query->first();

        if (!$workDay) {
            return null;
        }

        return [
            'UWD_ID' => $workDay->UWD_ID,
            'User_ID' => $workDay->User_ID,
            'Day_Of_Week' => $workDay->Day_Of_Week,
            'Work_Start_Time' => $workDay->Work_Start_Time,
            'Work_End_Time' => $workDay->Work_End_Time,
        ];
    }
}
