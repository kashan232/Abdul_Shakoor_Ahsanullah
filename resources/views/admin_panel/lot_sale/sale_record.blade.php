@include('admin_panel.include.header_include')

<style>
    .card-header-premium {
        background: linear-gradient(90deg, #0f172a 0%, #1e293b 100%) !important;
        color: #ffffff !important;
        border-bottom: 3px solid #22c55e !important;
        border-radius: 8px 8px 0 0;
        padding: 1.5rem 2rem;
    }
    .card-header-lot {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem 1.5rem;
    }
    .table-custom th {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
        font-weight: 700;
        border-bottom: 2px solid #cbd5e1 !important;
    }
    .table-custom td {
        vertical-align: middle;
    }
    .stat-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px 15px;
        text-align: center;
        flex: 1;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .stat-box .title {
        font-size: 11px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .stat-box .value {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }
    .stat-box.success .value { color: #16a34a; }
    .stat-box.danger .value { color: #dc2626; }
    .stat-box.primary .value { color: #2563eb; }
</style>

<body>
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        @include('admin_panel.include.navbar_include')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                <div class="container">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="card shadow-lg border-0 rounded-3">
                        <div class="card-header-premium d-flex justify-content-between align-items-center">
                            <h4 class="fw-bold mb-0 text-white"><i class="fas fa-clipboard-list me-2"></i> Sale Record</h4>
                            <span class="badge bg-success fs-6 px-3 py-2 shadow-sm" style="border: 1px solid #fff;">
                                <i class="fas fa-truck me-1"></i> Truck: {{ $truck->truck_number }}
                            </span>
                        </div>

                        <div class="card-body p-4">
                            @foreach($lots as $lot)
                            <div class="card mb-4 border-0 shadow-sm" style="border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0 !important;">
                                <div class="card-header-lot d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-box-open me-2"></i> {{ $lot->category }} <span class="text-muted fs-6">({{ $lot->variety }})</span></h5>
                                </div>
                                <div class="card-body p-4">
                                    @php
                                    $totalAmount = $lot->sales->sum(function($sale) {
                                        return ($sale->weight ? $sale->weight : $sale->quantity) * $sale->price;
                                    });
                                    $averageSale = $lot->sold_quantity > 0 ? $totalAmount / $lot->sold_quantity : 0;
                                    @endphp

                                    <div class="d-flex flex-wrap gap-3 mb-4">
                                        <div class="stat-box">
                                            <div class="title">Total Quantity</div>
                                            <div class="value">{{ $lot->total_units }}</div>
                                        </div>
                                        <div class="stat-box primary">
                                            <div class="title">Sold Quantity</div>
                                            <div class="value">{{ $lot->sold_quantity }}</div>
                                        </div>
                                        <div class="stat-box danger">
                                            <div class="title">Available</div>
                                            <div class="value">{{ $lot->available_quantity }}</div>
                                        </div>
                                        <div class="stat-box success">
                                            <div class="title">Total Sale Amount</div>
                                            <div class="value">Rs. {{ number_format($totalAmount, 2) }}</div>
                                        </div>
                                        <div class="stat-box">
                                            <div class="title">Avg. Sale / Unit</div>
                                            <div class="value">Rs. {{ number_format($averageSale, 2) }}</div>
                                        </div>
                                    </div>

                                    <div class="table-responsive rounded border">
                                        <table class="table table-hover table-custom mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Customer Name</th>
                                                    <th>Sold Units</th>
                                                    <th>Weight</th> <!-- Add this -->
                                                    <th>Price</th>
                                                    <th>Total</th>
                                                    <th>Sale Date</th>
                                                    <th>Type</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($lot->sales as $sale)
                                                <tr>
                                                    <td class="fw-bold">{{ $sale->customer_name }}</td>
                                                    <td>{{ $sale->quantity }}</td>
                                                    <td>{{ $sale->weight ?? '-' }}</td>
                                                    <td>{{ number_format($sale->price, 2) }}</td>
                                                    <td class="text-success fw-bold">
                                                        {{ number_format(($sale->weight ?? $sale->quantity) * $sale->price, 2) }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M, Y') }}</td>
                                                    <td>
                                                        <span class="badge {{ $sale->customer_type == 'Credit' ? 'bg-danger' : 'bg-success' }}">
                                                            {{ $sale->customer_type }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <!-- Edit Button -->
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $sale->id }}" title="Edit Sale">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button
                                                                type="button"
                                                                class="btn btn-sm btn-outline-danger delete-sale"
                                                                data-lot-id="{{ $sale->lot_id }}"
                                                                data-sale-id="{{ $sale->id }}"
                                                                data-customer-id="{{ $sale->customer_id }}" title="Delete Sale">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal{{ $sale->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $sale->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form method="POST" action="{{ route('update.lot.sale') }}">
                                                            @csrf
                                                            <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                                                                <div class="modal-content border-0 shadow-lg">
                                                                <div class="modal-header" style="background: linear-gradient(90deg, #0f172a 0%, #1e293b 100%); color: white;">
                                                                    <h5 class="modal-title" id="editModalLabel{{ $sale->id }}">
                                                                        <i class="fas fa-edit me-2"></i> Edit Sale - {{ $sale->customer_name }}
                                                                    </h5>
                                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <!-- Current sold units (read‑only) -->
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Sold Units</label>
                                                                        <input type="number" class="form-control"
                                                                            value="{{ $sale->quantity }}" readonly>
                                                                    </div>
                                                                    <!-- Conditional Weight Field -->
                                                                    @if($sale->weight !== null)
                                                                    <div class="mb-3">
                                                                        <label for="weight" class="form-label">Weight (KG)</label>
                                                                        <input type="number" step="0.01" name="weight" class="form-control" value="{{ $sale->weight }}">
                                                                        <div class="form-text">Optional: Only applicable if weight-based sale.</div>
                                                                    </div>
                                                                    @endif
                                                                    <!-- NEW: Add Units -->
                                                                    <div class="mb-3">
                                                                        <label for="add_units" class="form-label">Add Units</label>
                                                                        <input type="number" name="add_units" id="add_units"
                                                                            class="form-control" value="0" min="0" required>
                                                                        <div class="form-text">
                                                                            Enter how many more units to sell (or 0 to leave unchanged).
                                                                        </div>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="price" class="form-label">Price</label>
                                                                        <input type="number" name="price" step="0.01"
                                                                            class="form-control" value="{{ $sale->price }}" required>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="sale_date" class="form-label">Sale Date</label>
                                                                        <input type="date" name="sale_date" class="form-control"
                                                                            value="{{ \Carbon\Carbon::parse($sale->sale_date)->format('Y-m-d') }}"
                                                                            required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Update Sale</button>
                                                                </div>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS for modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @include('admin_panel.include.footer_include')
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('.delete-sale').on('click', function() {
        const lotId = $(this).data('lot-id');
        const saleId = $(this).data('sale-id');
        const customerid = $(this).data('customer-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this sale!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request here
                $.ajax({
                    url: '{{ route("delete.sale") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        lot_id: lotId,
                        sale_id: saleId,
                        customerid: customerid
                    },
                    success: function(res) {
                        Swal.fire(
                            'Deleted!',
                            res.message,
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let error = xhr.responseJSON?.message || 'Something went wrong!';
                        Swal.fire('Error', error, 'error');
                    }
                });
            }
        });
    });
</script>