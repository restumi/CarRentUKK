<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Car;
use App\Http\Requests\Car\StoreCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use Exception;
// use Illuminate\Container\Attributes\Storage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Cache\Store;

class CarController extends Controller
{
    public function index()
    {
        try{
            $cars = Car::all();
            return response()->json([
                'message' => 'fetch success',
                'data' => $cars
            ]);
        } catch (Exception $e){
            $error = $e->getMessage();

            return response()->json([
                'message' => 'failed to fetch cars data',
                'message' => $error
            ], 500);
        }
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

            $car = Car::create($data);

            return response()->json([
                'message' => 'success create car',
                'data' => $car
            ]);

        } catch (Exception $e) {
            $error = $e->getMessage();

            return response()->json([
                'message' => 'failed to create car',
                'error' => $error
            ]);
        }
    }

    public function show(Car $car)
    {
        return response()->json($car);
    }

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
        try{
            $data = $request->validated();
            
            if( $request->hasFile('image') ){
                if( $car->image && Storage::disk('public')->exists($car->image) ){
                    Storage::disk('public')->delete($car->image);
                }

                $imagePath = $request->file('image')->store('cars', 'public');
                $data['image'] = $imagePath;
            }

            $car->fill($data);
            $car->save();

            return response()->json([
                'message' => 'updated car',
                'data' => $car->fresh()->toArray(),
                'updated' => $data
            ]);

        } catch (Exception $e) {
            $error = $e->getMessage();

            return response()->json([
                'message' => 'failed to update',
                'error' => $error
            ],500);
        }
    }

    public function destroy(Car $car)
    {
        try{
            if($car->image){
                Storage::disk('public')->delete($car->image);
            }

            $car->delete();

            return response()->json([
                'message' => 'car deleted'
            ]);
        } catch (Exception $e) {
            $error = $e->getMessage();

            return response()->json([
                'message' => 'failed to delete car',
                'error' => $error
            ]);
        }
    }
}
