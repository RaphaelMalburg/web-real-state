<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'image_url',
        'gallery_images',
        'type',
        'address',
        'bedrooms',
        'bathrooms',
        'sqft',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
