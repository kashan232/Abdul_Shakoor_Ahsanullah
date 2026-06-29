<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class HomeController extends Controller
{
    public function home()
    {
        if (Auth::id()) {
            $usertype = Auth()->user()->usertype;

            if ($usertype == 'staff') {

                // Fetch all categories for the dropdown
                $categories = Category::all();

                // Initially, load all products for display (optional, can be removed if you prefer to only load products on category change)
                $products = Product::all();
                $Customers = Customer::all();
                $Warehouses = Warehouse::get();


                return view('user_panel.user_dashboard', compact('categories', 'products', 'Customers','Warehouses'));
            } else if ($usertype == 'admin') {
                $today = now()->format('Y-m-d');

                // 1. Core KPIs (Summary parity with Report)
                $startDate = now()->startOfMonth()->format('Y-m-d');
                $previousMonthEnd = now()->startOfMonth()->subDay()->format('Y-m-d');
                $today = now()->format('Y-m-d');

                $allCustomers = DB::table('customers as c')->get();
                
                $totalCurrentBalance = 0;
                $totalPreviousMonthBalance = 0;
                $allClientData = []; // To store calculated balances for charts/watchlist

                foreach ($allCustomers as $c) {
                    $opening = (float)$c->opening_balance;
                    
                    // Sales and Recoveries up to Previous Month End
                    $prevSales = DB::table('lot_sales')->where('customer_id', $c->id)->where('sale_date', '<=', $previousMonthEnd)->sum('total');
                    $prevRecov = DB::table('customer_recoveries')->where('customer_ledger_id', $c->id)->where('date', '<=', $previousMonthEnd)->sum('amount_paid');
                    $balPrev = $opening + $prevSales - $prevRecov;

                    // Real-time Current Sales and Recoveries
                    $currSales = DB::table('lot_sales')->where('customer_id', $c->id)->sum('total');
                    $currRecov = DB::table('customer_recoveries')->where('customer_ledger_id', $c->id)->sum('amount_paid');
                    $balCurr = $opening + $currSales - $currRecov;

                    $totalCurrentBalance += $balCurr;
                    $totalPreviousMonthBalance += $balPrev;

                    $allClientData[] = [
                        'name' => $c->customer_name,
                        'bal_prev' => $balPrev,
                        'bal_curr' => $balCurr,
                        'change' => $balCurr - $balPrev
                    ];
                }

                // 2. Data for Charts
                // Top 10 Debtors
                $top10Debtors = collect($allClientData)->sortByDesc('bal_curr')->take(10)->values();

                // Performance Stats
                $increased = 0; $decreased = 0; $stable = 0;
                foreach ($allClientData as $item) {
                    if ($item['change'] > 1) $increased++;
                    elseif ($item['change'] < -1) $decreased++;
                    else $stable++;
                }
                $performanceStats = ['increased' => $increased, 'decreased' => $decreased, 'stable' => $stable];

                // Watchlist (Customers with increasing debt)
                $watchlistCustomers = collect($allClientData)->filter(fn($x) => $x['change'] > 1)->sortByDesc('change')->values();

                // 3. Other Existing KPIs
                $todaysSaleAmount = DB::table('lot_sales')->whereDate('created_at', $today)->sum('total');
                $todaysSaleUnit = DB::table('lot_sales')->whereDate('created_at', $today)->sum('quantity');
                $todaysRecovery = DB::table('customer_recoveries')->whereDate('date', $today)->sum('amount_paid');
                $totalAvailableUnits = DB::table('lot_entries')->sum('lot_quantity');
                $totalVendorBalance = DB::table('supplier_ledgers')->sum('closing_balance');
                $totalCustomers = $allCustomers->count();
                $totalVendors = DB::table('suppliers')->count();

                $monthLabels = [];
                $monthlyCashSales = [];
                $monthlyCreditSales = [];
                $monthlyRecoveries = [];
                $monthlyReceivables = [];
                
                $totalPeriodCashSales = 0;
                $totalPeriodCreditSales = 0;

                $totalOpeningBalance = 0;
                foreach($allCustomers as $c) {
                    $totalOpeningBalance += (float)$c->opening_balance;
                }

                $currentYear = now()->year;
                for ($i = 1; $i <= 12; $i++) {
                    $monthToken = \Carbon\Carbon::create($currentYear, $i, 1);
                    $monthTokenDate = $monthToken->endOfMonth()->format('Y-m-d');
                    $monthKey = $monthToken->format('Y-m');
                    $monthLabels[] = $monthToken->format('M');

                    $cashAmt = DB::table('lot_sales')->where('customer_type', 'cash')->where('sale_date', 'like', $monthKey . '%')->sum('total');
                    $monthlyCashSales[] = (float)$cashAmt;
                    $totalPeriodCashSales += (float)$cashAmt;

                    $creditAmt = DB::table('lot_sales')->where('customer_type', 'credit')->where('sale_date', 'like', $monthKey . '%')->sum('total');
                    $monthlyCreditSales[] = (float)$creditAmt;
                    $totalPeriodCreditSales += (float)$creditAmt;

                    $rAmt = DB::table('customer_recoveries')->where('date', 'like', $monthKey . '%')->sum('amount_paid');
                    $monthlyRecoveries[] = (float)$rAmt;

                    // FIX: Cumulative Receivables = Opening + Cumulative CREDIT Sales - Cumulative Recoveries
                    $cumCreditSales = DB::table('lot_sales')->where('customer_type', 'credit')->where('sale_date', '<=', $monthTokenDate)->sum('total');
                    $cumRecoveries = DB::table('customer_recoveries')->where('date', '<=', $monthTokenDate)->sum('amount_paid');
                    $monthlyReceivables[] = (float)($totalOpeningBalance + $cumCreditSales - $cumRecoveries);
                }

                return view('admin_panel.admin_dashboard', compact(
                    'totalCurrentBalance',
                    'totalPreviousMonthBalance',
                    'todaysSaleAmount',
                    'todaysSaleUnit',
                    'todaysRecovery',
                    'totalAvailableUnits',
                    'totalVendorBalance',
                    'totalCustomers',
                    'totalVendors',
                    'monthLabels',
                    'monthlyCashSales',
                    'monthlyCreditSales',
                    'monthlyRecoveries',
                    'monthlyReceivables',
                    'top10Debtors',
                    'performanceStats',
                    'watchlistCustomers',
                    'totalPeriodCashSales',
                    'totalPeriodCreditSales'
                ));
            }
        } else {
            return Redirect()->route('login');
        }
    }

    public function adminpage()
    {
        return view('admin_panel.admin_page');
    }

    public function dashboard()
    {
        return view('admin_panel.dashboard');
    }

    public function Admin_Change_Password()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            return view('admin_panel.change_password', []);
        } else {
            return redirect()->back();
        }
    }

    public function updte_change_Password(Request $request)
    {
        if (Auth::id()) {
            // dd($request);
            // Validate the form data
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|min:8',
                'retype_new_password' => 'required|same:new_password'
            ]);

            // Get the current authenticated user
            $user = Auth::user();
            // dd($user);
            // Check if the old password matches
            if (!Hash::check($request->input('old_password'), $user->password)) {
                return redirect()->back()->withErrors(['old_password' => 'Old password is incorrect']);
            }

            // Check if the user is an admin
            if ($user->usertype !== 'admin') {
                return redirect()->back()->withErrors(['error' => 'Unauthorized action']);
            }

            // Update the password
            $user->password = Hash::make($request->input('new_password'));
            $user->save();

            // Add a success message to the session
            Session::flash('success', 'Password changed successfully');

            // Redirect back with success message
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    // Staff work 

    public function getProductsByCategory(Request $request)
    {
        $categoryname = $request->categoryname;
        // dd($categoryname);
        // Fetch products based on the selected category
        $products = Product::where('category', $categoryname)->get();
        // dd($products);
        // Return JSON response
        return response()->json($products);
    }

    public function getProductByBarcode(Request $request)
    {
        $barcode = $request->query('barcode'); // Get barcode from query parameters
        $product = Product::where('barcode_number', $barcode)->first();

        if ($product) {
            return response()->json($product);
        } else {
            return response()->json(null, 404);
        }
    }
}
