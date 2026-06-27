<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LotSale;
use App\Models\Sale;
use App\Models\CustomerRecovery;
use App\Models\SupplierPayments;
use App\Models\Expense;

class CashBookController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        // Calculate Opening Balance (Everything before start_date)
        $openingLotSales = LotSale::where('sale_date', '<', $startDate)->sum('total');
        $openingSales = Sale::where('sale_date', '<', $startDate)->sum('total_price');
        $openingRecoveries = CustomerRecovery::where('date', '<', $startDate)->sum('amount_paid');
        $totalOpeningReceipts = $openingLotSales + $openingSales + $openingRecoveries;

        $openingSupplierPayments = SupplierPayments::where('payment_date', '<', $startDate)->sum('amount_paid');
        $openingExpenses = Expense::where('expense_date', '<', $startDate)->sum('amount');
        $totalOpeningPayments = $openingSupplierPayments + $openingExpenses;

        $openingBalance = $totalOpeningReceipts - $totalOpeningPayments;

        // Fetch Current Period Receipts
        $lotSales = LotSale::whereBetween('sale_date', [$startDate, $endDate])->get()->map(function($item) {
            return [
                'type' => 'Lot Sale',
                'description' => 'Sale to ' . ($item->customer->customer_name ?? 'Walk-in'),
                'amount' => $item->total,
                'date' => $item->sale_date
            ];
        });

        $sales = Sale::whereBetween('sale_date', [$startDate, $endDate])->get()->map(function($item) {
            return [
                'type' => 'Sale',
                'description' => 'Invoice #' . $item->invoice_no,
                'amount' => $item->total_price,
                'date' => $item->sale_date
            ];
        });

        $recoveries = CustomerRecovery::whereBetween('date', [$startDate, $endDate])->get()->map(function($item) {
            return [
                'type' => 'Customer Recovery',
                'description' => $item->description ?? 'Payment Received',
                'amount' => $item->amount_paid,
                'date' => $item->date
            ];
        });

        $receipts = collect()->merge($lotSales)->merge($sales)->merge($recoveries)->sortBy('date')->values();
        $totalReceipts = $receipts->sum('amount');

        // Fetch Current Period Payments
        $supplierPayments = SupplierPayments::whereBetween('payment_date', [$startDate, $endDate])->get()->map(function($item) {
            return [
                'type' => 'Vendor Payment',
                'description' => 'Payment to Vendor',
                'amount' => $item->amount_paid,
                'date' => $item->payment_date
            ];
        });

        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->get()->map(function($item) {
            return [
                'type' => 'Expense',
                'description' => $item->title ?? 'General Expense',
                'amount' => $item->amount,
                'date' => $item->expense_date
            ];
        });

        $payments = collect()->merge($supplierPayments)->merge($expenses)->sortBy('date')->values();
        $totalPayments = $payments->sum('amount');

        $closingBalance = $openingBalance + $totalReceipts - $totalPayments;

        return view('admin_panel.cash_book.index', compact(
            'startDate', 'endDate', 'openingBalance', 'receipts', 'payments',
            'totalReceipts', 'totalPayments', 'closingBalance'
        ));
    }
}
