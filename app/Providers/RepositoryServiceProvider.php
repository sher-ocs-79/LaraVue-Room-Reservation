<?php
declare(strict_types=1);

namespace App\Providers;

use App\Contracts\BookingRepositoryInterfaceContract;
use App\Contracts\RepositoryInterfaceContract;
use App\Contracts\RoomRepositoryInterfaceContract;
use App\Contracts\UserRepositoryInterfaceContract;
use App\Repositories\BaseRepository;
use App\Repositories\BookingRepository;
use App\Repositories\RoomRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(RepositoryInterfaceContract::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterfaceContract::class, UserRepository::class);
        $this->app->bind(BookingRepositoryInterfaceContract::class, BookingRepository::class);
        $this->app->bind(RoomRepositoryInterfaceContract::class, RoomRepository::class);
    }
}
