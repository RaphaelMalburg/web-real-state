<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing properties to avoid duplicates or outdated paths
        Property::truncate();

        $properties = [
            [
                'title' => 'Elite Luxury Penthouse',
                'description' => 'Experience the pinnacle of urban living in this stunning 4-bedroom penthouse. Featuring panoramic city views, a private rooftop terrace, and high-end finishes throughout.',
                'price' => 2800000,
                'image_url' => 'assets/images/real-estate/elite-properties/hero/elite-hero-luxury-penthouse.jpg',
                'gallery_images' => 'assets/images/real-estate/elite-properties/properties/elite-penthouse-interior.jpg,assets/images/real-estate/elite-properties/properties/elite-penthouse-living-room.jpg,assets/images/real-estate/elite-properties/properties/elite-master-bedroom.jpg,assets/images/real-estate/elite-properties/properties/elite-rooftop-terrace.jpg',
                'type' => 'Sale',
                'address' => '123 Skyview Dr, Lisbon',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'sqft' => 3500
            ],
            [
                'title' => 'Modern Urban Apartment',
                'description' => 'Chic and functional 2-bedroom apartment in the vibrant city center. Perfect for professionals, featuring open-plan living and smart home technology.',
                'price' => 3200,
                'image_url' => 'assets/images/real-estate/urban-living/hero/urban-apartment-hero.jpg',
                'gallery_images' => 'assets/images/real-estate/urban-living/interiors/urban-apartment-modern.jpg,assets/images/real-estate/urban-living/interiors/urban-balcony.jpg,assets/images/real-estate/urban-living/interiors/urban-living-open-plan-apartment.jpg,assets/images/real-estate/urban-living/amenities/urban-rooftop.jpg',
                'type' => 'Rent',
                'address' => '45 Metro St, Lisbon',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'sqft' => 1100
            ],
            [
                'title' => 'Eco-Friendly Green Home',
                'description' => 'Sustainable living at its finest. This 3-bedroom eco-house features solar panels, passive ventilation, and beautiful native gardens.',
                'price' => 650000,
                'image_url' => 'assets/images/real-estate/green-homes/hero/green-eco-house.jpg',
                'gallery_images' => 'assets/images/real-estate/green-homes/properties/green-home-interior.jpg,assets/images/real-estate/green-homes/properties/green-homes-cork-wall-interior.jpg,assets/images/real-estate/green-homes/properties/green-homes-solar-panels-roof.jpg,assets/images/real-estate/green-homes/hero/green-homes-interior-natural-light.jpg',
                'type' => 'Sale',
                'address' => '88 Nature Ln, Cascais',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'sqft' => 2200
            ],
            [
                'title' => 'Luxury Estate Mansion',
                'description' => 'An architectural masterpiece. This sprawling estate offers ultimate privacy, a gourmet kitchen, and a master suite that defines luxury.',
                'price' => 4500000,
                'image_url' => 'assets/images/real-estate/elite-properties/hero/elite-hero-luxury.jpg',
                'gallery_images' => 'assets/images/real-estate/elite-properties/properties/elite-gourmet-kitchen.jpg,assets/images/real-estate/elite-properties/properties/elite-master-bedroom-suite.jpg,assets/images/real-estate/elite-properties/properties/elite-penthouse-living.jpg,assets/images/real-estate/elite-properties/properties/elite-apartment-city.jpg',
                'type' => 'Sale',
                'address' => '1 Royal Ave, Sintra',
                'bedrooms' => 6,
                'bathrooms' => 5,
                'sqft' => 6000
            ]
        ];

        foreach ($properties as $property) {
            Property::create($property);
        }
    }
}
