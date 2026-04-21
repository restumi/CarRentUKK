<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function handleMidtransNotifications(Request $request)
    {
        $notification = $request->all();

        $orderId = $notification['order_id'] ?? null;
        $statusCode = $notification['status_code'] ?? null;
        $grossAmount = $notification['gross_amount'] ?? null;
        $signatureKey = $notification['signature_key'] ?? null;
        $transactionStatus = $notification['transaction_status'] ?? null;
        $fraudStatus = $notification['fraud_status'] ?? 'accept';

        $serverKey = config('midtrans.server_key');

        Log::info('Received Midtrans notification', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'signature_key_received' => $signatureKey,
        ]);

        $computedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $computedSignature) {
            Log::error('Invalid Midtrans signature', [
                'expected' => $signatureKey,
                'computed' => $computedSignature,
                'raw_string' => $orderId . $statusCode . $grossAmount . $serverKey
            ]);
            return response('invalid signature', 403);
        }

        Log::info('Valid Midtrans notification received, processing payment update');

        $isTestNotification = (str_contains($orderId, 'payment_notif_test') || str_contains($orderId, 'G967054589'));

        if ($isTestNotification) {
            Log::info('🧪 Test notification detected - skipping database lookup', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus
            ]);

            return response('OK (test mode)', 200);
        }

        $transaction = Transaction::where('order_id', $orderId)->first();
        if (!$transaction) {
            return response('transaction not found', 404);
        }

        if ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
            $transaction->update(['payment_status' => 'paid']);
            $transaction->update(['status_transaction' => 'accepted']);
        }

        elseif ($transactionStatus === 'settlement') {
            $transaction->update(['payment_status' => 'paid']);
            $transaction->update(['status_transaction' => 'accepted']);
        }

        elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $transaction->update(['payment_status' => 'cancelled']);
        }

        elseif ($transactionStatus === 'pending') {
            $transaction->update(['payment_status' => 'pending']);
        }

        Log::info('Payment status updated', [
            'transaction_id' => $transaction->id,
            'payment_status' => $transaction->payment_status,
        ]);
        return response('OK', 200);
    }
}
