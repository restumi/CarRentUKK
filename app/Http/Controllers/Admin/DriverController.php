<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Http\Requests\Driver\StoreDriverRequest;
use App\Http\Requests\Driver\UpdateDriverRequest;
use App\Services\Admin\DriverService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DriverController extends Controller
{
    public function __construct(
        protected DriverService $driverService
    ){}

    public function index(Request $request)
    {
        $drivers = $this->driverService->index($request);

        return view('admin.drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('admin.drivers.create');
    }

    public function store(StoreDriverRequest $request)
    {
        try {
            $data = $request->validated();

            $this->driverService->store($request, $data);

            return redirect()->route('admin.drivers.index')
                ->with('success', 'Driver berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan driver.');
        }
    }

    public function edit(Driver $driver)
    {
        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        try {
            $data = $request->validated();

            $this->driverService->update($request, $driver, $data);

            return redirect()->route('admin.drivers.index')
                ->with('success', 'Driver berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui driver.');
        }
    }

    public function destroy(Driver $driver)
    {
        try {
            $this->driverService->destroy($driver);

            return redirect()->route('admin.drivers.index')
                ->with('success', 'Driver berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus driver.');
        }
    }
}
