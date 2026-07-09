<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerLedger;
use App\Models\Supplier;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{


    public function customer_ledger_report()
    {
        $Customers = Customer::get();
        // dd($Customers);
        return view('admin_panel.report.cstomer_ledger_report', compact('Customers'));
    }

    public function fetchCustomerLedger(Request $request)
    {
        $customerId = $request->input('Customer_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Get ledger record
        $ledger = DB::table('customer_ledgers')
            ->where('customer_id', $customerId)
            ->select('id', 'opening_balance')
            ->first();

        $customerLedgerId = $customerId ?? null;
        $initialOpeningBalance = $ledger->opening_balance ?? 0;

        // Sales and Recoveries before start date
        $previousSales = DB::table('lot_sales')
            ->where('customer_id', $customerId)
            ->where('sale_date', '<', $startDate)
            ->sum('total');

        $previousRecoveries = DB::table('customer_recoveries')
            ->where('customer_ledger_id', $customerLedgerId)
            ->where('date', '<', $startDate)
            ->sum('amount_paid');
        // Calculate Remaining Before Start
        $remainingBeforeStart = $previousSales - $previousRecoveries;

        // Adjusted Opening Balance: Remaining before start + Opening Balance
        $openingBalance = $remainingBeforeStart + $initialOpeningBalance;

        // Recoveries (Credit) in selected range
        $recoveries = DB::table('customer_recoveries')
            ->where('customer_ledger_id', $customerLedgerId)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('id', 'amount_paid', 'date')
            ->get();

        // Sales (Debit) in selected range
        $sales = DB::table('lot_sales')
            ->where('customer_id', $customerId)
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->select('id', 'sale_date', 'total', 'quantity', 'weight', 'price')
            ->get();

        // Combine sales and recoveries
        $ledgerData = [];
        $balance = $openingBalance;

        foreach ($sales as $sale) {
            $balance -= $sale->total;
            $ledgerData[] = [
                'date' => $sale->sale_date,
                'debit' => $sale->total,
                'credit' => '',
                'quantity' => $sale->quantity,
                'weight' => $sale->weight,
                'price' => $sale->price,
                'total' => $balance
            ];
        }

        foreach ($recoveries as $recovery) {
            $balance += $recovery->amount_paid;
            $ledgerData[] = [
                'date' => $recovery->date,
                'debit' => '',
                'credit' => $recovery->amount_paid,
                'total' => $balance
            ];
        }

        // Sort by date
        usort($ledgerData, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        $totalDebit = $sales->sum('total');
        $totalCredit = $recoveries->sum('amount_paid');
        $closingBalance = $balance;

        return response()->json([
            'ledger_data' => $ledgerData,
            'opening_balance' => $openingBalance,
            'closing_balance' => $closingBalance,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function Vendor_ledger_report()
    {
        $Vendors = Supplier::get();
        // dd($Vendors);
        return view('admin_panel.report.vendor_ledger_report', compact('Vendors'));
    }

    public function fetch_Vendor_ledger_report(Request $request)
    {
        $VendorId = $request->input('Vendor_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // 1. Supplier name
        $supplierName = DB::table('suppliers')
            ->where('id', $VendorId)
            ->pluck('name')
            ->first();

        // 2. Balances
        $ledger = DB::table('supplier_ledgers')
            ->where('supplier_id', $VendorId)
            ->select('opening_balance', 'previous_balance', 'closing_balance')
            ->first();
            
        $initialOpeningBalance = $ledger->opening_balance ?? 0;
        
        // Bills and Payments before start date
        $previousBills = DB::table('vendor_bills')
            ->where('vendorId', $supplierName)
            ->whereDate('created_at', '<', $startDate)
            ->sum('net_pay');
            
        $previousPayments = DB::table('supplier_payments')
            ->where('supplier_id', $VendorId)
            ->whereDate('payment_date', '<', $startDate)
            ->sum('amount_paid');
            
        $adjustedOpeningBalance = $initialOpeningBalance + $previousBills - $previousPayments;

        // 3. Recoveries (Payments to Vendor)
        $recoveries = DB::table('supplier_payments')
            ->where('supplier_id', $VendorId)
            ->whereDate('payment_date', '>=', $startDate)
            ->whereDate('payment_date', '<=', $endDate)
            ->select('id', 'amount_paid', 'description', 'payment_date as date') // updated
            ->get();

        // 4. Local Sales (Vendor Bills)
        $lot_sales = DB::table('vendor_bills')
            ->where('vendorId', $supplierName)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->select(
                'id',
                'created_at as sale_date',
                'net_pay as total', // updated
                'lot_id',
                'trucknumber'
            )
            ->get();

        return response()->json([
            'opening_balance' => $adjustedOpeningBalance,
            'previous_balance' => $ledger->previous_balance ?? 0,
            'closing_balance' => $ledger->closing_balance ?? 0,
            'recoveries' => $recoveries,
            'local_sales' => $lot_sales,
            'supplier_name' => $supplierName,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function Marketcreditreport()
    {
        $userId = Auth::id();

        // Fetch customers with their latest ledger balances
        $startDate = request('start_date');
        $endDate = request('end_date');

        $customers = Customer::where('admin_or_user_id', $userId)->get();
        return view('admin_panel.report.Marketcreditreport', compact('customers'));
    }

    public function getCustomerLedgerSummary(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $customerId = $request->customer_id;

        $customers = [];

        // All Customers
        if ($customerId === 'All') {
            $customers = Customer::all();
        } else {
            $customer = Customer::find($customerId);
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Customer not found']);
            }
            $customers = collect([$customer]); // Make it iterable
        }

        $report = [];

        foreach ($customers as $customer) {
            $ledger = DB::table('customer_ledgers')
                ->where('customer_id', $customer->id)
                ->select('id', 'opening_balance')
                ->first();

            $ledgerId = $ledger->id ?? null;
            $initialOpeningBalance = $ledger->opening_balance ?? 0;

            $prevSales = DB::table('lot_sales')
                ->where('customer_id', $customer->id)
                ->where('sale_date', '<', $startDate)
                ->sum('total');

            $prevRecoveries = DB::table('customer_recoveries')
                ->where('customer_ledger_id', $ledgerId)
                ->where('date', '<', $startDate)
                ->sum('amount_paid');

            $openingBalance = $initialOpeningBalance + ($prevSales - $prevRecoveries);

            $sales = DB::table('lot_sales')
                ->where('customer_id', $customer->id)
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->sum('total');

            $recoveries = DB::table('customer_recoveries')
                ->where('customer_ledger_id', $ledgerId)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount_paid');

            $closingBalance = $openingBalance - $sales + $recoveries;

            $report[] = [
                'customer_name' => $customer->customer_name,
                'customer_name_urdu' => $customer->customer_name_urdu,
                'closing_balance' => round($closingBalance),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $report,
        ]);
    }
}
