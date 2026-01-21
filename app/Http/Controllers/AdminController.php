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
            'gallery_images' => 'nullable|string',
            'address' => 'nullable|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'sqft' => 'nullable|integer',
        ]);

        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $base64 = base64_encode(file_get_contents($file));
            $mime = $file->getMimeType();
            $validated['image_url'] = 'data:' . $mime . ';base64,' . $base64;
        }

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
            'gallery_images' => 'nullable|string',
            'address' => 'nullable|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'sqft' => 'nullable|integer',
        ]);

        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $base64 = base64_encode(file_get_contents($file));
            $mime = $file->getMimeType();
            $validated['image_url'] = 'data:' . $mime . ';base64,' . $base64;
        } else {
            // If no new image, keep the old one (remove from validated so it doesn't overwrite with null if that passed)
            // Actually, if it wasn't in the request, it won't be in validated if it's nullable? 
            // Wait, validate returns only validated data. If 'image_url' is nullable and not present, it might be null or missing.
            // If it's missing from request, it shouldn't be in $validated?
            unset($validated['image_url']);
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
