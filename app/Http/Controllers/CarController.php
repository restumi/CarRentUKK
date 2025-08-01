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
use App\Classes\ApiResponse;

class CarController extends Controller
{
    public function index()
    {
        try{
            $cars = Car::all();

            return ApiResponse::sendResponse('data obtained', $cars);

        } catch (Exception $e){
            $error = $e->getMessage();

            return ApiResponse::sendErrorResponse('failed to get data', $error);
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

            return ApiResponse::sendResponse('car created', $car);

        } catch (Exception $e) {
            $error = $e->getMessage();

            return ApiResponse::sendErrorResponse('failed to create car', $error);
        }
    }

    public function show(Car $car)
    {
        return ApiResponse::sendResponse('', $car);
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

            return ApiResponse::sendResponse('Car updated', $car);

        } catch (Exception $e) {
            $error = $e->getMessage();

            return ApiResponse::sendErrorResponse('failed to update car', $error);
        }
    }

    public function destroy(Car $car)
    {
        try{
            if($car->image){
                Storage::disk('public')->delete($car->image);
            }

            $car->delete();

            return ApiResponse::sendResponse('Car deleted', 'Success deleted car');

        } catch (Exception $e) {
            $error = $e->getMessage();

            return ApiResponse::sendErrorResponse('failed to delete car', $error);
        }
    }
}
