<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Driver;
use App\Models\Transaction;
use App\Classes\ApiResponse;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use Midtrans\Snap;

class TransactionController extends Controller
{
    public function store(StoreTransactionRequest $request)
    {
        return ApiResponse::withTransaction(function () use ($request) {
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

    public function createPayment(Request $request, Transaction $transaction)
    {
        return ApiResponse::withTransaction(function () use ($transaction) {

            if ($transaction->user_id !== auth()->id()) {
                abort(403);
            }

            if ($transaction->payment_status !== 'pending') {
                return ApiResponse::sendErrorResponse('transaction already paid', '', 400);
            }

            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

            if($transaction->order_id){
                $order_id = $transaction->order_id;
            } else {
                $order_id = 'TRX-' . $transaction->id . '-' . time();
                $transaction->update(['order_id' => $order_id]);
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $order_id,
                    'gross_amount' => $transaction->total_price
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email
                ],
                'item_details' => [
                    [
                        'id' => 'Car-' . $transaction->car_id,
                        'price' => $transaction->total_price,
                        'quantity' => 1,
                        'name' => 'Sewa Mobil ' . $transaction->car->brand . ' ' . $transaction->car->name,
                    ]
                ]
            ];

            $snapToken = Snap::getSnapToken($params);

            return [
                'snap_token' => $snapToken,
                'order_id' => $order_id
            ];
        });
    }

    public function handleMidtransNotifications(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->getContent() . $serverKey);

        if ($hashed !== $request->server('HTTP_X_MIDTRANS_SIGNATURE_KEY')) {
            return response('invalid signature', 403);
        }

        $orderId = $request->order_id;
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status ?? 'accept';

        $transaction = Transaction::where('order_id', $orderId)->first();
        if (!$transaction) return response('transaction not found', 404);

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $transaction->update(['payment_status' => 'paid']);
            }
        } elseif ($transactionStatus == 'settlement') {
            $transaction->update(['payment_status' => 'paid']);
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'expire') {
            $transaction->update(['payment_status' => 'cancelled']);
        }

        return response('OK', 200);
    }
}
