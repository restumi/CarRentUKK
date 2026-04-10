<?php

namespace App\Http\Repositories\Transaction;

use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function __construct(private Transaction $model)
    {}

    public function query()
    {
        return $this->model->query();
    }

    public function all()
    {
        return $this->model->with(['user', 'car', 'driver'])->get();
    }

    public function countByStatus(string $status)
    {
        return $this->model->where('status_transaction', $status)->count();
    }

    public function totalRevenue()
    {
        return $this->model->where('status_transaction', 'completed')->sum('total_price');
    }

    public function recents()
    {
        return $this->model->with(['user', 'car', 'driver'])->latest()->take(10)->get();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findByUserId($id)
    {
        return $this->model->where('user_id', $id)->get();
    }

    public function store($data)
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
}
