<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerReportController extends Controller
{
   public function currentBalanceReport(Request $request)
{
    $toDate = $request->input('to_date') ?? now()->format('Y-m-d');

    $data = DB::table('customers as c')
        ->select(
            'c.id',
            'c.customer_name',
            DB::raw('CAST(c.opening_balance AS DECIMAL) as opening_balance'),

            DB::raw("(
                SELECT SUM(CAST(total AS DECIMAL))
                FROM lot_sales
                WHERE customer_id = c.id AND sale_date <= '$toDate'
            ) as total_sales"),

            DB::raw("(
                SELECT SUM(CAST(amount_paid AS DECIMAL))
                FROM customer_recoveries
                WHERE customer_ledger_id = c.id AND date <= '$toDate'
            ) as total_recoveries")
        )
->orderBy('c.customer_name', 'asc') 
        
->get();

    $totalSales = 0;
    $totalRecoveries = 0;
    $totalClosingBalance = 0;

    foreach ($data as $customer) {
        $customer->total_sales = $customer->total_sales ?? 0;
        $customer->total_recoveries = $customer->total_recoveries ?? 0;
        $customer->closing_balance = 
            $customer->opening_balance + $customer->total_sales - $customer->total_recoveries;

        $totalSales += $customer->total_sales;
        $totalRecoveries += $customer->total_recoveries;
        $totalClosingBalance += $customer->closing_balance;
    }

    if ($request->has('download') && $request->download == 'pdf') {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.customers_current_balance', compact(
            'data', 'toDate', 'totalSales', 'totalRecoveries', 'totalClosingBalance'
        ));
        return $pdf->download('customers_current_balance.pdf');
    }

    return view('reports.customers_current_balance', compact(
        'data', 'toDate', 'totalSales', 'totalRecoveries', 'totalClosingBalance'
    ));
}



 public function customerReceivableReport(Request $request)
    {
        $toDate = $request->input('to_date') ?? now()->format('Y-m-d');
        $currentDate = Carbon::parse($toDate)->startOfMonth();
        $previousDate = (clone $currentDate)->subMonth();

        $customers = DB::table('customers as c')
            ->select(
                'c.id',
                'c.customer_name',
                DB::raw('CAST(c.opening_balance AS DECIMAL) as opening_balance'),

                DB::raw("(
                    SELECT SUM(CAST(total AS DECIMAL))
                    FROM lot_sales
                    WHERE customer_id = c.id AND sale_date <= '$currentDate'
                ) as current_sales"),

                DB::raw("(
                    SELECT SUM(CAST(amount_paid AS DECIMAL))
                    FROM customer_recoveries
                    WHERE customer_ledger_id = c.id AND date <= '$currentDate'
                ) as current_recoveries"),

                DB::raw("(
                    SELECT SUM(CAST(total AS DECIMAL))
                    FROM lot_sales
                    WHERE customer_id = c.id AND sale_date <= '$previousDate'
                ) as previous_sales"),

                DB::raw("(
                    SELECT SUM(CAST(amount_paid AS DECIMAL))
                    FROM customer_recoveries
                    WHERE customer_ledger_id = c.id AND date <= '$previousDate'
                ) as previous_recoveries")
            )
->orderBy('c.customer_name', 'asc')
            ->get();

        $report = [];
        $totalCurrent = 0;
        $totalPrevious = 0;

        foreach ($customers as $c) {
            $opening = $c->opening_balance ?? 0;
            $salesCurrent = $c->current_sales ?? 0;
            $recoveriesCurrent = $c->current_recoveries ?? 0;
            $salesPrevious = $c->previous_sales ?? 0;
            $recoveriesPrevious = $c->previous_recoveries ?? 0;

            $currentBalance = $opening + $salesCurrent - $recoveriesCurrent;
            $previousBalance = $opening + $salesPrevious - $recoveriesPrevious;
            $change = $currentBalance - $previousBalance;

            $report[] = [
                'name' => $c->customer_name,
                'opening' => $opening,
                'balance_previous' => $previousBalance,
                'balance_current' => $currentBalance,
                'change' => $change,
            ];

            $totalCurrent += $currentBalance;
            $totalPrevious += $previousBalance;
        }

        if ($request->has('download') && $request->download == 'pdf') {
            $pdf = Pdf::loadView('reports.customer_receivables', compact(
                'report', 'totalCurrent', 'totalPrevious', 'currentDate', 'previousDate'
            ));
            return $pdf->download('customer_receivables.pdf');
        }

        return view('reports.customer_receivables', compact(
            'report', 'totalCurrent', 'totalPrevious', 'currentDate', 'previousDate'
        ));
    }
}