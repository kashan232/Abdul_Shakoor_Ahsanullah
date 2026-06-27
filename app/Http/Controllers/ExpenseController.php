<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        if (Auth::id()) {
            $expenses = Expense::with('category')->get();
            $categories = ExpenseCategory::all();
            return view('admin_panel.expense.index', compact('expenses', 'categories'));
        } else {
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        if (Auth::id()) {
            Expense::create([
                'expense_category_id' => $request->expense_category_id,
                'title' => $request->title,
                'description' => $request->description,
                'amount' => $request->amount,
                'expense_date' => $request->expense_date,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            return redirect()->back()->with('success', 'Expense Added Successfully');
        } else {
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        if (Auth::id()) {
            $update_id = $request->input('expense_id');
            Expense::where('id', $update_id)->update([
                'expense_category_id' => $request->expense_category_id,
                'title' => $request->title,
                'description' => $request->description,
                'amount' => $request->amount,
                'expense_date' => $request->expense_date,
                'updated_at' => Carbon::now(),
            ]);
            return redirect()->back()->with('success', 'Expense Updated Successfully');
        } else {
            return redirect()->back();
        }
    }
}
