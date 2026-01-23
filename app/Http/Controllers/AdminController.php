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

        // Increase memory limit for image processing
        ini_set('memory_limit', '512M');

        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $base64 = base64_encode(file_get_contents($file));
            $mime = $file->getMimeType();
            $validated['image_url'] = 'data:' . $mime . ';base64,' . $base64;
        }

        // Handle Gallery Images
        $galleryData = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $base64 = base64_encode(file_get_contents($file));
                $mime = $file->getMimeType();
                $galleryData[] = 'data:' . $mime . ';base64,' . $base64;
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

        // Increase memory limit for image processing
        ini_set('memory_limit', '512M');

        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $base64 = base64_encode(file_get_contents($file));
            $mime = $file->getMimeType();
            $validated['image_url'] = 'data:' . $mime . ';base64,' . $base64;
        } else {
            // If no new image, keep the old one
            unset($validated['image_url']);
        }

        // Handle Gallery Images (Append to existing or replace? Usually replace or append. Let's append if new ones provided, but user might want to delete. 
        // For simplicity in this "nothing fancy" request: if new images uploaded, we ADD them. 
        // Actually, "make that upload field better" usually implies replacing or adding. 
        // Let's implement: New uploads replace old gallery? Or append? 
        // Standard "file input" behavior is "replace" if you select new files. But since we are storing in DB...
        // Let's go with: If files uploaded, REPLACE the gallery. (Simplest logic for now).
        // Wait, if I want to KEEP existing, I need a way to manage them. 
        // Given the constraints, let's just APPEND new images to existing ones.
        
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
                $base64 = base64_encode(file_get_contents($file));
                $mime = $file->getMimeType();
                $currentGallery[] = 'data:' . $mime . ';base64,' . $base64;
            }
            // Update the validated array with the merged gallery
            $validated['gallery_images'] = json_encode($currentGallery);
        } else {
             // If no new files, keep existing (unset so it doesn't overwrite with null)
             // But wait, if they want to clear it? There's no "clear" button yet. 
             // For now, if no file sent, we don't touch the gallery.
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
