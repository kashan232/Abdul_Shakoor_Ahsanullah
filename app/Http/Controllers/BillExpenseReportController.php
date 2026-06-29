<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorBill;

class BillExpenseReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $selectedCategory = $request->input('category', 'All');

        $bills = VendorBill::whereBetween('bill_date', [$startDate, $endDate])->get();

        $reportData = [];
        $totalAmount = 0;

        foreach ($bills as $bill) {
            $categories = json_decode($bill->category, true) ?? [];
            $amounts = json_decode($bill->final_amount, true) ?? [];

            foreach ($categories as $index => $cat) {
                if ($selectedCategory === 'All' || $selectedCategory === $cat) {
                    $amount = (float)($amounts[$index] ?? 0);
                    if ($amount > 0) {
                        $reportData[] = [
                            'bill_date' => $bill->bill_date,
                            'trucknumber' => $bill->trucknumber,
                            'category' => $cat,
                            'amount' => $amount,
                            'bill_id' => $bill->id,
                        ];
                        $totalAmount += $amount;
                    }
                }
            }
        }

        $availableCategories = ['Mazdori', 'Commission', 'Rent', 'Market Tax'];

        $customCategoryMap = [
            'Mazdori' => 'مزدوری',
            'Commission' => 'کمیشن',
            'Rent' => 'کرایہ',
            'Market Tax' => 'مارکیٹ ٹیکس',
        ];

        return view('admin_panel.reports.bill_expense_report', compact(
            'reportData', 'totalAmount', 'startDate', 'endDate', 'selectedCategory', 'availableCategories', 'customCategoryMap'
        ));
    }
}
