<?php

namespace App\Http\Repositories\Transaction;

interface TransactionRepositoryInterface
{
    public function query();

    public function all();

    public function countByStatus(string $status);

    public function totalRevenue();

    public function recents();

    public function find($id);

    public function findByUserId($id);

    public function store($data);

    public function update($id, array $data);

    public function delete($id);
}
