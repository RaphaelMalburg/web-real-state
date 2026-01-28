<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Property;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $properties = Property::all();

        if ($properties->isEmpty()) {
            return;
        }

        $bookings = [
            [
                'property_id' => $properties->random()->id,
                'user_name' => 'John Doe',
                'user_email' => 'john@example.com',
                'booking_date' => now()->addDays(7)->format('Y-m-d'),
                'status' => 'pending',
            ],
            [
                'property_id' => $properties->random()->id,
                'user_name' => 'Jane Smith',
                'user_email' => 'jane@example.com',
                'booking_date' => now()->addDays(10)->format('Y-m-d'),
                'status' => 'confirmed',
            ],
        ];

        foreach ($bookings as $booking) {
            Booking::firstOrCreate(
                [
                    'user_email' => $booking['user_email'],
                    'booking_date' => $booking['booking_date'],
                    'property_id' => $booking['property_id']
                ],
                $booking
            );
        }
    }
}
