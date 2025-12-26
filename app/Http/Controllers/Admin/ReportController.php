<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

final class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        // Default to current month
        $month = $request->query('month', now()->format('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $month);

        $query = Transaction::query()
            ->where('status', 'success') // Assuming 'success' is the status for paid tx
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month);

        // Daily aggregation
        $dailyRevenue = Transaction::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->where('status', 'success')
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalRevenue = $dailyRevenue->sum('total');
        $totalTransactions = $dailyRevenue->sum('count');

        return view('admin.reports.revenue', [
            'month' => $month,
            'date' => $date, // for display
            'dailyRevenue' => $dailyRevenue,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
        ]);
    }
}
