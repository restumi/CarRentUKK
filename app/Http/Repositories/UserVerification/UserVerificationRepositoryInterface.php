<?php

namespace App\Http\Repositories\UserVerification;

interface UserVerificationRepositoryInterface
{
    public function all();

    public function find($id);

    public function status($status);

    public function store($data);

    public function update($id, array $data);

    public function delete($id);

    public function query();
}
