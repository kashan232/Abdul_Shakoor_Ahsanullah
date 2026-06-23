@include('admin_panel.include.header_include')

<style>
    /* Premium UI Styles */
    .premium-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.03);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .premium-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .page-title i {
        color: #2ecc71;
        background: rgba(46, 204, 113, 0.1);
        padding: 10px;
        border-radius: 12px;
        font-size: 1.2rem;
    }
    .premium-table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .premium-table thead th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 15px;
        border-bottom: 2px solid #e9ecef;
        border-top: none;
    }
    .premium-table tbody tr {
        transition: background-color 0.2s ease;
    }
    .premium-table tbody tr:hover {
        background-color: #f1f5f9;
    }
    .premium-table tbody td {
        padding: 15px;
        vertical-align: middle;
        color: #555;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
    }
    .premium-table tbody tr:last-child td {
        border-bottom: none;
    }
    .badge-soft-success {
        background-color: rgba(46, 204, 113, 0.15);
        color: #27ae60;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 10px 15px;
        box-shadow: none;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .form-control:focus {
        border-color: #2ecc71;
        box-shadow: 0 0 0 0.2rem rgba(46, 204, 113, 0.25);
    }
    .btn-premium {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(46, 204, 113, 0.2);
    }
    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(46, 204, 113, 0.3);
        color: #fff;
    }
</style>

<body>
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">

        <!-- sidebar start -->
        @include('admin_panel.include.sidebar_include')
        <!-- sidebar end -->

        <!-- navbar-wrapper start -->
        @include('admin_panel.include.navbar_include')
        <!-- navbar-wrapper end -->

        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                <div class="d-flex mb-4 flex-wrap gap-3 justify-content-between align-items-center">
                    <h6 class="page-title">
                        <i class="fas fa-money-bill-wave"></i> Cash Sale Record
                    </h6>
                </div>

                <div class="premium-card p-4 mb-4">
                    <div class="card-body p-0">
                        <form method="GET" action="{{ route('cash-sale') }}">
                            <div class="row align-items-end">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-muted fw-bold">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-muted fw-bold">End Date</label>
                                    <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}" required>
                                </div>
                                <div class="col-md-4 mb-3 d-flex gap-2">
                                    <button type="submit" class="btn-premium flex-grow-1">
                                        <i class="fas fa-search"></i> Search Sales
                                    </button>
                                    @if(request('start_date'))
                                    <a href="{{ route('cash-sale') }}" class="btn btn-secondary rounded-3 d-flex align-items-center justify-content-center" style="padding: 10px 20px;" title="Reset Filter">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="premium-card">
                            <div class="table-responsive">
                                <table class="premium-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Sale Date</th>
                                            <th class="text-start">Category</th>
                                            <th class="text-start">Variety</th>
                                            <th class="text-center">Sold Units</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-end">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($cash_sales as $sale)
                                        <tr>
                                            <td class="text-center"><strong>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M, Y') }}</strong></td>
                                            <td class="text-start">{{ $sale->category }}</td>
                                            <td class="text-start">{{ $sale->variety }}</td>
                                            <td class="text-center"><span class="badge badge-soft-success">{{ $sale->quantity }}</span></td>
                                            <td class="text-end">Rs. {{ number_format($sale->price, 2) }}</td>
                                            <td class="text-end"><strong class="text-dark">Rs. {{ number_format($sale->total, 2) }}</strong></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                No cash sales found.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>
    @include('admin_panel.include.footer_include')