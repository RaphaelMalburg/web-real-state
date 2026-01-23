<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Inquiry;
use App\Models\Property;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $properties = Property::latest()->get();
        $bookings = Booking::with('property')->latest()->get();
        $inquiries = Inquiry::latest()->get();

        return view('admin.index', compact('properties', 'bookings', 'inquiries'));
    }

    public function create()
    {
        return view('admin.create');
    }

    // Helper to process image: Resize & Compress
    private function processImage($file, $maxWidth = 1024, $quality = 75)
    {
        // Increase memory for this operation
        ini_set('memory_limit', '512M');

        $imageString = file_get_contents($file);
        $image = imagecreatefromstring($imageString);
        
        if (!$image) {
            return null;
        }

        // Get original dimensions
        $width = imagesx($image);
        $height = imagesy($image);

        // Resize if needed
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = intval($height * ($newWidth / $width));
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            
            // Handle transparency for PNG/GIF
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        // Buffer output
        ob_start();
        // Convert everything to JPEG for consistent compression (or keep format if needed)
        // For simplicity in this POC, let's output as JPEG which compresses well.
        // If transparency is critical (PNG), we'd need logic to check type.
        // Let's stick to original mime type if possible, or force JPEG for size.
        // For real estate photos, JPEG is standard.
        
        // Check original mime
        $mime = $file->getMimeType();
        if ($mime === 'image/png') {
             // PNG compression (0-9, 9 is max compression)
             // We can convert PNG to JPG to save space if transparency isn't used.
             // Let's preserve PNG but compress.
             imagepng($image, null, 6); 
        } else {
             // JPEG
             imagejpeg($image, null, $quality);
        }
        
        $contents = ob_get_clean();
        imagedestroy($image);

        return 'data:' . $mime . ';base64,' . base64_encode($contents);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'type' => 'required|in:Rent,Sale',
            'image_url' => 'nullable|image|max:10240', // 10MB Max
            'gallery_images.*' => 'nullable|image|max:10240', // Validate each gallery image
            'address' => 'nullable|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'sqft' => 'nullable|integer',
        ]);

        if ($request->hasFile('image_url')) {
            $processed = $this->processImage($request->file('image_url'));
            if ($processed) {
                $validated['image_url'] = $processed;
            }
        }

        // Handle Gallery Images
        $galleryData = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $processed = $this->processImage($file);
                if ($processed) {
                    $galleryData[] = $processed;
                }
            }
        }
        
        // Store as JSON
        $validated['gallery_images'] = !empty($galleryData) ? json_encode($galleryData) : null;

        Property::create($validated);

        return redirect()->route('admin.index')->with('success', 'Property created successfully.');
    }

    public function edit(Property $property)
    {
        return view('admin.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'type' => 'required|in:Rent,Sale',
            'image_url' => 'nullable|image|max:10240',
            'gallery_images.*' => 'nullable|image|max:10240',
            'address' => 'nullable|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'sqft' => 'nullable|integer',
        ]);

        if ($request->hasFile('image_url')) {
            $processed = $this->processImage($request->file('image_url'));
            if ($processed) {
                $validated['image_url'] = $processed;
            }
        } else {
            // If no new image, keep the old one
            unset($validated['image_url']);
        }

        // Handle Gallery Images
        $currentGallery = [];
        if ($property->gallery_images) {
            $decoded = json_decode($property->gallery_images, true);
            if (is_array($decoded)) {
                $currentGallery = $decoded;
            } else {
                // Handle legacy comma-separated
                $currentGallery = array_filter(explode(',', $property->gallery_images));
            }
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $processed = $this->processImage($file);
                if ($processed) {
                    $currentGallery[] = $processed;
                }
            }
            // Update the validated array with the merged gallery
            $validated['gallery_images'] = json_encode($currentGallery);
        } else {
             unset($validated['gallery_images']);
        }

        $property->update($validated);

        return redirect()->route('admin.index')->with('success', 'Property updated successfully.');
    }

    public function confirmDelete(Property $property)
    {
        return view('admin.delete', compact('property'));
    }

    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()->route('admin.index')->with('success', 'Property removed successfully.');
    }
}
