@include('admin_panel.include.header_include')

<body>
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        @include('admin_panel.include.navbar_include')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                <div class="d-flex mb-4 flex-wrap gap-3 justify-content-between align-items-center">
                    <h4 class="fw-bold text-primary"> Available Trucks for Sale</h4>
                </div>
                <div class="card shadow-lg">
                    <div class="card-body">
                        <div class="table-responsive--sm table-responsive">
                            <table id="example" class="display  table table--light" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Truck Number</th>
                                        <th>Vendor</th>
                                        <th>Arrival Date</th>
                                        <th>Lot Details (Avail/Total)</th>
                                        <th>Total Available Units</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trucks as $truck)
                                    <tr>
                                        <td>{{ $truck->truck_number }}</td>
                                        <td>{{ $truck->vendor_id }}</td>
                                        <td>{{ date('d-m-Y', strtotime($truck->entry_date)) }}</td>
                                        <td>
                                            @if($truck->lots && $truck->lots->count() > 0)
                                                <div class="d-flex flex-column gap-1" style="min-width: 200px;">
                                                    @foreach($truck->lots as $lot)
                                                    <div class="d-flex justify-content-between align-items-center" style="font-size: 12px; border-bottom: 1px solid #f1f1f1; padding-bottom: 4px;">
                                                        <span><strong>{{ $lot->category }}</strong> <span class="text-muted" style="font-size: 11px;">({{ $lot->variety }})</span></span>
                                                        <span>
                                                            <span style="color: #0f5132; font-weight: 700;" title="Total Received">{{ $lot->total_units }}</span> / 
                                                            <span class="text-danger fw-bold" title="Available Balance">{{ $lot->lot_quantity }}</span> 
                                                            <span class="text-muted" style="font-size: 11px;">{{ $lot->unit_in }}</span>
                                                        </span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">No Lots</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-success" style="font-size: 14px;">{{ $truck->total_units }}</span></td>
                                        <td>
                                            @if($truck->total_units > 0)
                                            <a href="{{ route('show-Lots', $truck->id) }}" class="btn btn-primary btn-sm">Sale</a>
						{{-- <a href="{{ route('lot.sale.bulk', $truck->id) }}" class="btn btn-info btn-sm ms-1">Bulk Sale</a> --}}
					  @else
                                            <span class="btn btn-danger btn-sm">Units Sold</span>
                                            @endif
                                            <a href="{{ route('sale-record', $truck->id) }}" class="btn btn-success btn-sm">Sale Record</a>

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('admin_panel.include.footer_include')



</body>