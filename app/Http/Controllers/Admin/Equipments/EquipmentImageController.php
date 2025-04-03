<?php

namespace App\Http\Controllers\Admin\Equipments;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\EquipmentImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EquipmentImageController extends Controller
{
    private $adminVerifier;
    private $uploadPath = 'public/equipmentImages';

    public function __construct(AdminVerificationController $adminVerifier)
    {
        $this->middleware('auth:sanctum');
        $this->adminVerifier = $adminVerifier;

        if (!Storage::exists($this->uploadPath)) {
            Storage::makeDirectory($this->uploadPath);
        }
    }

    public function index()
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $images = EquipmentImage::where('Is_Deleted', false)
            ->with('equipment')
            ->get();

        if ($images->isEmpty()) {
            return response()->json(['message' => 'No equipment images found'], 404);
        }

        return $images;
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'Equip_ID' => 'required|exists:Equipment,Equip_ID',
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'EQI_Discription' => 'required|string'
        ]);

        $uploadedImages = [];
        $errors = [];

        foreach ($request->file('images') as $image) {
            try {
                $filename = $this->generateEncryptedFilename($image);
                if ($image->storeAs($this->uploadPath, $filename)) {
                    $equipmentImage = EquipmentImage::create([
                        'Equip_ID' => $request->Equip_ID,
                        'EQI_Image' => $filename,
                        'EQI_Discription' => $request->EQI_Discription,
                        'Is_Deleted' => false
                    ]);
                    $uploadedImages[] = $equipmentImage->load('equipment');
                }
            } catch (\Exception $e) {
                $errors[] = $image->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }

        return response()->json([
            'message' => 'Images uploaded successfully',
            'uploaded' => $uploadedImages,
            'errors' => $errors
        ]);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $images = EquipmentImage::where('Is_Deleted', false)
                ->with('equipment')
                ->get();
            if ($images->isEmpty()) {
                return response()->json(['message' => 'No equipment images found'], 404);
            }
            return $images;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $images = EquipmentImage::where('Is_Deleted', false)
                    ->whereBetween('EQI_ID', [$start, $end])
                    ->with('equipment')
                    ->get();
                if ($images->isEmpty()) {
                    return response()->json(['message' => "No equipment images found in range $start-$end"], 404);
                }
                return $images;
            }
        }

        if (is_numeric($param)) {
            $image = EquipmentImage::with('equipment')->find($param);
            if (!$image || $image->Is_Deleted) {
                return response()->json(['message' => 'Equipment image not found'], 404);
            }
            return $image;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, EquipmentImage $equipmentimage)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($equipmentimage->Is_Deleted) {
            return response()->json(['message' => 'Equipment image not found'], 404);
        }

        $validated = $request->validate([
            'Equip_ID' => 'sometimes|required|exists:Equipment,Equip_ID',
            'EQI_Discription' => 'sometimes|required|string'
        ]);

        $equipmentimage->update($validated);
        return $equipmentimage->load('equipment');
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $images = EquipmentImage::where('Is_Deleted', false)->get();
            foreach ($images as $image) {
                Storage::delete($this->uploadPath . '/' . $image->EQI_Image);
                $image->update(['Is_Deleted' => true]);
            }
            return response()->json(['message' => 'All equipment images marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $images = EquipmentImage::whereBetween('EQI_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->get();
                foreach ($images as $image) {
                    Storage::delete($this->uploadPath . '/' . $image->EQI_Image);
                    $image->update(['Is_Deleted' => true]);
                }
                return response()->json(['message' => "Equipment images from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $image = EquipmentImage::find($param);
            if (!$image || $image->Is_Deleted) {
                return response()->json(['message' => 'Equipment image not found'], 404);
            }
            Storage::delete($this->uploadPath . '/' . $image->EQI_Image);
            $image->Is_Deleted = true;
            $image->save();
            return response()->json(['message' => 'Equipment image marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            EquipmentImage::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All equipment images recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                EquipmentImage::whereBetween('EQI_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Equipment images from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $image = EquipmentImage::find($param);
            if (!$image) {
                return response()->json(['message' => 'Equipment image not found'], 404);
            }
            if (!$image->Is_Deleted) {
                return response()->json(['message' => 'Equipment image is not deleted'], 400);
            }
            $image->Is_Deleted = false;
            $image->save();
            return response()->json(['message' => 'Equipment image recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $images = EquipmentImage::where('Is_Deleted', true)->with('equipment')->get();
            if ($images->isEmpty()) {
                return response()->json(['message' => 'No deleted equipment images found'], 404);
            }
            return $images;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $images = EquipmentImage::where('Is_Deleted', true)
                    ->whereBetween('EQI_ID', [$start, $end])
                    ->with('equipment')
                    ->get();
                if ($images->isEmpty()) {
                    return response()->json(['message' => "No deleted equipment images found in range $start-$end"], 404);
                }
                return $images;
            }
        }

        if (is_numeric($param)) {
            $image = EquipmentImage::with('equipment')
                    ->where('EQI_ID', $param)
                    ->where('Is_Deleted', true)
                    ->first();
            if (!$image) {
                return response()->json(['message' => 'Deleted equipment image not found'], 404);
            }
            return $image;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    private function generateEncryptedFilename($image)
    {
        $extension = $image->getClientOriginalExtension();
        $timestamp = time();
        $random = Str::random(16);
        return hash('sha256', $random . $timestamp) . '.' . $extension;
    }
}
