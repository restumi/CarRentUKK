<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'car', 'driver']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('car', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            })->orWhere('id', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status_transaction', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('time_filter')) {
            $timeFilter = $request->time_filter;
            switch ($timeFilter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', today()->subDay());
                    break;
                case 'week':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
            }
        }

        $transactions = $query->latest()->paginate(10);

        // Statistics
        $pendingCount = Transaction::where('status_transaction', 'requested')->count();
        $approvedCount = Transaction::where('status_transaction', 'accepted')->count();
        $rejectedCount = Transaction::where('status_transaction', 'rejected')->count();
        $totalRevenue = Transaction::where('status_transaction', 'completed')
            ->sum('total_price');
        $todayCount = Transaction::whereDate('created_at', today())->count();

        return view('admin.transactions.index', compact(
            'transactions',
            'pendingCount',
            'approvedCount', 
            'rejectedCount',
            'totalRevenue',
            'todayCount'
        ));
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