<?php

namespace App\Http\Repositories\Driver;

use App\Models\Driver;

class DriverRepository implements DriverRepositoryInterface
{
    public function __construct(protected Driver $model){}

    public function query()
    {
        return $this->model->query();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $driver = $this->model->find($id);
        $driver->update($data);
        return $driver;
    }

    public function delete($id)
    {
        $driver = $this->model->find($id);
        $driver->delete();
        return $driver;
    }
}
