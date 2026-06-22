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

                <div class="d-flex mb-30 flex-wrap gap-3 justify-content-between align-items-center">
                    <h6 class="page-title">Dashboard</h6>
                    <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center breadcrumb-plugins">
                    </div>
                </div>


                <div class="row gy-4 mb-30">

                    <!-- Card 1: Current Total Receivable -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="widget-two box--shadow2 b-radius--5 bg--white">
                            <i class="las la-wallet overlay-icon text--primary"></i>
                            <div class="widget-two__icon b-radius--5 bg--primary">
                                <i class="las la-wallet"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3>{{ number_format($totalCurrentBalance, 0) }}</h3>
                                <p>Current Total Receivable</p>
                                @php 
                                    $diff = $totalCurrentBalance - $totalPreviousMonthBalance;
                                    $percent = $totalPreviousMonthBalance > 0 ? ($diff / $totalPreviousMonthBalance) * 100 : 0;
                                @endphp
                                <span class="fw-bold {{ $diff > 0 ? 'text--danger' : 'text--success' }}" style="font-size: 0.8rem;">
                                    {{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 0) }} 
                                    ({{ number_format($percent, 1) }}%)
                                    <i class="las la-arrow-{{ $diff > 0 ? 'up' : 'down' }}"></i>
                                </span>
                            </div>
                            <a href="{{ route('customer.receivable.report') }}" class="widget-two__btn btn btn-outline--primary">Detailed Report</a>
                        </div>
                    </div>

                    <!-- Card 2: Previous Month Closing -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="widget-two box--shadow2 b-radius--5 bg--white">
                            <i class="las la-history overlay-icon text--secondary"></i>
                            <div class="widget-two__icon b-radius--5 bg--secondary">
                                <i class="las la-history"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3>{{ number_format($totalPreviousMonthBalance, 0) }}</h3>
                                <p>Prev Month Closing</p>
                                <span class="text--muted" style="font-size: 0.8rem;">Snapshot as of 1st {{ now()->format('M') }}</span>
                            </div>
                            <a href="{{ route('customer.receivable.report') }}?to_date={{ now()->startOfMonth()->subDay()->format('Y-m-d') }}" class="widget-two__btn btn btn-outline--secondary">View History</a>
                        </div>
                    </div>

                    <!-- Card 3: Daily Sales Unit (Qty) -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="widget-two box--shadow2 b-radius--5 bg--white">
                            <i class="las la-box overlay-icon text--info"></i>
                            <div class="widget-two__icon b-radius--5 bg--info">
                                <i class="las la-box"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3>{{ number_format($todaysSaleUnit, 0) }}</h3>
                                <p>Daily Sales Units</p>
                            </div>
                            <a href="{{ route('daily-sale') }}" class="widget-two__btn btn btn-outline--info">View Details</a>
                        </div>
                    </div>

                    <!-- Card 4: Today's Recovery -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="widget-two box--shadow2 b-radius--5 bg--white">
                            <i class="las la-money-bill-wave overlay-icon text--warning"></i>
                            <div class="widget-two__icon b-radius--5 bg--warning">
                                <i class="las la-money-bill-wave"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3>{{ number_format($todaysRecovery, 0) }}</h3>
                                <p>Today's Recovery</p>
                            </div>
                            <a href="{{ route('daily-recovery') }}" class="widget-two__btn btn btn-outline--warning">View Recovery</a>
                        </div>
                    </div>

                    <!-- Card 5: Total Available Units -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="widget-two box--shadow2 b-radius--5 bg--white">
                            <i class="las la-boxes overlay-icon text--dark"></i>
                            <div class="widget-two__icon b-radius--5 bg--dark">
                                <i class="las la-boxes"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3 class="text--dark">{{ number_format($totalAvailableUnits, 0) }}</h3>
                                <p>Total Available Units</p>
                            </div>
                            <a href="{{ route('show-trucks') }}" class="widget-two__btn btn btn-outline--dark">View Stock</a>
                        </div>
                    </div>

                    <!-- Card 6: Total Vendor Balance -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="widget-two box--shadow2 b-radius--5 bg--white">
                            <i class="las la-file-invoice-dollar overlay-icon text--danger"></i>
                            <div class="widget-two__icon b-radius--5 bg--danger">
                                <i class="las la-file-invoice-dollar"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3>{{ number_format($totalVendorBalance, 0) }}</h3>
                                <p>Total Vendor Balance</p>
                            </div>
                            <a href="{{ route('Vendor-ledger-report') }}" class="widget-two__btn btn btn-outline--danger">View Ledger</a>
                        </div>
                    </div>

                    <!-- Card 7: Total Customers -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="widget-two box--shadow2 b-radius--5 bg--white">
                            <i class="las la-users overlay-icon text--indigo"></i>
                            <div class="widget-two__icon b-radius--5 bg--indigo">
                                <i class="las la-users"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3>{{ $totalCustomers }}</h3>
                                <p>Total Customers</p>
                            </div>
                            <a href="{{ route('customer') }}" class="widget-two__btn btn btn-outline--secondary" style="border-color: #6610f2; color: #6610f2;">Manage</a>
                        </div>
                    </div>

                    <!-- Card 8: Total Vendors -->
                    <div class="col-xxl-3 col-sm-6">
                        <div class="widget-two box--shadow2 b-radius--5 bg--white">
                            <i class="las la-user-tag overlay-icon text--teal"></i>
                            <div class="widget-two__icon b-radius--5 bg--teal">
                                <i class="las la-user-tag"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3>{{ $totalVendors }}</h3>
                                <p>Total Vendors</p>
                            </div>
                            <a href="{{ route('vendor') }}" class="widget-two__btn btn btn-outline--success" style="border-color: #20c997; color: #20c997;">Manage</a>
                        </div>
                    </div>

                </div><!-- row end-->

                <!-- TRENDS SECTION -->
                <div class="row gy-4 mb-30">
                    <div class="col-xl-12">
                        <div class="card shadow-sm border-0 h-100 pb-3">
                            <div class="card-header bg-white border-bottom-0 pt-3">
                                <h6 class="fw-bold"><i class="las la-history me-2 text-danger"></i>6-Month Receivable Trend (Company Closing Balance)</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="monthlyReceivablesChart" style="min-height: 450px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gy-4 mb-30">
                    <div class="col-xl-8">
                        <div class="card shadow-sm border-0 h-100 pb-3">
                            <div class="card-header bg-white border-bottom-0 pt-3">
                                <h6 class="fw-bold"><i class="las la-chart-bar me-2 text-primary"></i>Top 10 Receivables</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="topReceivablesChart" style="max-height: 450px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white border-bottom-0 pt-3">
                                <h6 class="fw-bold"><i class="las la-exclamation-triangle me-2 text-warning"></i>Receivable Watchlist</h6>
                                <p class="text-muted small">Customers with increasing debt this month</p>
                            </div>
                            <div class="card-body p-0">
                                <div class="watchlist-scroll" style="max-height: 600px; overflow-y: auto;">
                                    <table class="table table--light">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th class="text-end">Increase</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($watchlistCustomers as $customer)
                                            <tr>
                                                <td class="fw-bold">{{ $customer['name'] }}</td>
                                                <td class="text-end text--danger fw-bold">+{{ number_format($customer['change'], 0) }}</td>
                                            </tr>
                                            @endforeach
                                            @if($watchlistCustomers->isEmpty())
                                            <tr><td colspan="2" class="text-center text-muted py-4">No debt increases recorded</td></tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gy-4 mb-30">
                    <div class="col-xl-6">
                        <div class="card shadow-sm border-0 h-100 pb-3">
                            <div class="card-header bg-white border-bottom-0 pt-3">
                                <h6 class="fw-bold"><i class="las la-chart-pie me-2 text-primary"></i>Monthly Sales Breakdown (6-Month Granular)</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    @for($i=0; $i<6; $i++)
                                    <div class="col-4 text-center">
                                        <div style="height: 140px; position: relative;">
                                            <canvas id="salesDoughnut_{{ $i }}"></canvas>
                                        </div>
                                        <small class="fw-bold d-block mt-1">{{ \Illuminate\Support\Carbon::now()->subMonths(5-$i)->format('F') }}</small>
                                    </div>
                                    @endfor
                                </div>
                                <div class="mt-3 text-center small text-muted">
                                    <span class="badge bg--primary me-2">Cash Sale</span>
                                    <span class="badge bg--orange">Credit Sale</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card shadow-sm border-0 h-100 pb-3">
                            <div class="card-header bg-white border-bottom-0 pt-3">
                                <h6 class="fw-bold"><i class="las la-hand-holding-usd me-2 text-success"></i>Monthly Recovery Trend</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="recoveryLineChart" style="max-height: 350px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gy-4 mb-30">
                    <div class="col-xl-6 col-lg-6">
                        <div class="card shadow-sm border-0 h-100 pb-3">
                            <div class="card-header bg-white border-bottom-0 pt-3 d-flex justify-content-between">
                                <h6 class="fw-bold"><i class="las la-chart-pie me-2 text-info"></i>Total Balance Snapshot</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="volumeTrendChart" style="max-height: 250px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="card shadow-sm border-0 h-100 pb-3">
                            <div class="card-header bg-white border-bottom-0 pt-3">
                                <h6 class="fw-bold"><i class="las la-balance-scale me-2 text-warning"></i>Performance Count</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="performancePieChart" style="max-height: 250px;"></canvas>
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
                                var fontSize = (height / 200).toFixed(2);
                                ctx.font = "bold " + fontSize + "em sans-serif";
                                ctx.textBaseline = "middle";
                                ctx.fillStyle = chart.options.plugins.centerText.color || "#000";
                                var text = chart.options.plugins.centerText.text,
                                    textX = Math.round((width - ctx.measureText(text).width) / 2),
                                    textY = height / 2;
                                ctx.fillText(text, textX, textY);
                                // Subtext
                                var subtext = chart.options.plugins.centerText.subtext;
                                if(subtext) {
                                    ctx.font = (height / 450).toFixed(2) + "em sans-serif";
                                    var subtextX = Math.round((width - ctx.measureText(subtext).width) / 2),
                                        subtextY = (height / 2) + 25;
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
                        for (let i = 0; i < 6; i++) {
                            const monthTotal = cashSales[i] + creditSales[i];
                            new Chart(document.getElementById(`salesDoughnut_${i}`), {
                                type: 'doughnut',
                                data: {
                                    labels: ['Cash', 'Credit'],
                                    datasets: [{
                                        data: [cashSales[i], creditSales[i]],
                                        backgroundColor: ['#0d6efd', '#fd7e14'],
                                        borderWidth: 1,
                                        borderRadius: 2
                                    }]
                                },
                                options: {
                                    responsive: true, maintainAspectRatio: false,
                                    cutout: '65%',
                                    plugins: {
                                        legend: { display: false },
                                        datalabels: { display: false },
                                        centerText: {
                                            text: monthTotal > 0 ? (monthTotal/1000000).toFixed(1) + 'M' : '0',
                                            subtext: '',
                                            color: '#333'
                                        }
                                    }
                                }
                            });
                        }

                        // 2. Recovery Trend Line
                        new Chart(document.getElementById('recoveryLineChart'), {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Monthly Recovery',
                                    data: recoveries,
                                    borderColor: '#198754',
                                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 6,
                                    pointBackgroundColor: '#198754'
                                }]
                            },
                            options: {
                                responsive: true, maintainAspectRatio: false,
                                plugins: {
                                    datalabels: {
                                        anchor: 'end', align: 'top', 
                                        formatter: (val) => val > 0 ? Math.round(val).toLocaleString() : '',
                                        font: { weight: 'bold' }
                                    }
                                },
                                scales: { y: { beginAtZero: true, ticks: { callback: (v) => v.toLocaleString() } } }
                            }
                        });

                        // 3. Extended Bar Chart: Receivables History
                        new Chart(document.getElementById('monthlyReceivablesChart'), {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Total Outstanding (Rs)',
                                    data: receivables,
                                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                                    borderRadius: 5
                                }]
                            },
                            options: {
                                responsive: true, maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    datalabels: {
                                        anchor: 'end', align: 'top', rotation: -90, offset: 10, color: '#dc3545',
                                        formatter: (val) => Math.round(val).toLocaleString(),
                                        font: { weight: 'bold', size: 11 }
                                    }
                                },
                                scales: { y: { beginAtZero: true, suggestedMax: 120000000, ticks: { callback: (v) => v.toLocaleString() } } }
                            }
                        });

                        // 4. Horizontal Bar: Top 10 Receivables
                        new Chart(document.getElementById('topReceivablesChart'), {
                            type: 'bar',
                            data: {
                                labels: top10.map(i => i.name),
                                datasets: [{
                                    data: top10.map(i => i.bal_curr),
                                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                    borderRadius: 5
                                }]
                            },
                            options: {
                                indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                                plugins: { 
                                    legend: { display: false },
                                    datalabels: { 
                                        anchor: 'end', align: 'right', 
                                        formatter: (v) => Math.round(v).toLocaleString() 
                                    }
                                },
                                scales: { x: { suggestedMax: 120000000 } }
                            }
                        });

                        // 5. Volume Snapshot (Prev vs Curr)
                        new Chart(document.getElementById('volumeTrendChart'), {
                            type: 'doughnut',
                            data: {
                                labels: ['Previous Month', 'Current Month'],
                                datasets: [{
                                    data: [totalPrev, totalCurr],
                                    backgroundColor: ['#6c757d', '#0d6efd']
                                }]
                            },
                            options: { 
                                responsive: true, maintainAspectRatio: false,
                                plugins: {
                                    datalabels: { display: false }
                                }
                            }
                        });

                        // 6. Performance Pie (Inc vs Dec vs Stable)
                        new Chart(document.getElementById('performancePieChart'), {
                            type: 'pie',
                            data: {
                                labels: ['Increased', 'Decreased', 'Stable'],
                                datasets: [{
                                    data: [perf.increased, perf.decreased, perf.stable],
                                    backgroundColor: ['#dc3545', '#198754', '#adb5bd']
                                }]
                            },
                            options: { 
                                responsive: true, maintainAspectRatio: false,
                                plugins: {
                                    datalabels: {
                                        color: '#fff',
                                        formatter: (v) => v > 0 ? v : '',
                                        font: { weight: 'bold' }
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