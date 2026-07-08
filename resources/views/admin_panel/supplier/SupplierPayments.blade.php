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
                    <h6 class="page-title">Vendors Payments</h6>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card b-radius--10">
                            <div class="card-body p-0">
                                <div class="table-responsive--sm table-responsive">
                                    <table id="example" class="display  table table--light style--two bg--white" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Payment Date</th>
                                                <th>Vendor Name</th>
                                                <th>Amount Paid</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($SupplierPayments as $payment)
                                            <tr>
                                                <td>{{ $payment->id }}</td>
                                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d-M-Y') }}</td>
                                                <td>{{ $payment->supplier->name ?? 'N/A' }}</td>
                                                <td>Rs. {{ number_format($payment->amount_paid, 2) }}</td>
                                                <td>{{ $payment->description }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary edit-btn" 
                                                        data-id="{{ $payment->id }}" 
                                                        data-supplier-id="{{ $payment->supplier_id }}"
                                                        data-amount="{{ $payment->amount_paid }}"
                                                        data-date="{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}"
                                                        data-desc="{{ $payment->description }}">
                                                        <i class="las la-pen"></i> Edit
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-btn" 
                                                        data-id="{{ $payment->id }}" 
                                                        data-supplier-id="{{ $payment->supplier_id }}"
                                                        data-amount="{{ $payment->amount_paid }}">
                                                        <i class="las la-trash"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table><!-- table end -->
                                </div>
                            </div>
                        </div><!-- card end -->
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
                    <h5 class="modal-title">Edit Vendor Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        <input type="hidden" name="payment_id" id="edit_payment_id">
                        <input type="hidden" name="supplier_id" id="edit_supplier_id">
                        
                        <div class="form-group mb-3">
                            <label>Amount Paid</label>
                            <input type="number" name="amount_paid" id="edit_amount" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Date</label>
                            <input type="date" name="payment_date" id="edit_date" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Description</label>
                            <input type="text" name="description" id="edit_desc" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Payment</button>
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
                $('#edit_payment_id').val($(this).data('id'));
                $('#edit_supplier_id').val($(this).data('supplier-id'));
                $('#edit_amount').val($(this).data('amount'));
                $('#edit_date').val($(this).data('date'));
                $('#edit_desc').val($(this).data('desc'));
                $('#editModal').modal('show');
            });

            // Submit Edit Form
            $('#editForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('update.supplier.payment') }}",
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
                let payment_id = $(this).data('id');
                let supplier_id = $(this).data('supplier-id');
                let amount = $(this).data('amount');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Deleting this payment will add the amount back to the vendor's ledger balance!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('delete.supplier.payment') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                payment_id: payment_id,
                                supplier_id: supplier_id,
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