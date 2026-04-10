<?php

namespace App\Http\Repositories\User;

interface UserRepositoryInterface
{
    public function all();

    public function withVerificationQuery();

    public function find($id);

    public function findByEmail($email);

    public function checkExists($email);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function query();
}
