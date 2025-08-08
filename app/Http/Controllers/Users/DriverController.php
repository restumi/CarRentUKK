<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
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

        return ApiResponse::sendResponse('fetch data success', $drivers);
    }

    public function show(Driver $driver)
    {
        return ApiResponse::sendResponse('fetch data success', $driver);
    }
}
