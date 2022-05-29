<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\BookingRepositoryInterfaceContract;
use App\Models\Booking;

class BookingRepository extends BaseRepository implements BookingRepositoryInterfaceContract
{
    public function __construct(Booking $model)
    {
        parent::__construct($model);
    }

    public function getAllWithPagination(): iterable
    {
        return $this
            ->model
            ->orderBy('id', 'DESC')
            ->paginate(Booking::LIMIT);
    }

    public function getAllByUserWithPagination(int $user_id): iterable
    {
        return $this
            ->model
            ->where(['user_id' => $user_id])
            ->orderBy('id', 'DESC')
            ->paginate(Booking::LIMIT);
    }

    public function getById(int $booking_id): Booking
    {
        return $this->model->findOrFail($booking_id);
    }
}
