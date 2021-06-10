<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
	public function index()
	{
		$books = Booking::all()->toArray();
		return array_reverse($books);
	}

	public function get()
	{
		$books = Booking::where(['user_id' => Auth::id()])->get()->toArray();
		return array_reverse($books);
	}

	public function add(Request $request)
	{
		if ($this->_isAvailable($request)) {
			$book = new Booking($request->all() + ['user_id' => Auth::id(), 'status' => Booking::STATUS_BOOKED]);
			$book->save();
			return response()->json(['added' => TRUE]);
		} else {
			return response()->json(['added' => FALSE, 'message' => 'Selected date/time is not available.']);
		}
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

	protected function _isAvailable(Request $request)
	{
		return !$this->_isOccupied($request) && $this->_isTheSameDay($request);
	}

	protected function _isOccupied(Request $request)
	{
		return count(Booking::whereBetween('from', [$request->from, $request->to])
			->orWhereBetween('to', [$request->from, $request->to])
			->get());
	}

	protected function _isTheSameDay(Request $request)
	{
		$date_from = Carbon::parse($request->from)->format('Y-m-d');
		$date_to = Carbon::parse($request->to)->format('Y-m-d');
		return $date_from == $date_to;
	}
}
