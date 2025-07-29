<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Car;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
// use Illuminate\Container\Attributes\Storage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Cache\Store;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::all();
        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        return view('admin.cars.create');
    }

    public function store(StoreCarRequest $request)
    {
        try{
            $data = $request->validated();

            if($request->hasFile('image')){
                $imagePath = $request->file('image')->store('cars', 'public');
                $data['image'] = $imagePath;
            }

            Car::create($data);

            return redirect()->route('cars.index')->with('success', 'mobil berhasil ditambahkan');

        } catch (\Throwable $e) {
            $response = Log::error($e->getMessage());

            return back()->withInput()->with('error', $response);
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
        try{
            $data = $request->validated();

            if( $car->hasFile('image') ){
                if( $car->image && Storage::disk('public')->exists($car->image) ){
                    Storage::disk('public')->delete($car->image);
                }

                $imagePath = $request->file('image')->store('cars', 'public');
                $data['image'] = $imagePath;
            }

            $car->update($data);

            return redirect()->route('cars.index')->with('success', 'mobil di update');

        } catch (\Throwable $e) {
            $response = Log::error($e->getMessage());

            return back()->withInput()->with('error', 'gagal update mobil : '. $car->id .$response);
        }
    }

    public function destroy(Car $car)
    {
        try{
            $car->delete();
            return redirect()->route('cars.index')->with('success', 'mobil berhasil di tambahkan');
        } catch (\Throwable $e) {
            $response = Log::error($e->getMessage());

            return back()->withInput()->with('error', 'gagal menghapus mobil : ' . $car->id . $response);
        }
    }
}
