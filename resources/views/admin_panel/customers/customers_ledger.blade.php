@include('admin_panel.include.header_include')

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

                <div class="d-flex mb-30 flex-wrap gap-3 justify-content-between align-items-center">
                    <h6 class="page-title">Customers Ledgers</h6>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card b-radius--10">
                            <div class="card-body p-0">
                                <div class="table-responsive--sm table-responsive">
                                    <table id="example" class="display  table table--light" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Phone Number</th>
                                                <th>Previous Balance</th>
                                                <th>Closing Balance</th>
                                                <th>Updated At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($CustomerLedgers as $ledger)
                                            <tr>
                                                <td>{{ $ledger->customer_id }}</td>
                                                <td>{{ $ledger->Customer->customer_name }}</td>
                                                <td>{{ $ledger->Customer->customer_phone }}</td>
                                                <td>{{ number_format((float) $ledger->previous_balance, 0) }}</td>

                                                <td id="closing_balance_{{ $ledger->id }}">{{ number_format($ledger->closing_balance, 0) }}</td>
                                                <td>{{ $ledger->updated_at->format('Y-m-d H:i:s') }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No records found.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>
    @include('admin_panel.include.footer_include')