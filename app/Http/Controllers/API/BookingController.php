<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Contracts\BookingRepositoryInterfaceContract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class BookingController extends Controller
{
	protected $valid_start_time = ['08', '17'];  // Starting time should be within 8:00am to 5:00pm

    /**
     * @var BookingRepositoryInterfaceContract
     */
    protected $bookingRepository;

    public function __construct(BookingRepositoryInterfaceContract $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function index()
	{
		return response()->json($this->bookingRepository->getAllWithPagination());
	}

	public function all()
	{
		return response()->json($this->bookingRepository->getAllByUserWithPagination(Auth::id()));
	}

	public function get($id)
	{
        $booking = $this->bookingRepository->getById((int)$id)->toArray();

        return $booking['user_id'] == Auth::id() ? array_reverse($booking) : null;
	}

	public function edit($id)
	{
		return response()->json($this->bookingRepository->getById((int)$id));
	}

	public function add(Request $request)
	{
		if ($this->_isAvailable($request)) {
            $this->bookingRepository->create([
                'user_id' => Auth::id(),
                'status' => Booking::STATUS_BOOKED,
            ] + $request->all());
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
            $booking = $this->bookingRepository->getById((int)$id);
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
        $this->bookingRepository->getById((int)$id)->delete();
		return response()->json('Booking successfully deleted');
	}

	protected function _isAvailable(Request $request)
	{
		return !$this->_isOccupied($request) && $this->_isValid($request);
	}

	protected function _isOccupied(Request $request)
	{
		return count($this->bookingRepository->getByDateRange($request->from, $request->to));
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
