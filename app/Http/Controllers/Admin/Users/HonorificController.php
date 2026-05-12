<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\Honorific;
use Illuminate\Http\Request;

class HonorificController extends Controller
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

        return Honorific::where('Is_Deleted', false)->get();
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $exists = Honorific::where('Honorific', $request->Honorific)
            ->where('Is_Deleted', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'An honorific with this value already exists',
                'field' => 'Honorific'
            ], 422);
        }

        $validated = $request->validate([
            'Honorific' => 'required|string|max:50'
        ]);

        $validated['Is_Deleted'] = false;
        return Honorific::create($validated);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return Honorific::where('Is_Deleted', false)->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Honorific::where('Is_Deleted', false)
                    ->whereBetween('Honorifics_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $honorific = Honorific::find($param);
            if (!$honorific || $honorific->Is_Deleted) {
                return response()->json(['message' => 'Honorific not found'], 404);
            }
            return $honorific;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, Honorific $honorific)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($honorific->Is_Deleted) {
            return response()->json(['message' => 'Honorific not found'], 404);
        }

        if ($request->has('Honorific')) {
            $exists = Honorific::where('Honorific', $request->Honorific)
                ->where('Honorifics_ID', '!=', $honorific->Honorifics_ID)
                ->where('Is_Deleted', false)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'An honorific with this value already exists',
                    'field' => 'Honorific'
                ], 422);
            }
        }

        $validated = $request->validate([
            'Honorific' => 'sometimes|required|string|max:50'
        ]);

        $honorific->update($validated);
        return $honorific;
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Honorific::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All honorifics marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Honorific::whereBetween('Honorifics_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Honorifics from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $honorific = Honorific::find($param);
            if (!$honorific || $honorific->Is_Deleted) {
                return response()->json(['message' => 'Honorific not found'], 404);
            }
            $honorific->Is_Deleted = true;
            $honorific->save();
            return response()->json(['message' => 'Honorific marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Honorific::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All honorifics recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Honorific::whereBetween('Honorifics_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Honorifics from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $honorific = Honorific::find($param);
            if (!$honorific) {
                return response()->json(['message' => 'Honorific not found'], 404);
            }
            if (!$honorific->Is_Deleted) {
                return response()->json(['message' => 'Honorific is not deleted'], 400);
            }
            $honorific->Is_Deleted = false;
            $honorific->save();
            return response()->json(['message' => 'Honorific recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return Honorific::where('Is_Deleted', true)->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Honorific::where('Is_Deleted', true)
                    ->whereBetween('Honorifics_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $honorific = Honorific::where('Honorifics_ID', $param)
                ->where('Is_Deleted', true)
                ->first();
            if (!$honorific) {
                return response()->json(['message' => 'Deleted honorific not found'], 404);
            }
            return $honorific;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
