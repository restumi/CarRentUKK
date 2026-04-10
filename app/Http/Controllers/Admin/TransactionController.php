<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\Admin\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transaction
    ){}

    public function index(Request $request)
    {
        $data = $this->transaction->index($request);

        return view('admin.transactions.index', [
            'transactions' => $data['transactions'],
            'pendingCount' => $data['pendingCount'],
            'approvedCount' => $data['approvedCount'],
            'rejectedCount' => $data['rejectedCount'],
            'totalRevenue' => $data['totalRevenue'],
            'todayCount' => $data['todayCount']
        ]);
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'car', 'driver']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function approve(Transaction $transaction)
    {
        try {
            $transaction->update(['status_transaction' => 'accepted']);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disetujui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui transaksi.'
            ], 500);
        }
    }

    public function reject(Transaction $transaction)
    {
        try {
            $transaction->update(['status_transaction' => 'rejected']);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil ditolak.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menolak transaksi.'
            ], 500);
        }
    }

    public function updatePaymentStatus(Transaction $transaction)
    {
        try {
            $transaction->update(['payment_status' => 'paid']);

            return response()->json([
                'success' => true,
                'message' => 'Status pembayaran berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status pembayaran.'
            ], 500);
        }
    }

    public function markAsCompleted(Transaction $transaction)
    {
        try {
            $transaction->update(['status_transaction' => 'completed']);
            if($transaction->payment_status !== 'paid') {
                $transaction->update(['payment_status' => 'paid']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diselesaikan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyelesaikan transaksi.'
            ], 500);
        }
    }

    public function cencelPayment(Transaction $transaction)
    {
        try {
            $transaction->update(['payment_status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dibatalkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan pembayaran.'
            ], 500);
        }
    }
}
