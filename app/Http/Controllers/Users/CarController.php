<?php

namespace App\Http\Controllers\Users;

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

    public function show(Car $car)
    {
        return ApiResponse::sendResponse('', $car);
    }

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }
}
