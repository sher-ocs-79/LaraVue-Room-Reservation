<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterfaceContract;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements RepositoryInterfaceContract
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id)
    {
        return $this->model->find($id);
    }
}
