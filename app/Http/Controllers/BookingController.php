<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'booking_date' => 'required|date|after:today',
        ]);

        Booking::create($validated);

        return back()->with('success', 'Booking requested successfully! We will contact you soon.');
    }
}
