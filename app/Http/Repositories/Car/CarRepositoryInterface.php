<?php

namespace App\Http\Repositories\Car;

interface CarRepositoryInterface
{
    public function query();

    public function all();

    public function getBrands();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}
