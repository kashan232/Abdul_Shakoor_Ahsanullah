<style>
    /* Custom Theme Overrides for Admin Panel */
    .navbar-wrapper { 
        background-color: #ffffff !important; 
        box-shadow: 0 2px 15px rgba(0,0,0,0.05) !important; 
        border-bottom: 2px solid #22c55e; 
    }
    .navbar__right .nav-link i, .las.la-bars, .las.la-search, .navbar-search-field::placeholder, .navbar-search-field { 
        color: #333 !important; 
    }
    .navbar-user__name {
        color: #ffffff !important;
    }
    .navbar-search-field {
        background-color: #f4f4f4 !important;
        border: 1px solid #ddd !important;
    }
    .navbar-user {
        border: 1px solid #eee;
        border-radius: 50px;
        padding: 5px 10px;
        background: #141d30;
        color: #ffffff !important;
    }
</style>
<nav class="navbar-wrapper">
    <div class="navbar__left">
        <button type="button" class="res-sidebar-open-btn me-3"><i class="las la-bars"></i></button>
        <form class="navbar-search">
            <input type="search" name="#0" class="navbar-search-field" id="searchInput" autocomplete="off"
                placeholder="Search here...">
            <i class="las la-search"></i>
            <ul class="search-list"></ul>
        </form>
    </div>
    <div class="navbar__right">
        <ul class="navbar__action-list">
            @if(Auth::check() && Auth::user()->usertype == 'admin')
            <li class="nav-item">
                {{-- @php
                $lowStockProductsCount = DB::table('products')
                ->whereRaw('CAST(stock AS UNSIGNED) <= CAST(alert_quantity AS UNSIGNED)')
                    ->count();
                    @endphp --}}

                    <a class="nav-link" href="#">
                        <!-- Notifications -->
                        <i class="las la-bell" style="font-size: 25px; color:#22c55e !important;"></i>
                        <!-- <i class="menu-icon las la-bell"></i> -->
                        <span class="badge badge-danger" style="padding: .5em .15em!important;">
                            {{-- {{ $lowStockProductsCount }} --}}
                        </span>
                    </a>
            </li>
            @endif
            <li class="dropdown">
                <button type="button" class="" data-bs-toggle="dropdown" data-display="static"
                    aria-haspopup="true" aria-expanded="false" style="background: none; border: none;">
                    <span class="navbar-user">
                        <span class="navbar-user__thumb">
                            <img src="{{ asset('assets/admin/images/user.png') }}" alt="image"></span>
                        <span class="navbar-user__info">
                            @if(Auth::check() && Auth::user()->usertype == 'admin')
                            <span class="navbar-user__name">Super Admin</span>
                            @elseif(Auth::check() && Auth::user()->usertype == 'staff')
                            <span class="navbar-user__name">User</span>
                            @endif
                        </span>
                        <span class="icon"><i class="las la-chevron-circle-down" style="color: #ffffff;"></i></span>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu--sm p-0 border-0 box--shadow1 dropdown-menu-right">
                    @if(Auth::check() && Auth::user()->usertype == 'admin')
                    <a href="{{ route('Admin-Change-Password') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                        <i class="dropdown-menu__icon las la-key"></i>
                        <span class="dropdown-menu__caption">Password</span>
                    </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();"
                            class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                            <i class="dropdown-menu__icon las la-sign-out-alt"></i>
                            <span class="dropdown-menu__caption">Logout</span>
                        </a>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>