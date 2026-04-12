<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\Admin\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

            return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil disetujui.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('admin.transactions.index')->with('error', 'Terjadi kesalahan saat menyetujui transaksi.');
        }
    }

    public function reject(Transaction $transaction)
    {
        try {
            $transaction->update(['status_transaction' => 'rejected']);
            $transaction->update(['payment_status' => 'cancelled']);

            return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil ditolak.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('admin.transactions.index')->with('error', 'Terjadi kesalahan saat menolak transaksi.');
        }
    }

    public function updatePaymentStatus(Transaction $transaction)
    {
        try {
            $transaction->update(['payment_status' => 'paid']);

            return redirect()->route('admin.transactions.index')->with('success', 'Status pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('admin.transactions.index')->with('error', 'Terjadi kesalahan saat memperbarui status pembayaran.');
        }
    }

    public function markAsCompleted(Transaction $transaction)
    {
        try {
            $transaction->update(['status_transaction' => 'completed']);
            if($transaction->payment_status !== 'paid') {
                $transaction->update(['payment_status' => 'paid']);
            }

            return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil diselesaikan.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('admin.transactions.index')->with('error', 'Terjadi kesalahan saat menyelesaikan transaksi.');
        }
    }

    public function cencelPayment(Transaction $transaction)
    {
        try {
            $transaction->update(['status_transaction' => 'cancelled']);
            $transaction->update(['payment_status' => 'cancelled']);

            return redirect()->route('admin.transactions.index')->with('success', 'Pembayaran berhasil dibatalkan.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('admin.transactions.index')->with('error', 'Terjadi kesalahan saat membatalkan pembayaran.');
        }
    }
}
