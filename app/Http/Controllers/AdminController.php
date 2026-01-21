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
            'image_url' => 'nullable|string',
            'gallery_images' => 'nullable|string',
            'address' => 'nullable|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'sqft' => 'nullable|integer',
        ]);

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
            'image_url' => 'nullable|string',
            'gallery_images' => 'nullable|string',
            'address' => 'nullable|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'sqft' => 'nullable|integer',
        ]);

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
