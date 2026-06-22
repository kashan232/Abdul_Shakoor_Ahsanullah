@include('admin_panel.include.header_include')

<body>
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        @include('admin_panel.include.navbar_include')
        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                <div class="d-flex mb-30 flex-wrap gap-3 justify-content-between align-items-center">
                    <h6 class="page-title">Truck Entry</h6>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-primary text-white fw-semibold">
                        <i class="fas fa-truck me-2"></i> Add New Truck Entry
                    </div>
                    <div class="card-body">
                        @if (session()->has('success'))
                        <div class="alert alert-success">
                            <strong>Success!</strong> {{ session('success') }}.
                        </div>
                        @endif

                        <form action="{{ route('Truck-Entry.Store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <!-- Truck Number -->
                                <div class="col-md-4">
                                    <label class="form-label">Truck Number</label>
                                    <input type="text" name="truck_number" class="form-control" required>
                                </div>

                                <!-- Driver Name -->
                                <div class="col-md-4">
                                    <label class="form-label">Driver Name</label>
                                    <input type="text" name="driver_name" class="form-control" required>
                                </div>

                                <!-- Driver CNIC -->
                                <div class="col-md-4">
                                    <label class="form-label">Driver CNIC</label>
                                    <input type="text" name="driver_cnic" class="form-control">
                                </div>

                                <!-- Driver Contact -->
                                <div class="col-md-4 mt-2">
                                    <label class="form-label">Driver Contact</label>
                                    <input type="text" name="driver_contact" class="form-control">
                                </div>

                                <!-- Vendor (Party) -->
                                <div class="col-md-4 mt-2">
                                    <label class="form-label">Vendor (Party)</label>
                                    <select name="vendor_id" class="select2-basic form-control">
                                        <option value="">Select Vendor</option>
                                        @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->name }}">{{ $vendor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Entry Date -->
                                <div class="col-md-4 mt-2">
                                    <label class="form-label">Entry Date</label>
                                    <input type="date" name="entry_date" class="form-control" required>
                                </div>
                            </div>

                            <hr>

                            <!-- LOT ENTRY TABLE -->
                            <h5 class="fw-bold mt-3 mb-3 text-dark">Lot Details</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border" id="lotTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Category</th>
                                            <th>Variety</th>
                                            <th>Size</th>
                                            <th>Unit In</th>
                                            <th>Lot Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td >
                                                <select name="category[]" class="form-control">
                                                    <option value="">Select Category</option>
                                                    @foreach($categories as $category)
                                                    <option value="{{ $category->category }}">{{ $category->category }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="variety[]" class="form-control">
                                                    <option value="">Select Variety</option>
                                                    @foreach($varieties as $variety)
                                                    <option value="{{ $variety->brand }}">{{ $variety->brand }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="unit[]" class="form-control">
                                                    <option value="">Select Unit</option>
                                                    @foreach($Units as $Unit)
                                                    <option value="{{ $Unit->unit }}">{{ $Unit->unit }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="unit_in[]" class="form-control">
                                                    <option value="" disabled>Select Unit In</option>
                                                    @foreach($UnitIns as $Unitin)
                                                    <option value="{{ $Unitin->unit_in }}">{{ $Unitin->unit_in }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="lot_quantity[]" class="form-control">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger remove-row"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" class="btn btn--primary mt-3 mb-3" id="addMore"><i class="fas fa-plus"></i> Add More Lot</button>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-success w-100 py-2 fw-bold">Save Truck Entry</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('admin_panel.include.footer_include')

    <style>
        .bg-gradient-primary {
            background: linear-gradient(90deg, #0f172a 0%, #1e293b 100%) !important;
            color: #ffffff !important;
            border-bottom: 2px solid #22c55e !important;
        }
        .form-label {
            font-weight: 500;
            color: #333;
        }
        #lotTable {
            border-collapse: collapse !important;
            border-spacing: 0 !important;
        }
        #lotTable td, #lotTable th {
            padding: 10px !important;
            vertical-align: middle;
            border: 1px solid #dee2e6 !important;
            background: #fff;
        }
        #lotTable .form-control {
            min-width: 130px;
            margin: 0 !important;
            border-radius: 4px;
            border: 1px solid #ced4da;
        }
        #lotTable tr {
            box-shadow: none !important;
            background: none !important;
        }
    </style>

    <script>
        // Add More Lot Entry
        document.getElementById("addMore").addEventListener("click", function() {
            let table = document.getElementById("lotTable").getElementsByTagName("tbody")[0];
            let newRow = table.insertRow();

            newRow.innerHTML = `
                <td>
                    <select name="category[]" class="form-control">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category }}">{{ $category->category }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="variety[]" class="form-control">
                        <option value="">Select Variety</option>
                        @foreach($varieties as $variety)
                            <option value="{{ $variety->brand }}">{{ $variety->brand }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="unit[]" class="form-control">
                        <option value="">Select Unit</option>
                        @foreach($Units as $Unit)
                            <option value="{{ $Unit->unit }}">{{ $Unit->unit }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="unit_in[]" class="form-control">
                        <option value="" disabled>Select Unit In</option>
                        <option value="Bori">Bori</option>
                        <option value="Katta">Katta</option>
                        <option value="Jali">Jali</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="lot_quantity[]" class="form-control">
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-row"><i class="fas fa-trash"></i></button>
                </td>
            `;
        });

        // Remove Row
        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("remove-row")) {
                e.target.closest("tr").remove();
            }
        });
    </script>
</body>