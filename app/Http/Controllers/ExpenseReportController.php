<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\ExpenseCategory;

class ExpenseReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $selectedCategory = $request->input('category', 'All');

        $query = Expense::with('category')->whereBetween('expense_date', [$startDate, $endDate]);

        if ($selectedCategory !== 'All') {
            $query->where('expense_category_id', $selectedCategory);
        }

        $reportData = $query->orderBy('expense_date', 'asc')->get();
        
        $totalAmount = $reportData->sum('amount');

        $availableCategories = ExpenseCategory::all();

        return view('admin_panel.reports.expense_report', compact(
            'reportData', 'totalAmount', 'startDate', 'endDate', 'selectedCategory', 'availableCategories'
        ));
    }
}
