@include('admin_panel.include.header_include')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body>
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        @include('admin_panel.include.navbar_include')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                <div class="d-flex mb-30 flex-wrap gap-3 justify-content-between align-items-center">
                    <h6 class="page-title">Customer Receivable Report</h6>
                </div>

                <div class="card p-4">
                    <form method="GET" action="{{ route('customer.receivable.report') }}" id="reportFilterForm" class="mb-4">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <label for="to_date" class="fw-bold">Select Date:</label>
                                <input type="date" name="to_date" id="to_date" class="form-control"
                                    value="{{ request('to_date') ?? now()->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-8 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('customer.receivable.report', ['to_date' => request('to_date'), 'download' => 'pdf']) }}"
                                   class="btn btn-danger">Download PDF</a>
                            </div>
                        </div>
                    </form>

                    @if(count($report) > 0)
                    <!-- CHARTS SECTION -->
                    <div class="row gy-4 mb-4">
                        <div class="col-xl-6">
                            <div class="card shadow-sm border-0 h-100 pb-3">
                                <div class="card-header bg-white border-bottom-0 pt-3">
                                    <h6 class="fw-bold"><i class="las la-chart-bar me-2 text-primary"></i>Top 10 Receivables</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="topReceivablesChart" style="max-height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card shadow-sm border-0 h-100 pb-3">
                                <div class="card-header bg-white border-bottom-0 pt-3">
                                    <h6 class="fw-bold"><i class="las la-chart-pie me-2 text-success"></i>Volume Trend</h6>
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <canvas id="trendChart" style="max-height: 250px;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card shadow-sm border-0 h-100 pb-3">
                                <div class="card-header bg-white border-bottom-0 pt-3">
                                    <h6 class="fw-bold"><i class="las la-balance-scale me-2 text-warning"></i>Performance</h6>
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <canvas id="performanceChart" style="max-height: 250px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="report-container">
                        <div class="report-header text-center mb-3 fw-bold fs-5">
                            Customer Receivable Report as of {{ \Carbon\Carbon::parse(request('to_date') ?? now())->format('d M, Y') }}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="receivableTable">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Opening Balance</th>
                                        <th>1st {{ \Carbon\Carbon::parse($previousDate)->format('F') }}</th>
                                        <th>1st {{ \Carbon\Carbon::parse($currentDate)->format('F') }}</th>
                                        <th>Change</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($report as $row)
                                    <tr>
                                        <td>{{ $row['name'] }}</td>
                                        <td class="text-end">{{ number_format($row['opening'], 0) }}</td>
                                        <td class="text-end">{{ number_format($row['balance_previous'], 0) }}</td>
                                        <td class="text-end">{{ number_format($row['balance_current'], 0) }}</td>
                                        <td class="text-end fw-bold" style="color: {{ $row['change'] > 0 ? '#dc3545' : ($row['change'] < 0 ? '#198754' : 'black') }}">
                                            {{ number_format($row['change'], 0) }}
                                            @if($row['change'] > 0) <i class="las la-arrow-up"></i> @elseif($row['change'] < 0) <i class="las la-arrow-down"></i> @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light fw-bold text-end">
                                    <tr>
                                        <td colspan="2" class="text-start fs-5">TOTAL RECEIVABLES</td>
                                        <td class="fs-6">{{ number_format($totalPrevious, 0) }}</td>
                                        <td class="fs-5 text-primary">{{ number_format($totalCurrent, 0) }}</td>
                                        <td class="fs-6" style="color: {{ ($totalCurrent - $totalPrevious) > 0 ? '#dc3545' : '#198754' }}">
                                            {{ number_format($totalCurrent - $totalPrevious, 0) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">No data found for selected date.</div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    @include('admin_panel.include.footer_include')

    @if(count($report) > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rawData = @json($report);
            const totalPrev = {{ $totalPrevious }};
            const totalCurr = {{ $totalCurrent }};

            // 1. Data for Top 10 Debtors
            const top10 = [...rawData]
                .sort((a, b) => b.balance_current - a.balance_current)
                .slice(0, 10);
            
            const debtorNames = top10.map(i => i.name);
            const debtorValues = top10.map(i => i.balance_current);

            new Chart(document.getElementById('topReceivablesChart'), {
                type: 'bar',
                data: {
                    labels: debtorNames,
                    datasets: [{
                        label: 'Current Balance (Rs)',
                        data: debtorValues,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgb(54, 162, 235)',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { grid: { display: false } }, y: { grid: { display: false } } }
                }
            });

            // 2. Data for Volume Trend (Doughnut)
            new Chart(document.getElementById('trendChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Previous Month', 'Current Month'],
                    datasets: [{
                        data: [totalPrev, totalCurr],
                        backgroundColor: ['#6c757d', '#0d6efd'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            // 3. Performance Analysis (Count Increase vs Decrease)
            let increased = 0;
            let decreased = 0;
            let unchanged = 0;
            rawData.forEach(item => {
                if(item.change > 0) increased++;
                else if(item.change < 0) decreased++;
                else unchanged++;
            });

            new Chart(document.getElementById('performanceChart'), {
                type: 'pie',
                data: {
                    labels: ['Increased Debt', 'Decreased Debt', 'Stable'],
                    datasets: [{
                        data: [increased, decreased, unchanged],
                        backgroundColor: ['#dc3545', '#198754', '#adb5bd']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        });
    </script>
    @endif

    <style>
        .report-container {
            max-width: 100% !important;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .report-header {
            border-bottom: 2px solid #343a40;
            padding-bottom: 12px;
            margin-bottom: 20px;
            color: #343a40;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        #receivableTable th {
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .text-end i { font-size: 0.75rem; margin-left: 2px; }
        .card-header h6 { margin-bottom: 0; font-size: 0.9rem; letter-spacing: 0.5px; }
    </style>
</body>
</html>
