<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        if (Auth::id()) {
            $categories = ExpenseCategory::all();
            return view('admin_panel.expense_category.index', compact('categories'));
        } else {
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        if (Auth::id()) {
            ExpenseCategory::create([
                'name' => $request->name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            return redirect()->back()->with('success', 'Expense Category Added Successfully');
        } else {
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        if (Auth::id()) {
            $update_id = $request->input('category_id');
            ExpenseCategory::where('id', $update_id)->update([
                'name' => $request->name,
                'updated_at' => Carbon::now(),
            ]);
            return redirect()->back()->with('success', 'Expense Category Updated Successfully');
        } else {
            return redirect()->back();
        }
    }
}
