<?php

namespace App\Http\Repositories\Driver;

interface DriverRepositoryInterface
{
    public function query();

    public function all();

    public function find($id);

    public function create($data);

    public function update($id, array $data);

    public function delete($id);
}
