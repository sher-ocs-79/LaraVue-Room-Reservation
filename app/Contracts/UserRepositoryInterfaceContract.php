<?php
declare(strict_types=1);

namespace App\Contracts;

use App\Models\User;

interface UserRepositoryInterfaceContract
{
    public function getByEmail(string $email): ?User;
}
