<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

	protected $fillable = ['room_id','user_id','from','to'];

	public function room()
	{
		return $this->belongsTo(Room::class);
	}
}
