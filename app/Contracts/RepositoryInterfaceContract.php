<?php
declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories
 */
interface RepositoryInterfaceContract
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes);

    /**
     * @param $id
     * @return Model
     */
    public function find($id);
}
