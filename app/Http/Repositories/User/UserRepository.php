<?php

namespace App\Http\Repositories\User;

use App\Models\User;
use App\Http\Repositories\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(protected User $model){}

    public function all()
    {
        return $this->model->with('verification')->get();
    }

    public function withVerificationQuery()
    {
        return $this->model->with('verification');
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function checkExists($email)
    {
        return $this->model->where('email', $email)->exists();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function query()
    {
        return $this->model->query();
    }
}
