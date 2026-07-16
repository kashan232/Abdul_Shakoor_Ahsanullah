<style>
    /* Premium Sidebar Colors Only (Layout preserved) */
    .sidebar { 
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important; 
    }
    
    /* User Provided SlimScroll and Active State CSS */
    .sidebar .slimScrollDiv .slimScrollBar {
        background-color: #4ade80 !important;
        width: 5px !important;
        opacity: 1 !important;
    }

    .sidebar__menu .sidebar-menu-item .side-menu--open, 
    .sidebar__menu .sidebar-menu-item.active>a {
        background-color: #4ade80 !important;
    }

    /* Only overriding text and background colors */
    .sidebar .nav-link, .sidebar .menu-title, .sidebar-submenu .nav-link { 
        color: #94a3b8 !important; 
    }
    .sidebar .menu-icon { 
        color: #64748b !important; 
    }
    
    .sidebar .sidebar-menu-item > a:hover { 
        background-color: rgba(34, 197, 94, 0.1) !important; 
    }
    
    /* Target ALL active/open states that might have yellow */
    .sidebar .sidebar-menu-item.active > a,
    .sidebar .sidebar-menu-item.active > a:hover,
    .sidebar .sidebar-menu-item.open > a,
    .sidebar .sidebar-menu-item.show > a,
    .sidebar .sidebar-dropdown > a[aria-expanded="true"],
    .sidebar .sidebar-dropdown.active > a {
        border-color: #4ade80 !important;
    }
    
    .sidebar .sidebar-menu-item.active > a::before,
    .sidebar .sidebar-dropdown > a[aria-expanded="true"]::before,
    .sidebar .sidebar-menu-item.active > a::after {
        background-color: #4ade80 !important;
        border-color: #4ade80 !important;
    }

    /* Keep text dark when active/open since background is now solid #4ade80 */
    .sidebar .sidebar-menu-item.active > a .menu-title, 
    .sidebar .sidebar-menu-item.active > a .menu-icon, 
    .sidebar .sidebar-dropdown > a[aria-expanded="true"] .menu-title,
    .sidebar .sidebar-dropdown > a[aria-expanded="true"] .menu-icon { 
        color: #0f172a !important; 
    }
    
    .sidebar .sidebar-menu-item > a:hover .menu-title, 
    .sidebar .sidebar-menu-item > a:hover .menu-icon { 
        color: #4ade80 !important; 
    }

    /* Submenu Active State (Darker Green instead of Yellow) */
    .sidebar .sidebar-submenu .sidebar-menu-item.active > a {
        background-color: #16a34a !important;
    }
    .sidebar .sidebar-submenu .sidebar-menu-item.active > a .menu-title,
    .sidebar .sidebar-submenu .sidebar-menu-item.active > a .menu-icon {
        color: #ffffff !important;
    }
</style>
<div class="sidebar">
    <button class="res-sidebar-close-btn" style="color: #fff;"><i class="la la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="#" class="sidebar__main-logo" style="text-decoration: none;">
                <div style="font-size: 24px; font-weight: 800; color: #f8fafc; padding: 15px 0; text-align: center; text-transform: uppercase; letter-spacing: 2px;">
                    AU & <span style="color: #4ade80;">Brothers</span>
                </div>
            </a>
        </div>
        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            @if(Auth::check() && Auth::user()->usertype == 'admin')
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item ">
                    <a href="{{ route('home') }}" class="nav-link ">
                        <i class="menu-icon la la-home"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a  href="javascript:void(0)">
                        <i class="menu-icon fas fa-truck"></i>
                        <span class="menu-title">Products</span>
                    </a>
                    <div class="sidebar-submenu ">
                        <ul>
                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('category') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Products </span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('brand') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Verity</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('unit') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Units</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('In-unit') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Units In</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>


                <li class="sidebar-menu-item sidebar-dropdown">
                    <a  href="javascript:void(0)">
                        <i class="menu-icon fas fa-user-friends"></i>
                        <span class="menu-title">Customer</span>
                    </a>
                    <div class="sidebar-submenu ">
                        <ul>
                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('customer') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Customers</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('Customer-balance') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Customers Balance</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('customer-ledger') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Cutomers Ledger</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('customer-recovery') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Cutomers Recoveries</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('customer-sale') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"> Customer sale </span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>  

                
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a  href="javascript:void(0)">
                        <i class="menu-icon fas fa-user-check"></i>
                        <span class="menu-title">Vendor</span>
                    </a>
                    <div class="sidebar-submenu ">
                        <ul>
                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('supplier') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Vendor</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('Supplier-balance') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Vendor Balance</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('supplier-ledger') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Vendor Ledger</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('supplier-payment') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Vendor Payments</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>  

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a  href="javascript:void(0)">
                        <i class="menu-icon fas fa-truck"></i>
                        <span class="menu-title">Truck Entry</span>
                    </a>
                    <div class="sidebar-submenu ">
                        <ul>
                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('Truck-Entry') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Add Truck Entry </span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('Truck-Entries') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Trucks Enter</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a  href="javascript:void(0)">
                        <i class="menu-icon fas fa-shopping-basket"></i>
                        <span class="menu-title"> Sale</span>
                    </a>
                    <div class="sidebar-submenu ">
                        <ul>
                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('show-trucks') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"> Available Truck </span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('trucks-sold') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"> Sold Truck </span>
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('cash-sale') }}" class="nav-link">
                        <i class="menu-icon fas fa-receipt"></i>
                        <span class="menu-title">Cash Sale</span>
                    </a><i class=""></i>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('daily-sale') }}" class="nav-link">
                        <i class="menu-icon fas fa-calendar-alt"></i>
                        <span class="menu-title">Daily Sale</span>
                    </a><i class=""></i>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('daily-sale-truck-wise') }}" class="nav-link">
                        <i class="menu-icon fas fa-calendar-alt"></i>
                        <span class="menu-title">Daily Sale Truck </span>
                    </a><i class=""></i>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('daily-recovery') }}" class="nav-link">
                        <i class="menu-icon fas fa-calendar-alt"></i>
                        <span class="menu-title">Daily Recoveries</span>
                    </a><i class=""></i>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)">
                        <i class="menu-icon las la-credit-card"></i>
                        <span class="menu-title"> Payments </span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li class="sidebar-menu-item">
                                <a href="{{ route('customer-payments') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Customer Payments</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a href="{{ route('Vendor-payments') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Vendor Payments</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a  href="javascript:void(0)">
                        <i class="menu-icon fas fa-money-bill-wave"></i>
                        <span class="menu-title"> Expense</span>
                    </a>
                    <div class="sidebar-submenu ">
                        <ul>
                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('expense-categories') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"> Expense Categories </span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('expenses') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"> Expense Records </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item ">
                    <a href="{{ route('cash-book') }}">
                        <i class="menu-icon las la-book"></i>
                        <span class="menu-title"> Cash Book </span>
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a  href="javascript:void(0)">
                        <i class="menu-icon fas fa-shopping-basket"></i>
                        <span class="menu-title"> Reports</span>
                    </a>
                    <div class="sidebar-submenu ">
                        <ul>
                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('customer-ledger-report') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"> Customer Ledger Report </span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('Vendor-ledger-report') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"> Vendor Ledger Report</span>
                                </a>
                            </li>


<li class="sidebar-menu-item ">
    <a href="{{ route('customers.current.balance.report') }}">
        <i class="menu-icon las la-dot-circle"></i>
        <span class="menu-title"> Customer Current Receivable Report </span>
    </a>
</li>

<li class="sidebar-menu-item ">
    <a href="{{ route('reports.bill_expenses') }}">
        <i class="menu-icon las la-dot-circle"></i>
        <span class="menu-title"> Bill Expenses Report </span>
    </a>
</li>

<li class="sidebar-menu-item ">
    <a href="{{ route('reports.expenses') }}">
        <i class="menu-icon las la-dot-circle"></i>
        <span class="menu-title"> Office Expenses Report </span>
    </a>
</li>

<li class="sidebar-menu-item ">
    <a href="{{ route('sale.recovery.report') }}">
        <i class="menu-icon las la-dot-circle"></i>
        <span class="menu-title"> Sale & Recovery Report </span>
    </a>
</li>



                        </ul>
                    </div>
                </li>

               <!-- <li class="sidebar-menu-item sidebar-dropdown">
                    <a class="" href="javascript:void(0)">
                        <i class="menu-icon las la-users"></i>
                        <span class="menu-title">Manage Staff</span>
                    </a>
                    <div class="sidebar-submenu ">
                        <ul>
                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('staff') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">All Staff</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item ">
                                <a class="nav-link" href="{{ route('StaffSalary') }}">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">Staff Salary</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>  -->

                <!-- <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="">
                        <i class="menu-icon lab la-product-hunt"></i>
                        <span class="menu-title">Manage Product</span>
                    </a>
                    <div class="sidebar-submenu  ">
                        <ul>
                            <li class="sidebar-menu-item  ">
                                <a href="{{ route('category') }}" class="nav-link">
                                    <i class="menu-icon la la-dot-circle"></i>
                                    <span class="menu-title">Categories</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item  ">
                                <a href="{{ route('subcategory') }}" class="nav-link">
                                    <i class="menu-icon la la-dot-circle"></i>
                                    <span class="menu-title">Sub Categories</span>
                                </a>
                            </li>
                            {{-- <li class="sidebar-menu-item  ">
                                <a href="{{ route('brand') }}" class="nav-link">
                                    <i class="menu-icon la la-dot-circle"></i>
                                    <span class="menu-title">Brands</span>
                                </a>
                            </li> --}}
                            <li class="sidebar-menu-item  ">
                                <a href="{{ route('unit') }}" class="nav-link">
                                    <i class="menu-icon la la-dot-circle"></i>
                                    <span class="menu-title">Units</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item  ">
                                <a href="{{ route('all-product') }}"
                                    class="nav-link">
                                    <i class="menu-icon la la-dot-circle"></i>
                                    <span class="menu-title">Products</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> -->

                <!-- <li class="sidebar-menu-item ">
                    <a href="{{ route('all-order') }}" class="nav-link ">
                        <i class="menu-icon la la-warehouse"></i>
                        <span class="menu-title">Order</span>
                    </a>
                </li> -->
                <!-- <li class="sidebar-menu-item ">
                    <a href="{{ route('product-alerts') }}" class="nav-link ">
                        <i class="menu-icon las la-bell"></i>
                        <span class="menu-title">Stock Alerts</span>
                        {{-- @php
                        $lowStockProductsCount = DB::table('products')
                        ->whereRaw('CAST(stock AS UNSIGNED) <= CAST(alert_quantity AS UNSIGNED)')
                            ->count();
                            @endphp


                            @if($lowStockProductsCount > 0)
                            <small>&nbsp;<i class="fa fa-circle text--danger" aria-hidden="true" aria-label="Returned" data-bs-original-title="Returned"></i></small>
                            @endif --}}
                    </a>
                </li> -->

                




                 <!-- <li class="sidebar-menu-item">
                    <a href="{{ route('supplier') }}" class="nav-link">
                        <i class="menu-icon la la-user-friends"></i>
                        <span class="menu-title">Supplier</span>
                    </a>
                </li> 
                 <li class="sidebar-menu-item">
                    <a href="{{ route('supplier') }}" class="nav-link">
                        <i class="menu-icon la la-user-friends"></i>
                        <span class="menu-title">Vendor</span>
                    </a>
                </li>  -->
                <!-- <li class="sidebar-menu-item">
                    <a href="{{ route('vendor') }}" class="nav-link">
                        <i class="menu-icon la la-user-friends"></i>
                        <span class="menu-title">Vendor</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('vendor') }}" class="nav-link">
                        <i class="menu-icon la la-user-friends"></i>
                        <span class="menu-title">Give Order to Vendor</span>
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="">
                        <i class="menu-icon la la-shopping-bag"></i>
                        <span class="menu-title">Purchase</span>
                    </a>
                    <div class="sidebar-submenu  ">
                        <ul>
                            <li class="sidebar-menu-item  ">
                                <a href="{{ route('Purchase') }}"
                                    class="nav-link">
                                    <i class="menu-icon la la-dot-circle"></i>
                                    <span class="menu-title">All Purchases</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item  ">
                                <a href="{{ route('all-purchase-return') }}"
                                    class="nav-link">
                                    <i class="menu-icon la la-dot-circle"></i>
                                    <span class="menu-title">Purchases Return</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item  ">
                    <a href="{{ route('all-purchase-return-damage-item') }}"
                        class="nav-link">
                        <i class="menu-icon la la-dot-circle"></i>
                        <span class="menu-title">Claim Returns</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('customer') }}" class="nav-link">
                        <i class="menu-icon la la-users"></i>
                        <span class="menu-title">Customer</span>
                    </a>
                </li>

                
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="">
                        <i class="menu-icon la la-shopping-cart"></i>
                        <span class="menu-title">Sale</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li class="sidebar-menu-item">
                                <a href="{{ route('all-sales') }}" class="nav-link">
                                    <i class="menu-icon la la-dot-circle"></i>
                                    <span class="menu-title">All Sales</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item  ">
                                <a href="#"
                                    class="nav-link">
                                    <i class="menu-icon la la-dot-circle"></i>
                                    <span class="menu-title">Sales Return</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>  -->
                <li class="sidebar-menu-item">
                    <a href="{{ route('backup.index') }}" class="nav-link">
                        <i class="menu-icon fas fa-database text--success"></i>
                        <span class="menu-title">System Backup</span>
                    </a>
                </li>
            </ul>
            @endif

            <div class="text-center mb-3 text-uppercase" style="margin-top: 20px;">
                <span class="text--warning">AU &</span>
                <span class="text--primary">Brothers</span>
            </div>
        </div>
    </div>
</div>