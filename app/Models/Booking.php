<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'user_name',
        'user_email',
        'booking_date',
        'status',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
