<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['price', 'description', 'photo'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
