<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\UserRepositoryInterfaceContract;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterfaceContract
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getByEmail(string $email): ?User
    {
        return $this->model->whereEmail($email)->first();
    }
}
