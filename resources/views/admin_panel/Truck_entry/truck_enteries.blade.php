@include('admin_panel.include.header_include')
<meta name="csrf-token" content="{{ csrf_token() }}">

<body>
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        @include('admin_panel.include.navbar_include')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                <div class="d-flex mb-30 flex-wrap gap-3 justify-content-between align-items-center">
                    <h6 class="page-title">Truck Entries</h6>
                    <a href="{{ route('Truck-Entry') }}" class="btn btn-sm btn-success fw-bold"><i class="fas fa-plus"></i> Add New Truck</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive--sm table-responsive">
                            <table id="example" class="display  table table--light" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Truck Number</th>
                                        <th>Driver Name</th>
                                        <th>Vendor</th>
                                        <th>Lot Details (Avail/Total)</th>
                                        <th>Entry Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($truckEntries as $key => $entry)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $entry->truck_number }}</td>
                                        <td>{{ $entry->driver_name }}</td>
                                        <td>{{ $entry->vendor_id }}</td>
                                        <td>
                                            @if($entry->lots && $entry->lots->count() > 0)
                                                <div class="d-flex flex-column gap-1" style="min-width: 200px;">
                                                    @foreach($entry->lots as $lot)
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
                                        <td>{{ date('d-m-Y', strtotime($entry->entry_date)) }}</td>
                                        <td>
                                            <a href="{{ route('Truck-Entry.Show',   $entry->id) }}" class="btn btn-dark btn-sm">View</a>
                                            <a href="{{ route('Truck-Entry.Edit',   $entry->id) }}" class="btn btn-primary btn-sm">Edit</a>

                                            <!-- Delete button with route URL -->
                                            <button
                                                class="btn btn-danger btn-sm btn-delete-entry"
                                                data-url="{{ route('Truck-Entry.Destroy', $entry->id) }}">Delete</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                                <!-- CSRF token for AJAX -->
                                <meta name="csrf-token" content="{{ csrf_token() }}">

                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('admin_panel.include.footer_include')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set CSRF header for all AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            // Delegate click on delete buttons
            $(document).on('click', '.btn-delete-entry', function() {
                let url = $(this).data('url');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently remove the truck entry and its lots.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire('Deleted!', response.message, 'success')
                                    .then(() => $('button[data-url="' + url + '"]').closest('tr').remove());
                            },
                            error: function(xhr) {
                                const msg = xhr.responseJSON?.message || 'Something went wrong.';
                                Swal.fire('Cannot delete', msg, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>


</body>