<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\RoomImageList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class RoomImageListController extends Controller
{
    private $adminVerifier;
    private $uploadPath = 'public/roomImages';
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

        $images = RoomImageList::where('Is_Deleted', false)->with('room')->get();

        if ($images->isEmpty()) {
            return response()->json(['message' => 'No room images found'], 404);
        }

        return $images;
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'Room_ID' => 'required|exists:Rooms,Room_ID',
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'RIL_Discription' => 'required|string'
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
                    $roomImage = RoomImageList::create([
                        'Room_ID' => $request->Room_ID,
                        'RIL_Image' => $encryptedName,
                        'RIL_Discription' => $request->RIL_Discription,
                        'Is_Deleted' => false
                    ]);

                    $uploadedImages[] = $roomImage->load('room');
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
            // Create a new ImageManager instance with GD driver
            $manager = new ImageManager(
                new \Intervention\Image\Drivers\Gd\Driver()
            );

            // Read the image file
            $img = $manager->read($image->getRealPath());

            // Resize if larger than 800px width
            if ($img->width() > 800) {
                $img = $img->scaleDown(800);
            }

            // Convert and compress to JPEG
            $processedImage = $img->toJpeg(80);

            // Store the processed image
            if (Storage::put($this->uploadPath . '/' . $filename, $processedImage)) {
                return ['success' => true];
            }

            throw new \Exception('Failed to store image');

        } catch (\Exception $e) {
            Log::error('Image processing error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Image processing failed: ' . $e->getMessage()
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
            $images = RoomImageList::where('Is_Deleted', false)->with('room')->get();
            if ($images->isEmpty()) {
                return response()->json(['message' => 'No room images found'], 404);
            }
            return $images;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $images = RoomImageList::where('Is_Deleted', false)
                    ->whereBetween('RIL_ID', [$start, $end])
                    ->with('room')
                    ->get();

                if ($images->isEmpty()) {
                    return response()->json(['message' => "No room images found in range $start-$end"], 404);
                }
                return $images;
            }
        }

        if (is_numeric($param)) {
            $image = RoomImageList::with('room')->find($param);
            if (!$image || $image->Is_Deleted) {
                return response()->json(['message' => 'Room image not found'], 404);
            }
            return $image;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, RoomImageList $roomimage)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($roomimage->Is_Deleted) {
            return response()->json(['message' => 'Room image not found'], 404);
        }

        $validated = $request->validate([
            'Room_ID' => 'sometimes|required|exists:Rooms,Room_ID',
            'RIL_Image' => 'sometimes|required|string|max:255',
            'RIL_Discription' => 'sometimes|required|string'
        ]);

        $roomimage->update($validated);
        return $roomimage->load('room');
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            if ($param === '*') {
                $images = RoomImageList::where('Is_Deleted', false)->get();
                foreach ($images as $image) {
                    Storage::delete($this->uploadPath . '/' . $image->RIL_Image);
                    $image->update(['Is_Deleted' => true]);
                }
                return response()->json(['message' => 'All room images deleted successfully']);
            }

            if (strpos($param, '-') !== false) {
                list($start, $end) = explode('-', $param);
                if (is_numeric($start) && is_numeric($end)) {
                    $images = RoomImageList::whereBetween('RIL_ID', [$start, $end])
                        ->where('Is_Deleted', false)
                        ->get();
                    foreach ($images as $image) {
                        Storage::delete($this->uploadPath . '/' . $image->RIL_Image);
                        $image->update(['Is_Deleted' => true]);
                    }
                    return response()->json(['message' => "Room images from $start to $end marked as deleted successfully"]);
                }
            }

            if (is_numeric($param)) {
                $image = RoomImageList::find($param);
                if (!$image || $image->Is_Deleted) {
                    return response()->json(['message' => 'Room image not found'], 404);
                }
                Storage::delete($this->uploadPath . '/' . $image->RIL_Image);
                $image->Is_Deleted = true;
                $image->save();
                return response()->json(['message' => 'Room image marked as deleted successfully']);
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
            RoomImageList::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All room images recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                RoomImageList::whereBetween('RIL_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Room images from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $image = RoomImageList::find($param);
            if (!$image) {
                return response()->json(['message' => 'Room image not found'], 404);
            }
            if (!$image->Is_Deleted) {
                return response()->json(['message' => 'Room image is not deleted'], 400);
            }
            $image->Is_Deleted = false;
            $image->save();
            return response()->json(['message' => 'Room image recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $images = RoomImageList::where('Is_Deleted', true)->with('room')->get();
            if ($images->isEmpty()) {
                return response()->json(['message' => 'No deleted room images found'], 404);
            }
            return $images;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $images = RoomImageList::where('Is_Deleted', true)
                    ->whereBetween('RIL_ID', [$start, $end])
                    ->with('room')
                    ->get();

                if ($images->isEmpty()) {
                    return response()->json(['message' => "No deleted room images found in range $start-$end"], 404);
                }
                return $images;
            }
        }

        if (is_numeric($param)) {
            $image = RoomImageList::with('room')
                    ->where('RIL_ID', $param)
                    ->where('Is_Deleted', true)
                    ->first();
            if (!$image) {
                return response()->json(['message' => 'Deleted room image not found'], 404);
            }
            return $image;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
