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
                    <h6 class="page-title">Customer Recoveries</h6>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card b-radius--10">
                            <div class="card-body p-0">
                                <div class="table-responsive--sm table-responsive">
                                    <table id="example" class="display  table table--light" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Customer</th>
                                                <th>Description</th>
                                                <th>Amount Paid</th>
                                                <th>Bank</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($Recoveries as $key => $recovery)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $recovery->customer->customer_name ?? 'N/A' }}</td>
                                                <td>{{ $recovery->description }}</td>
                                                <td>{{ number_format($recovery->amount_paid, 0) }}</td>
                                                <td>{{ $recovery->Bank }}</td>
                                                <td>{{ $recovery->date }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary edit-btn" 
                                                        data-id="{{ $recovery->id }}" 
                                                        data-customer-id="{{ $recovery->customer_ledger_id }}"
                                                        data-amount="{{ $recovery->amount_paid }}"
                                                        data-date="{{ \Carbon\Carbon::parse($recovery->date)->format('Y-m-d') }}"
                                                        data-desc="{{ $recovery->description }}">
                                                        <i class="las la-pen"></i> Edit
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-btn" 
                                                        data-id="{{ $recovery->id }}" 
                                                        data-customer-id="{{ $recovery->customer_ledger_id }}"
                                                        data-amount="{{ $recovery->amount_paid }}">
                                                        <i class="las la-trash"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @if($Recoveries->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center">No recoveries found.</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $Recoveries->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Recovery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        <input type="hidden" name="recovery_id" id="edit_recovery_id">
                        <input type="hidden" name="customer_id" id="edit_customer_id">
                        
                        <div class="form-group mb-3">
                            <label>Amount Paid</label>
                            <input type="number" name="amount_paid" id="edit_amount" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Date</label>
                            <input type="date" name="date" id="edit_date" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Description</label>
                            <input type="text" name="description" id="edit_desc" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Recovery</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin_panel.include.footer_include')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Edit Button Click
            $('.edit-btn').click(function() {
                $('#edit_recovery_id').val($(this).data('id'));
                $('#edit_customer_id').val($(this).data('customer-id'));
                $('#edit_amount').val($(this).data('amount'));
                $('#edit_date').val($(this).data('date'));
                $('#edit_desc').val($(this).data('desc'));
                $('#editModal').modal('show');
            });

            // Submit Edit Form
            $('#editForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('update.recovery') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#editModal').modal('hide');
                        Swal.fire('Success', response.message, 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.message || 'Something went wrong', 'error');
                    }
                });
            });

            // Delete Button Click
            $('.delete-btn').click(function() {
                let recovery_id = $(this).data('id');
                let customer_id = $(this).data('customer-id');
                let amount = $(this).data('amount');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Deleting this recovery will add the amount back to the customer's ledger balance!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('delete.recovery') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                recovery_id: recovery_id,
                                customer_id: customer_id,
                                amount: amount
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', response.message, 'success').then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire('Error', xhr.responseJSON.message || 'Something went wrong', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>