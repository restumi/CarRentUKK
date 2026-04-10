<?php

namespace App\Services\Admin;

use App\Http\Repositories\Transaction\TransactionRepositoryInterface;
use Illuminate\Http\Request;

class TransactionService
{
    public function __construct(
        protected TransactionRepositoryInterface $transaction
    ){}

    public function index(Request $request)
    {
        $query = $this->transaction->query();

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
        $pendingCount = $this->transaction->countByStatus('requested');
        $approvedCount = $this->transaction->countByStatus('accepted');
        $rejectedCount = $this->transaction->countByStatus('rejected');
        $totalRevenue = $this->transaction->totalRevenue();
        $todayCount = $this->transaction->query()->whereDate('created_at', today())->count();

        return [
            'transactions' => $transactions,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'totalRevenue' => $totalRevenue,
            'todayCount' => $todayCount,
        ];
    }
}
