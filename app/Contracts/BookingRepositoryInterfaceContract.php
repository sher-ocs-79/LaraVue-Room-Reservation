<?php
declare(strict_types=1);

namespace App\Contracts;

use App\Models\Booking;

interface BookingRepositoryInterfaceContract
{
    public function getAllWithPagination(): iterable;

    public function getAllByUserWithPagination(int $user_id): iterable;

    public function getById(int $booking_id): Booking;
}
