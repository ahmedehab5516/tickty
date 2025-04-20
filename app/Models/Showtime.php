<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{

  protected $fillable = [
        'movie_id',
        'hall_id',
        'start_time',
        'end_time',
        'language',
        'is_3d',
        
    ];




    public function movie()
        {
            return $this->belongsTo(Movie::class);
        }

    public function hall()
        {
            return $this->belongsTo(Hall::class);
        }

    public function bookings()
        {
            return $this->hasMany(Booking::class);
        }


    public function cinema()
        {
            return $this->belongsTo(Cinema::class);
        }

}
