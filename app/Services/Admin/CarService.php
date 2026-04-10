<?php

namespace App\Services\Admin;

use App\Http\Repositories\Car\CarRepositoryInterface;
use App\Http\Requests\Car\StoreCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarService
{
    public function __construct(
        protected CarRepositoryInterface $carRepository
    ){}

    public function index(Request $request)
    {
        $query = $this->carRepository->query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('seat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('price_range')) {
            $priceRange = $request->price_range;
            if ($priceRange === '0-500000') {
                $query->where('price_per_day', '<=', 500000);
            } elseif ($priceRange === '500000-1000000') {
                $query->whereBetween('price_per_day', [500000, 1000000]);
            } elseif ($priceRange === '1000000+') {
                $query->where('price_per_day', '>=', 1000000);
            }
        }

        $cars = $query->latest()->paginate(12);

        $brands = $this->carRepository->getBrands();

        return [
            'cars' => $cars,
            'brands' => $brands
        ];
    }

    public function store(array $data, StoreCarRequest $request)
    {
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cars', 'public');
        }

        $this->carRepository->create($data);
    }

    public function update(UpdateCarRequest $request, Car $car, array $data)
    {
        if ($request->hasFile('image')) {
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }
            $data['image'] = $request->file('image')->store('cars', 'public');
        }

        $this->carRepository->update($car->id, $data);
    }

    public function destroy(Car $car)
    {
        if ($car->image) {
            Storage::disk('public')->delete($car->image);
        }
        $this->carRepository->delete($car->id);
    }
}
