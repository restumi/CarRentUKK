<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Http\Requests\Driver\StoreDriverRequest;
use App\Http\Requests\Driver\UpdateDriverRequest;
use Exception;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::all();

        // return response()->json([
        //     'message' => 'success fetch',
        //     'data' => $drivers
        // ]);

        return ApiResponse::sendResponse('fetch data success', $drivers);
    }

    public function store(StoreDriverRequest $request)
    {
        try{
            $data = $request->validated();

            if($request->hasFile('photo')){
                $imgPath = $request->file('photo')->store('drivers', 'public');
                $data['photo'] = $imgPath;
            }

            $driver = Driver::create($data);

            // return response()->json([
            //     'message' => $data['name'] . 'added',
            //     'data' => $driver
            // ]);

            return ApiResponse::sendResponse('success create driver', $driver);

        } catch(Exception $e){
            $error = $e->getMessage();

            // return response()->json([
            //     'message' => 'failed added drivers',
            //     'error' => $error
            // ]);

            return ApiResponse::sendErrorResponse('failed to create driver', $error);
        }
    }

    public function show(Driver $driver)
    {
        return response()->json($driver);
    }

    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        try{
            $data = $request->validated();

            if($request->hasFile('photo')){
                if($driver->photo && Storage::disk('public')->exists($driver->photo)){
                    Storage::disk()->delete($driver->photo);
                }

                $data['photo'] = $request->file('photo')->store('drivers', 'public');
            }

            $driver->fill($data);
            $driver->save();

            // return response()->json([
            //     'message' => 'driver update',
            //     'data' => $driver
            // ]);

            return ApiResponse::sendResponse('driver updated', $driver);

        } catch(Exception $e){
            $error = $e->getMessage();

            // return response()->json([
            //     'message' => 'failed to update driver',
            //     'error' => $error
            // ]);

            return ApiResponse::sendErrorResponse('failed to update driver', $error);
        }
    }

    public function destroy(Driver $driver)
    {
        try{

            if( $driver->photo && Storage::disk('public')->exists($driver->photo)){
                Storage::disk('public')->delete($driver->photo);
            }

            $driver->delete();

            // return response()->json([
            //     'message' => 'driver deleted',
            // ]);

            return ApiResponse::sendResponse('', 'driver deleted');

        } catch (Exception $e) {
            $error = $e->getMessage();

            // return response()->json([
            //     'message' => 'failed delete',
            //     'error' => $error
            // ]);

            return ApiResponse::sendErrorResponse('failed to delete driver', $error);
        }
    }
}
