<?php

namespace App\Http\Repositories\Car;

use App\Models\Car;

class CarRepository implements CarRepositoryInterface
{
    public function __construct(protected Car $model){}

    public function query()
    {
        return $this->model->query();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function getBrands()
    {
        return $this->model->distinct()->pluck('brand')->sort()->values();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $car = $this->model->find($id);
        $car->update($data);
        return $car;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
