<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingController extends Controller
{
	public function index()
	{
		$books = Booking::all()->toArray();
		return array_reverse($books);
	}

	public function add(Request $request)
	{
		$book = new Booking($request->all() + ['status' => Booking::STATUS_BOOKED]);
		$book->save();
		return response()->json('Booking successfully added');
	}

	public function edit($id)
	{
		$book = Booking::find($id);
		return response()->json($book);
	}

	public function update($id, Request $request)
	{
		$book = Booking::find($id);
		$book->update($request->all());
		return response()->json('Booking successfully updated');
	}

	public function delete($id)
	{
		$book = Booking::find($id);
		$book->delete();
		return response()->json('Booking successfully deleted');
	}
}
