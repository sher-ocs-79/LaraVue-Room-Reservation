<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    const STATUS_BOOKED = 1;

	protected $fillable = ['room_id','user_id','from','to','status'];

	public function room()
	{
		return $this->belongsTo(Room::class);
	}
}
