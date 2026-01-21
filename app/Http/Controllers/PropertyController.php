<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::latest()->get();
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
}
