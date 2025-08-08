<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Driver;
use App\Models\User;
use App\Models\Transaction;
use App\Classes\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use Exception;
use PhpParser\Node\Stmt\TryCatch;
use Psy\Readline\Transient;

use function Laravel\Prompts\error;

class TransactionController extends Controller
{
    public function store(StoreTransactionRequest $request)
    {
        return ApiResponse::withTransaction( function() use($request) {
            $data = $request->validated();

            $car = Car::findOrFail($data['car_id']);
            $driver = array_key_exists('driver_id', $data) ? Driver::findOrFail($data['driver_id']) : null;

            $start = strtotime($data['start_date']);
            $end = strtotime($data['end_date']);
            $days = max(1, ceil(($end - $start) / 86400));

            $carCost = $car->price_per_day * $days;
            $driverCost = $driver ? 250000 * $days : 0;

            $total = $carCost + $driverCost;

            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'car_id' => $car->id,
                'driver_id' => $driver->id ?? null,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'total_price' => $total,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'status' => 'requested'
            ]);

            return $transaction->fresh(['car', 'driver']);
        });
    }

    public function index()
    {
        $transaction = Transaction::with(['car', 'driver'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return ApiResponse::sendResponse('History transaction', $transaction);
    }
}
