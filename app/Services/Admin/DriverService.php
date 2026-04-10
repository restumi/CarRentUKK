<?php

namespace App\Services\Admin;

use App\Http\Repositories\Driver\DriverRepositoryInterface;
use App\Http\Requests\Driver\StoreDriverRequest;
use App\Http\Requests\Driver\UpdateDriverRequest;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverService
{
    public function __construct(
        protected DriverRepositoryInterface $driverRepository
    ) {}

    public function index(Request $request)
    {
        $query = $this->driverRepository->query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $drivers = $query->latest()->paginate(10);

        return $drivers;
    }

    public function store(StoreDriverRequest $request, array $data)
    {
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('drivers', 'public');
        }

        $this->driverRepository->create($data);
    }

    public function update(UpdateDriverRequest $request, Driver $driver, array $data)
    {
        if ($request->hasFile('photo')) {
            if ($driver->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($driver->photo);
            }
            $data['photo'] = $request->file('photo')->store('drivers', 'public');
        }

        $this->driverRepository->update($driver->id, $data);
    }

    public function destroy(Driver $driver)
    {
        $this->driverRepository->delete($driver->id);
    }
}
