<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Http\Requests\Car\StoreCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use Illuminate\Http\Request;
use App\Services\Admin\CarService;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    public function __construct(
        protected CarService $carService
    )
    {}

    public function index(Request $request)
    {
        $data = $this->carService->index($request);

        return view('admin.cars.index', [
            'cars' => $data['cars'],
            'brands' => $data['brands']
        ]);
    }

    public function create()
    {
        return view('admin.cars.create');
    }

    public function store(StoreCarRequest $request)
    {
        try{
            $data = $request->validated();

            $this->carService->store($data, $request);

            return redirect()->route('admin.cars.index')
                ->with('success', 'Mobil berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan mobil.');
        }
    }

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
        try {
            $data = $request->validated();

            $this->carService->update($request, $car, $data);

            return redirect()->route('admin.cars.index')
                ->with('success', 'Mobil berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui mobil.');
        }

    }

    public function destroy(Car $car)
    {
        try{
            $this->carService->destroy($car);
            return redirect()->route('admin.cars.index')
                ->with('success', 'Mobil berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus mobil.');
        }
    }
}
