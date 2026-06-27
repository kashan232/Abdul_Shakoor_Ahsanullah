@include('admin_panel.include.header_include')

<body>
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        @include('admin_panel.include.navbar_include')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                <div class="d-flex mb-30 flex-wrap gap-3 justify-content-between align-items-center">
                    <h6 class="page-title">Customers Current Balance Report</h6>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card b-radius--10">
                            <div class="card-body p-4">
                                <form method="GET" action="{{ route('customers.current.balance.report') }}" id="balanceReportFilterForm" class="mb-4">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-4">
                                            <label for="to_date" class="fw-bold">Select Date:</label>
                                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                                        </div>
                                        <div class="col-md-8 d-flex align-items-end gap-2">
                                            <button type="submit" class="btn btn-primary h-45">Filter</button>
                                            <a href="{{ route('customers.current.balance.report', ['to_date' => $toDate, 'download' => 'pdf']) }}" class="btn btn-success h-45">Download PDF</a>
                                        </div>
                                    </div>
                                </form>

                                @if(count($data) > 0)
                                <div class="table-responsive--sm table-responsive">
                                    <table id="example" class="display table table--light" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Customer</th>
                                                <th>Opening Balance</th>
                                                <th>Total Sales</th>
                                                <th>Total Recoveries</th>
                                                <th>Closing Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data as $customer)
                                            <tr>
                                                <td>{{ $customer->customer_name }}</td>
                                                <td>{{ number_format($customer->opening_balance, 2) }}</td>
                                                <td>{{ number_format($customer->total_sales, 2) }}</td>
                                                <td>{{ number_format($customer->total_recoveries, 2) }}</td>
                                                <td class="fw-bold">{{ number_format($customer->closing_balance, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light fw-bold">
                                            <tr>
                                                <th class="text-start">Total</th>
                                                <th></th>
                                                <th>{{ number_format($totalSales, 2) }}</th>
                                                <th>{{ number_format($totalRecoveries, 2) }}</th>
                                                <th>{{ number_format($totalClosingBalance, 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                @else
                                    <div class="alert alert-warning">No Data Found</div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('admin_panel.include.footer_include')
</body>
