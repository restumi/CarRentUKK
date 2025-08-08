<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Http\Requests\Car\StoreCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('plate_number', 'like', "%{$search}%");
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

        // Get unique brands for filter dropdown
        $brands = Car::distinct()->pluck('brand')->sort()->values();

        return view('admin.cars.index', compact('cars', 'brands'));
    }

    public function create()
    {
        return view('admin.cars.create');
    }

    public function store(StoreCarRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cars', 'public');
        }

        Car::create($data);

        return redirect()->route('admin.cars.index')
            ->with('success', 'Mobil berhasil ditambahkan.');
    }

    public function show(Car $car)
    {
        return view('admin.cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }
            $data['image'] = $request->file('image')->store('cars', 'public');
        }

        $car->update($data);

        return redirect()->route('admin.cars.index')
            ->with('success', 'Mobil berhasil diperbarui.');
    }

    public function destroy(Car $car)
    {
        if ($car->image) {
            Storage::disk('public')->delete($car->image);
        }

        $car->delete();

        return redirect()->route('admin.cars.index')
            ->with('success', 'Mobil berhasil dihapus.');
    }
}
