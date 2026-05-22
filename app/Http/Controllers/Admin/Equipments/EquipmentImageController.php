<?php

namespace App\Http\Controllers\Admin\Equipments;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\EquipmentImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class EquipmentImageController extends Controller
{
    private $adminVerifier;
    private $uploadPath = 'public/equipImage';
    private $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
    private $maxFileSize = 5242880; // 5MB

    public function __construct(AdminVerificationController $adminVerifier)
    {
        $this->middleware('auth:sanctum');
        $this->adminVerifier = $adminVerifier;

        // Create directory if it doesn't exist
        if (!File::isDirectory(storage_path('app/' . $this->uploadPath))) {
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
            'Equip_ID' => 'required|exists:Equipments,Equip_ID',
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'EQI_Discription' => 'required|string'
        ]);

        $uploadedImages = [];
        $errors = [];

        foreach ($request->file('images') as $image) {
            try {
                // Validate mime type
                if (!in_array($image->getMimeType(), $this->allowedMimes)) {
                    $errors[] = $image->getClientOriginalName() . ': Invalid file type';
                    continue;
                }

                // Generate encrypted filename
                $encryptedName = $this->generateEncryptedFilename($image);

                // Process and store image
                $processedImage = $this->processAndStoreImage($image, $encryptedName);

                if ($processedImage['success']) {
                    // Create database record
                    $equipmentImage = EquipmentImage::create([
                        'Equip_ID' => $request->Equip_ID,
                        'EQI_Image' => $encryptedName,
                        'EQI_Discription' => $request->EQI_Discription,
                        'Is_Deleted' => false
                    ]);

                    $uploadedImages[] = $equipmentImage->load('equipment');
                } else {
                    $errors[] = $image->getClientOriginalName() . ': ' . $processedImage['message'];
                }

            } catch (\Exception $e) {
                $errors[] = $image->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }

        if (empty($uploadedImages) && !empty($errors)) {
            return response()->json([
                'message' => 'Failed to upload images',
                'errors' => $errors
            ], 422);
        }

        return response()->json([
            'message' => 'Images uploaded successfully',
            'uploaded' => $uploadedImages,
            'errors' => $errors
        ]);
    }

    protected function processAndStoreImage($image, $filename)
    {
        try {
            // Simple file storage without processing
            if ($image->storeAs($this->uploadPath, $filename)) {
                return ['success' => true];
            }

            throw new \Exception('Failed to store image');

        } catch (\Exception $e) {
            Log::error('Image storage error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Image storage failed: ' . $e->getMessage()
            ];
        }
    }

    protected function generateEncryptedFilename($file)
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = time();
        $random = Str::random(16);
        return hash('sha256', $random . $timestamp) . '.' . $extension;
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
            'Equip_ID' => 'sometimes|required|exists:Equipments,Equip_ID',
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

        try {
            if ($param === '*') {
                $images = EquipmentImage::where('Is_Deleted', false)->get();
                foreach ($images as $image) {
                    Storage::delete($this->uploadPath . '/' . $image->EQI_Image);
                    $image->update(['Is_Deleted' => true]);
                }
                return response()->json(['message' => 'All equipment images deleted successfully']);
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
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting images: ' . $e->getMessage()], 500);
        }
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
}
