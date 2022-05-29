<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RoomRepositoryInterfaceContract;
use App\Models\Room;

class RoomRepository extends BaseRepository implements RoomRepositoryInterfaceContract
{
    public function __construct(Room $model)
    {
        parent::__construct($model);
    }

    public function getAll(): iterable
    {
        return $this->model->all();
    }
}
