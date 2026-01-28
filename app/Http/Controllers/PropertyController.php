<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->input('bedrooms'));
        }

        if ($request->filled('price_range')) {
            $range = $request->input('price_range');
            if ($range === '1000000+') {
                $query->where('price', '>=', 1000000);
            } else {
                $parts = explode('-', $range);
                if (count($parts) === 2) {
                    $query->whereBetween('price', [(int)$parts[0], (int)$parts[1]]);
                }
            }
        }

        $properties = $query->latest()->get();
        $carousel_items = [
            ['img' => 'assets/images/real-estate/elite-properties/hero/elite-hero-luxury-penthouse.jpg', 'title' => 'Elite Luxury Living', 'desc' => 'Experience the pinnacle of urban sophistication.'],
            ['img' => 'assets/images/real-estate/urban-living/hero/urban-apartment-hero.jpg', 'title' => 'Modern Urban Style', 'desc' => 'Discover chic apartments in the heart of the city.'],
            ['img' => 'assets/images/real-estate/green-homes/hero/green-eco-house.jpg', 'title' => 'Sustainable Future', 'desc' => 'Live in harmony with nature in our eco-friendly homes.'],
            ['img' => 'assets/images/real-estate/elite-properties/hero/elite-hero-luxury.jpg', 'title' => 'Exclusive Estates', 'desc' => 'Discover architectural masterpieces and sprawling estates.']
        ];

        return view('index', compact('properties', 'carousel_items'));
    }

    public function gallery()
    {
        $properties = Property::latest()->get();
        return view('gallery', compact('properties'));
    }

    public function show(Property $property)
    {
        $gallery = [];
        if ($property->gallery_images) {
            $decoded = json_decode($property->gallery_images, true);
            $gallery = is_array($decoded) ? $decoded : array_filter(explode(',', $property->gallery_images));
        }

        // Suggested properties (excluding current)
        $suggested = Property::where('id', '!=', $property->id)
            ->where('type', $property->type)
            ->limit(3)
            ->get();

        return view('properties.show', compact('property', 'gallery', 'suggested'));
    }
}
