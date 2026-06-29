@include('admin_panel.include.header_include')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<body>
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        <!-- sidebar end -->

        <!-- navbar-wrapper start -->
        @include('admin_panel.include.navbar_include')
        <!-- navbar-wrapper end -->

        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                <!-- Dashboard Header -->
                <div class="d-flex mb-4 flex-wrap gap-3 justify-content-between align-items-center bg-white p-4 rounded-4 shadow-sm border-0" style="background: linear-gradient(to right, #ffffff, #f8fafc);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; background: linear-gradient(135deg, #3b82f6, #4f46e5); color: white;">
                            <i class="las la-chart-line fs-2"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.5px;">Business Overview</h4>
                            <p class="text-muted mb-0 small">Welcome back! Here's what's happening today.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark border border-secondary px-3 py-2 rounded-pill shadow-sm">
                            <i class="las la-calendar"></i> {{ now()->format('d M, Y') }}
                        </span>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row gy-4 mb-4">

                    <!-- Card 1: Current Total Receivable -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden hover-lift transition-all" style="background: linear-gradient(145deg, #ffffff, #eff6ff);">
                            <div class="position-absolute top-0 end-0 p-3 opacity-25">
                                <i class="las la-wallet" style="font-size: 5rem; color: #3b82f6; transform: rotate(-15deg);"></i>
                            </div>
                            <div class="card-body p-4 position-relative z-1 d-flex flex-column justify-content-between">
                                <div>
                                    <p class="text-muted fw-bold mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Current Receivable</p>
                                    <h3 class="fw-bolder text-dark mb-0" style="font-size: 1.8rem;">{{ number_format($totalCurrentBalance, 0) }} <span class="fs-6 text-muted">PKR</span></h3>
                                </div>
                                <div class="mt-3 d-flex justify-content-between align-items-end">
                                    <div>
                                        @php 
                                            $diff = $totalCurrentBalance - $totalPreviousMonthBalance;
                                            $percent = $totalPreviousMonthBalance > 0 ? ($diff / $totalPreviousMonthBalance) * 100 : 0;
                                        @endphp
                                        <span class="badge rounded-pill {{ $diff > 0 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} border-0 px-2 py-1" style="font-size: 0.8rem;">
                                            <i class="las la-arrow-{{ $diff > 0 ? 'up' : 'down' }}"></i> {{ number_format($percent, 1) }}%
                                        </span>
                                        <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">vs prev month</small>
                                    </div>
                                    <a href="{{ route('customer.receivable.report') }}" class="btn btn-sm btn-primary rounded-circle shadow-sm" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"><i class="las la-arrow-right fs-5"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Previous Month Closing -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden hover-lift transition-all bg-white">
                            <div class="position-absolute top-0 end-0 p-3 opacity-10">
                                <i class="las la-history" style="font-size: 5rem; color: #64748b; transform: rotate(15deg);"></i>
                            </div>
                            <div class="card-body p-4 position-relative z-1 d-flex flex-column justify-content-between">
                                <div>
                                    <p class="text-muted fw-bold mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Prev Month Closing</p>
                                    <h3 class="fw-bolder text-dark mb-0" style="font-size: 1.8rem;">{{ number_format($totalPreviousMonthBalance, 0) }} <span class="fs-6 text-muted">PKR</span></h3>
                                </div>
                                <div class="mt-3 d-flex justify-content-between align-items-end">
                                    <div>
                                        <span class="badge rounded-pill bg-light text-secondary border border-secondary px-2 py-1" style="font-size: 0.8rem;">
                                            1st {{ now()->format('M') }}
                                        </span>
                                    </div>
                                    <a href="{{ route('customer.receivable.report') }}?to_date={{ now()->startOfMonth()->subDay()->format('Y-m-d') }}" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"><i class="las la-arrow-right fs-5"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Daily Sales Unit (Qty) -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden hover-lift transition-all" style="background: linear-gradient(145deg, #ffffff, #f0fdf4);">
                            <div class="position-absolute top-0 end-0 p-3 opacity-25">
                                <i class="las la-box" style="font-size: 5rem; color: #10b981; transform: rotate(-15deg);"></i>
                            </div>
                            <div class="card-body p-4 position-relative z-1 d-flex flex-column justify-content-between">
                                <div>
                                    <p class="text-muted fw-bold mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Daily Sales Units</p>
                                    <h3 class="fw-bolder text-dark mb-0" style="font-size: 1.8rem;">{{ number_format($todaysSaleUnit, 0) }} <span class="fs-6 text-muted">Units</span></h3>
                                </div>
                                <div class="mt-3 d-flex justify-content-between align-items-end">
                                    <small class="text-success fw-bold"><i class="las la-certificate"></i> Today's Activity</small>
                                    <a href="{{ route('daily-sale') }}" class="btn btn-sm btn-success rounded-circle shadow-sm" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; background-color: #10b981; border: none;"><i class="las la-arrow-right fs-5"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Today's Recovery -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden hover-lift transition-all" style="background: linear-gradient(145deg, #ffffff, #fefce8);">
                            <div class="position-absolute top-0 end-0 p-3 opacity-25">
                                <i class="las la-money-bill-wave" style="font-size: 5rem; color: #eab308; transform: rotate(15deg);"></i>
                            </div>
                            <div class="card-body p-4 position-relative z-1 d-flex flex-column justify-content-between">
                                <div>
                                    <p class="text-muted fw-bold mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Today's Recovery</p>
                                    <h3 class="fw-bolder text-dark mb-0" style="font-size: 1.8rem;">{{ number_format($todaysRecovery, 0) }} <span class="fs-6 text-muted">PKR</span></h3>
                                </div>
                                <div class="mt-3 d-flex justify-content-between align-items-end">
                                    <small class="text-warning fw-bold"><i class="las la-coins"></i> Cash Inflow</small>
                                    <a href="{{ route('daily-recovery') }}" class="btn btn-sm btn-warning rounded-circle shadow-sm text-white" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; background-color: #eab308; border: none;"><i class="las la-arrow-right fs-5"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5: Total Available Units -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden hover-lift transition-all bg-white">
                            <div class="card-body p-4 d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #f1f5f9; color: #475569;">
                                    <i class="las la-boxes fs-2"></i>
                                </div>
                                <div>
                                    <p class="text-muted fw-bold mb-0 text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Available Stock</p>
                                    <h4 class="fw-bolder text-dark mb-0">{{ number_format($totalAvailableUnits, 0) }}</h4>
                                </div>
                            </div>
                            <a href="{{ route('show-trucks') }}" class="stretched-link"></a>
                        </div>
                    </div>

                    <!-- Card 6: Total Vendor Balance -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden hover-lift transition-all bg-white">
                            <div class="card-body p-4 d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #fee2e2; color: #ef4444;">
                                    <i class="las la-file-invoice-dollar fs-2"></i>
                                </div>
                                <div>
                                    <p class="text-muted fw-bold mb-0 text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Vendor Balance</p>
                                    <h4 class="fw-bolder text-dark mb-0">{{ number_format($totalVendorBalance, 0) }}</h4>
                                </div>
                            </div>
                            <a href="{{ route('Vendor-ledger-report') }}" class="stretched-link"></a>
                        </div>
                    </div>

                    <!-- Card 7: Total Customers -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden hover-lift transition-all bg-white">
                            <div class="card-body p-4 d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #e0e7ff; color: #6366f1;">
                                    <i class="las la-users fs-2"></i>
                                </div>
                                <div>
                                    <p class="text-muted fw-bold mb-0 text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Total Customers</p>
                                    <h4 class="fw-bolder text-dark mb-0">{{ $totalCustomers }}</h4>
                                </div>
                            </div>
                            <a href="{{ route('customer') }}" class="stretched-link"></a>
                        </div>
                    </div>

                    <!-- Card 8: Total Vendors -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden hover-lift transition-all bg-white">
                            <div class="card-body p-4 d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #ccfbf1; color: #14b8a6;">
                                    <i class="las la-user-tag fs-2"></i>
                                </div>
                                <div>
                                    <p class="text-muted fw-bold mb-0 text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Total Vendors</p>
                                    <h4 class="fw-bolder text-dark mb-0">{{ $totalVendors }}</h4>
                                </div>
                            </div>
                            <a href="{{ route('vendor') }}" class="stretched-link"></a>
                        </div>
                    </div>

                </div><!-- row end-->

                <!-- TRENDS SECTION -->
                <div class="row gy-4 mb-30">
                    <div class="col-xl-12">
                        <div class="card shadow-sm border-0 h-100 pb-3">
                            <div class="card-header bg-white border-bottom-0 pt-3">
                                <h6 class="fw-bold fs-5"><i class="las la-history me-2 text-danger"></i>12-Month Receivable Trend (Company Closing Balance)</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="monthlyReceivablesChart" style="min-height: 450px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gy-4 mb-30">
                    <!-- Top 10 Receivables Chart -->
                    <div class="col-xl-8">
                        <div class="card shadow-sm border-0 h-100 pb-3" style="border-radius: 16px;">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold fs-5 mb-0" style="color: #1e293b;"><i class="las la-trophy me-2 text-warning" style="font-size: 1.4rem;"></i>Top 10 Receivables</h6>
                                <p class="text-muted small mt-1">Customers with the highest outstanding balance</p>
                            </div>
                            <div class="card-body px-4">
                                <canvas id="topReceivablesChart" style="min-height: 400px;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Receivable Watchlist -->
                    <div class="col-xl-4">
                        <div class="card shadow-sm border-0 h-100" style="border-radius: 16px; background: linear-gradient(145deg, #ffffff, #f8fafc);">
                            <div class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-2">
                                <h6 class="fw-bold fs-5 mb-0" style="color: #ef4444;"><i class="las la-bell me-2" style="font-size: 1.4rem;"></i>Alert Watchlist</h6>
                                <p class="text-muted small mt-1">Debt increased this month</p>
                            </div>
                            <div class="card-body p-0 px-3 pb-3">
                                <div class="watchlist-scroll pe-2" style="max-height: 420px; overflow-y: auto;">
                                    <div class="d-flex flex-column gap-2 mt-2">
                                        @forelse($watchlistCustomers as $customer)
                                        <div class="d-flex justify-content-between align-items-center p-3 bg-white rounded-3 shadow-sm border border-light transition-all hover-lift">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #fee2e2; color: #ef4444;">
                                                    <i class="las la-user fs-4"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">{{ $customer['name'] }}</h6>
                                                    <small class="text-muted">High Risk</small>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge rounded-pill" style="background-color: #fef2f2; color: #ef4444; border: 1px solid #fecaca; font-size: 0.85rem;">
                                                    <i class="las la-arrow-up"></i> {{ number_format($customer['change'], 0) }}
                                                </span>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="text-center p-4">
                                            <i class="las la-check-circle text-success" style="font-size: 3rem;"></i>
                                            <h6 class="mt-2 text-muted">All Clear!</h6>
                                            <p class="small text-muted mb-0">No debt increases recorded this month.</p>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gy-4 mb-30">
                    <div class="col-xl-12">
                        <div class="card shadow-sm border-0 h-100 pb-3" style="background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);">
                            <div class="card-header bg-transparent border-bottom-0 pt-3">
                                <h6 class="fw-bold fs-5"><i class="las la-chart-pie me-2" style="color:#6366f1;"></i>Monthly Sales Breakdown ({{ now()->year }} - 12 Months)</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @for($i=0; $i<12; $i++)
                                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                        <div class="p-3 bg-white rounded-4 shadow-sm border border-light h-100 d-flex flex-column align-items-center justify-content-center transition-all" style="transition: transform 0.3s ease;">
                                            <div style="height: 110px; width: 100%; position: relative;">
                                                <canvas id="salesDoughnut_{{ $i }}"></canvas>
                                            </div>
                                            <span class="badge bg-light text-dark border border-secondary mt-3 px-3 py-2 rounded-pill" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                                {{ \Carbon\Carbon::create(now()->year, $i + 1, 1)->format('M, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                                <div class="mt-4 text-center pb-2 border-top pt-4">
                                    <span class="badge rounded-pill shadow-sm me-3 py-2 px-4" style="background: linear-gradient(45deg, #10b981, #059669); font-size:14px;"><i class="las la-money-bill"></i> Cash Sale</span>
                                    <span class="badge rounded-pill shadow-sm py-2 px-4" style="background: linear-gradient(45deg, #8b5cf6, #7c3aed); font-size:14px;"><i class="las la-credit-card"></i> Credit Sale</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gy-4 mb-30">
                    <div class="col-xl-12">
                        <div class="card shadow-sm border-0 h-100 pb-3">
                            <div class="card-header bg-white border-bottom-0 pt-3">
                                <h6 class="fw-bold fs-5"><i class="las la-hand-holding-usd me-2 text-success"></i>Monthly Recovery Trend ({{ now()->year }})</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="recoveryLineChart" style="max-height: 350px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>



                <script>
                    Chart.register(ChartDataLabels);

                    // Custom Plugin for Center Text in Doughnut
                    const centerTextPlugin = {
                        id: 'centerText',
                        afterDraw: function(chart) {
                            if (chart.config.type === 'doughnut' && chart.options.plugins.centerText) {
                                var width = chart.width, height = chart.height, ctx = chart.ctx;
                                ctx.restore();
                                
                                // Make the main text much larger
                                var fontSize = (height / 80).toFixed(2); // increased size significantly
                                ctx.font = "bold " + fontSize + "em sans-serif";
                                ctx.textBaseline = "middle";
                                ctx.fillStyle = chart.options.plugins.centerText.color || "#000";
                                var text = chart.options.plugins.centerText.text,
                                    textX = Math.round((width - ctx.measureText(text).width) / 2),
                                    textY = (height / 2) - 5; // shifted slightly up
                                ctx.fillText(text, textX, textY);
                                
                                // Make subtext larger as well
                                var subtext = chart.options.plugins.centerText.subtext;
                                if(subtext) {
                                    ctx.font = "bold " + (height / 140).toFixed(2) + "em sans-serif";
                                    var subtextX = Math.round((width - ctx.measureText(subtext).width) / 2),
                                        subtextY = (height / 2) + 18; // space it below main text
                                    ctx.fillText(subtext, subtextX, subtextY);
                                }
                                ctx.save();
                            }
                        }
                    };
                    Chart.register(centerTextPlugin);

                    document.addEventListener('DOMContentLoaded', function () {
                        const labels = @json($monthLabels);
                        const cashSales = @json($monthlyCashSales);
                        const creditSales = @json($monthlyCreditSales);
                        const recoveries = @json($monthlyRecoveries);
                        const receivables = @json($monthlyReceivables);

                        const top10 = @json($top10Debtors);
                        const perf = @json($performanceStats);
                        const totalPrev = {{ $totalPreviousMonthBalance }};
                        const totalCurr = {{ $totalCurrentBalance }};
                        
                        const totalPeriodCash = {{ $totalPeriodCashSales }};
                        const totalPeriodCredit = {{ $totalPeriodCreditSales }};
                        const totalSalesSum = totalPeriodCash + totalPeriodCredit;

                        // 1. Individual Monthly Sales Doughnuts
                        for (let i = 0; i < 12; i++) {
                            const monthTotal = cashSales[i] + creditSales[i];
                            new Chart(document.getElementById(`salesDoughnut_${i}`), {
                                type: 'doughnut',
                                data: {
                                    labels: ['Cash', 'Credit'],
                                    datasets: [{
                                        data: [cashSales[i], creditSales[i]],
                                        backgroundColor: ['#10b981', '#8b5cf6'],
                                        borderWidth: 0,
                                        hoverOffset: 6
                                    }]
                                },
                                options: {
                                    responsive: true, maintainAspectRatio: false,
                                    cutout: '75%',
                                    plugins: {
                                        legend: { display: false },
                                        datalabels: { display: false },
                                        centerText: {
                                            text: monthTotal > 0 ? (monthTotal/1000000).toFixed(1) + 'M' : '0',
                                            subtext: 'PKR',
                                            color: '#1e293b'
                                        }
                                    }
                                }
                            });
                        }

                        // 2. Recovery Trend Line
                        const ctxRecovery = document.getElementById('recoveryLineChart').getContext('2d');
                        
                        // Create a beautiful vertical gradient for the line chart fill
                        let gradientFill = ctxRecovery.createLinearGradient(0, 0, 0, 350);
                        gradientFill.addColorStop(0, 'rgba(16, 185, 129, 0.5)'); // Vibrant Emerald Green
                        gradientFill.addColorStop(1, 'rgba(16, 185, 129, 0.0)'); // Transparent at bottom

                        new Chart(ctxRecovery, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Total Recovery',
                                    data: recoveries,
                                    borderColor: '#10b981', // Solid emerald border
                                    backgroundColor: gradientFill,
                                    borderWidth: 3,
                                    fill: true,
                                    tension: 0.4, // Smooth curves
                                    pointRadius: 5,
                                    pointBackgroundColor: '#ffffff',
                                    pointBorderColor: '#10b981',
                                    pointBorderWidth: 2,
                                    pointHoverRadius: 8,
                                    pointHoverBackgroundColor: '#10b981',
                                    pointHoverBorderColor: '#ffffff',
                                    pointHoverBorderWidth: 3,
                                }]
                            },
                            options: {
                                responsive: true, 
                                maintainAspectRatio: false,
                                interaction: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                        titleFont: { size: 14, family: 'sans-serif' },
                                        bodyFont: { size: 14, weight: 'bold' },
                                        padding: 12,
                                        cornerRadius: 8,
                                        displayColors: false,
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.dataset.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                if (context.parsed.y !== null) {
                                                    label += context.parsed.y.toLocaleString() + ' PKR';
                                                }
                                                return label;
                                            }
                                        }
                                    },
                                    datalabels: {
                                        anchor: 'end', 
                                        align: 'top', 
                                        offset: 6,
                                        color: '#334155',
                                        formatter: (val) => val > 0 ? (val/1000).toFixed(0) + 'k' : '', // Show shorthand 'k' for cleaner look
                                        font: { weight: 'bold', size: 12 }
                                    }
                                },
                                scales: { 
                                    x: { 
                                        grid: { display: false, drawBorder: false },
                                        ticks: { color: '#64748b', font: { weight: '600' } }
                                    },
                                    y: { 
                                        beginAtZero: true, 
                                        grid: { color: '#e2e8f0', borderDash: [5, 5], drawBorder: false },
                                        ticks: { 
                                            color: '#64748b',
                                            callback: (v) => v >= 1000000 ? (v/1000000).toFixed(1) + 'M' : v.toLocaleString() 
                                        } 
                                    } 
                                }
                            }
                        });

                        // 3. Extended Bar Chart: Receivables History
                        const ctxReceivables = document.getElementById('monthlyReceivablesChart').getContext('2d');
                        let gradientReceivable = ctxReceivables.createLinearGradient(0, 0, 0, 400);
                        gradientReceivable.addColorStop(0, '#f43f5e'); // Rose
                        gradientReceivable.addColorStop(1, '#fb923c'); // Orange
                        
                        new Chart(ctxReceivables, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Total Outstanding (PKR)',
                                    data: receivables,
                                    backgroundColor: gradientReceivable,
                                    borderRadius: 6,
                                    barThickness: 30,
                                    hoverBackgroundColor: '#e11d48'
                                }]
                            },
                            options: {
                                responsive: true, 
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                        titleFont: { size: 14, family: 'sans-serif' },
                                        bodyFont: { size: 14, weight: 'bold' },
                                        padding: 12,
                                        cornerRadius: 8,
                                        displayColors: false,
                                        callbacks: {
                                            label: function(context) {
                                                return context.parsed.y.toLocaleString() + ' PKR';
                                            }
                                        }
                                    },
                                    datalabels: {
                                        anchor: 'end', 
                                        align: 'top', 
                                        color: '#475569',
                                        formatter: (val) => val > 0 ? (val/1000000).toFixed(2) + 'M' : '',
                                        font: { weight: 'bold', size: 11 }
                                    }
                                },
                                scales: { 
                                    x: { 
                                        grid: { display: false, drawBorder: false },
                                        ticks: { color: '#64748b', font: { weight: '600' } }
                                    },
                                    y: { 
                                        beginAtZero: true, 
                                        suggestedMax: Math.max(...receivables) * 1.2, 
                                        grid: { color: '#e2e8f0', borderDash: [5, 5], drawBorder: false },
                                        ticks: { 
                                            color: '#64748b',
                                            callback: (v) => v >= 1000000 ? (v/1000000).toFixed(1) + 'M' : v.toLocaleString() 
                                        } 
                                    } 
                                }
                            }
                        });

                        // 4. Horizontal Bar: Top 10 Receivables
                        const ctxTop = document.getElementById('topReceivablesChart').getContext('2d');
                        let gradientBar = ctxTop.createLinearGradient(0, 0, 400, 0);
                        gradientBar.addColorStop(0, '#3b82f6'); // Blue
                        gradientBar.addColorStop(1, '#6366f1'); // Indigo
                        
                        new Chart(ctxTop, {
                            type: 'bar',
                            data: {
                                labels: top10.map(i => i.name),
                                datasets: [{
                                    data: top10.map(i => i.bal_curr),
                                    backgroundColor: gradientBar,
                                    borderRadius: 8,
                                    barThickness: 16,
                                    hoverBackgroundColor: '#4f46e5'
                                }]
                            },
                            options: {
                                indexAxis: 'y', 
                                responsive: true, 
                                maintainAspectRatio: false,
                                plugins: { 
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                        titleFont: { size: 14, family: 'sans-serif' },
                                        bodyFont: { size: 14, weight: 'bold' },
                                        padding: 12,
                                        cornerRadius: 8,
                                        displayColors: false,
                                        callbacks: {
                                            label: function(context) {
                                                return context.parsed.x.toLocaleString() + ' PKR';
                                            }
                                        }
                                    },
                                    datalabels: { 
                                        anchor: 'end', 
                                        align: 'right', 
                                        color: '#64748b',
                                        formatter: (v) => v > 0 ? (v/1000).toFixed(0) + 'k' : '',
                                        font: { weight: 'bold', size: 12 }
                                    }
                                },
                                scales: { 
                                    x: { 
                                        suggestedMax: top10.length > 0 ? top10[0].bal_curr * 1.15 : 1000000,
                                        grid: { color: '#e2e8f0', borderDash: [5, 5], drawBorder: false },
                                        ticks: { 
                                            color: '#64748b',
                                            callback: (v) => v >= 1000000 ? (v/1000000).toFixed(1) + 'M' : v.toLocaleString() 
                                        }
                                    },
                                    y: {
                                        grid: { display: false, drawBorder: false },
                                        ticks: { color: '#334155', font: { weight: 'bold' } }
                                    }
                                }
                            }
                        });


                    });
                </script>

                <style>
                    .card-header h6 { font-size: 1rem; letter-spacing: 0.5px; }
                    canvas { padding-top: 35px; } /* Space for labels */
                </style>



            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>

    @include('admin_panel.include.footer_include')