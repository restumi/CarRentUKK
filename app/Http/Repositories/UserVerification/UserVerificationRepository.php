<?php

namespace App\Http\Repositories\UserVerification;

use App\Models\UserVerification;

class UserVerificationRepository implements UserVerificationRepositoryInterface
{
    public function __construct(protected UserVerification $userVerification)
    {}

    public function all()
    {
        return $this->userVerification->all();
    }

    public function find($id)
    {
        return $this->userVerification->findOrFail($id);
    }

    public function status($status)
    {
        return $this->userVerification->where('status', $status)->get();
    }

    public function store($data)
    {
        return $this->userVerification->create($data);
    }

    public function update($id, array $data)
    {
        return $this->userVerification->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->userVerification->where('id', $id)->delete();
    }

    public function query()
    {
        return $this->userVerification->query();
    }
}
