<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Room;

class RoomController extends Controller
{
	public function index()
	{
		$rooms = Room::all()->toArray();
		return array_reverse($rooms);
	}
}
