<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class BookingController extends Controller
{
	protected $valid_start_time = ['08', '17'];  // Starting time should be within 8:00am to 5:00pm

	public function index()
	{
		$bookings = Booking::orderBy('id', 'DESC')->paginate(Booking::LIMIT);
		return response()->json($bookings);
	}

	public function all()
	{
		$bookings = Booking::where(['user_id' => Auth::id()])->orderBy('id', 'DESC')->paginate(Booking::LIMIT);
		return response()->json($bookings);
	}

	public function get($id)
	{
		$booking = Booking::where(['id' => $id, 'user_id' => Auth::id()])->first()->toArray();
		return array_reverse($booking);
	}

	public function edit($id)
	{
		$booking = Booking::find($id);
		return response()->json($booking);
	}

	public function add(Request $request)
	{
		if ($this->_isAvailable($request)) {
			$booking = new Booking($request->all() + ['user_id' => Auth::id(), 'status' => Booking::STATUS_BOOKED]);
			$booking->save();
			return response()->json(['success' => TRUE]);
		} else {
			return response()->json(['success' => FALSE, 'message' => 'Selected date/time is not available.']);
		}
	}

	public function update($id, Request $request)
	{
		try
		{
			if (!$this->_isValid($request)) {
				throw new Exception('Selected Time is not available.');
			}
			$booking = Booking::find($id);
			$booking->from = $request->from;
			$booking->to = $request->to;
			if ($booking->isDirty() && $this->_isOccupied($request)) {  // When booking date has been changed
				throw new Exception('Selected Date is not available.');
			}
			$booking->update($request->all());
			$response = ['success' => TRUE];
		}
		catch(Exception $err)
		{
			$response = ['success' => FALSE, 'message' => $err->getMessage()];
		}
		return response()->json($response);
	}

	public function delete($id)
	{
		$booking = Booking::find($id);
		$booking->delete();
		return response()->json('Booking successfully deleted');
	}

	protected function _isAvailable(Request $request)
	{
		return !$this->_isOccupied($request) && $this->_isValid($request);
	}

	protected function _isOccupied(Request $request)
	{
		return count(Booking::whereBetween('from', [$request->from, $request->to])
			->orWhereBetween('to', [$request->from, $request->to])
			->get());
	}

	protected function _isValid(Request $request)
	{
		$date_start = Carbon::parse($request->from);
		$date_end = Carbon::parse($request->to);
		$reference_date = $date_start->format('Y-m-d');
		$start_time_1 = Carbon::createFromFormat('Y-m-d H', $reference_date.' '.$this->valid_start_time[0]);
		$start_time_2 = Carbon::createFromFormat('Y-m-d H', $reference_date.' '.$this->valid_start_time[1]);
		/**
		 * Checking if all the following have met:
		 * 1. Must be on the same day
		 * 2. Start time must be within 8am to 5pm
		 */
		$criteria = [
			$date_start->format('Y-m-d') == $date_end->format('Y-m-d'),
			$date_start->isBetween($start_time_1, $start_time_2)
		];
		return !in_array(FALSE, $criteria);
	}
}
