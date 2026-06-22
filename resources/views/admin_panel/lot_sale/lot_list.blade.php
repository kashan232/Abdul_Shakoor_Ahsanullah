@include('admin_panel.include.header_include')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('assets/global/css/urdu-keyboard.css') }}">
<script src="{{ asset('assets/global/js/urdu-keyboard.js') }}"></script>

<body>
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        @include('admin_panel.include.navbar_include')
        <!-- Bootstrap alert container -->
        <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCustomerModalLabel">Add Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addCustomerForm">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Name (English)</label>
                                <input type="text" id="customer_name_en" name="customer_name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Urdu Name (Click to Type)</label>
                                <input type="text" id="customer_name_urdu" name="customer_name_urdu" class="form-control urdu-input" placeholder="مثلاً: محمد فیضان">
                            </div>
                            <div class="form-group">
                                <label>Mobile</label>
                                <input type="text" class="form-control" name="customer_phone">
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" class="form-control" name="city">
                            </div>
                            <div class="form-group">
                                <label>Area</label>
                                <input type="text" class="form-control" name="area">
                            </div>
                            <div class="form-group">
                                <label>Opening Balance</label>
                                <input type="number" step="0.01" class="form-control" name="opening_balance" placeholder="0.00">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- New Sale Entry Modal -->
        <div class="modal fade" id="addSaleEntryModal" tabindex="-1" aria-labelledby="addSaleEntryModalLabel" aria-hidden="true" style="z-index: 1055;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSaleEntryModalLabel">Add Entry to Sale</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="modal_lot_id">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Sale Category</label>
                            <select id="modal_sale_category" class="form-select bg-light fw-bold" onchange="toggleModalCustomerInput()">
                                <option value="credit" selected>CREDIT SALE (Udhaar)</option>
                                <option value="cash">CASH SALE (Naqad)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lot Description</label>
                            <input type="text" id="modal_lot_desc" class="form-control" readonly tabindex="-1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Search Customer (Name)</label>
                            <div class="input-group">
                                <input type="text" id="modal_customer_search" class="form-control" list="customerListData" placeholder="Start typing name..." autocomplete="off">
                                <datalist id="customerListData">
                                    @foreach($customers->sortBy('customer_name') as $customer)
                                    <option value="{{ $customer->customer_name }}"></option>
                                    @endforeach
                                </datalist>
                                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#addCustomerModal" tabindex="-1">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Sale Type</label>
                                <select id="modal_sale_type" class="form-select" onchange="toggleModalWeightInput()">
                                    <option value="unit">Unit</option>
                                    <option value="kg">Weight (KG)</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Stock</label>
                                <input type="text" id="modal_available" class="form-control" readonly tabindex="-1" style="color:red; font-weight:bold;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Units (Quantity)</label>
                                <input type="number" id="modal_quantity" class="form-control" min="1" step="any" oninput="calculateModalAmount()">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Rate (Price)</label>
                                <input type="number" id="modal_price" class="form-control" min="0" step="any" oninput="calculateModalAmount()">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Weight (optional)</label>
                            <input type="number" id="modal_weight" class="form-control" disabled step="any" oninput="calculateModalAmount()">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="text" id="modal_amount" class="form-control fw-bold text-success" readonly tabindex="-1" value="0.00">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" onclick="confirmAddSaleItem()">Add Entry (Enter)</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Finish & Close</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                <div class="d-flex mb-4 flex-wrap gap-3 justify-content-between align-items-center">
                    <h4 class="fw-bold text-primary">Lots Available for Sale</h4>
                </div>
                <div class="card shadow-lg p-4">
                    <div class="card-body">
                        <div id="alertContainer" class="mt-3"></div>

                        <form id="saleForm">
 <!-- changed to id saleForm for JS -->
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="customer_type" class="form-label">Customer Type</label>
                                    <select class="form-control" id="customer_type" required>
                                        <option value="" selected disabled>Select Type</option>
                                        <option value="credit">Credit Customer</option>
                                        <option value="cash">Cash Customer</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3" id="customer_select" style="display: none;">
                                    <label for="customer" class="form-label">Select Customer</label>
                                    <div class="d-flex">
                                        <select id="customer" class="form-select select2-basic">
                                            <option value="" selected disabled>Select Customer</option>
                                            @foreach($customers->sortBy('customer_name') as $customer)
                                            <option value="{{ $customer->id }}" data-number="{{ $customer->customer_phone }}">
                                                {{ $customer->customer_name }}
                                            </option>
                                            @endforeach
                                        </select>

                                        <button type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3" id="customer_number_container" style="display: none;">
                                    <label for="customer_number" class="form-label">Customer Number</label>
                                    <input type="text" class="form-control" id="customer_number" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="sale_date" class="form-label">Sale Date</label>
                                    <input type="date" class="form-control" id="sale_date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <h4 class="fw-bold text-primary mt-4">Lots Available for Sale (Truck: {{ $truck->truck_number }})</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Variety</th>
                                            <th>Unit</th>
                                            <th>Unit In</th>
                                            <th>Total Units</th>
                                            <th>Available Units</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lots as $lot)
                                        <tr>
                                            <td>{{ $lot->category }}</td>
                                            <td>{{ $lot->variety }}</td>
                                            <td>{{ $lot->unit }}</td>
                                            <td>{{ $lot->unit_in }}</td>
                                            <td>{{ $lot->total_units }}</td>
                                            <td>{{ $lot->lot_quantity }}</td>
                                            <td>
                                                @if($lot->lot_quantity > 0)
                                                <button type="button" class="btn btn-success btn-sm"
                                                    onclick="addSaleRow({{ $lot->id }}, '{{ $lot->category }}', '{{ $lot->variety }}', '{{ $lot->unit }}', {{ $lot->lot_quantity }})">
                                                    Add to Sale
                                                </button>
                                                @else

                                                <span class="btn btn-danger text-white btn-sm">Out of Stock</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <h4 class="fw-bold text-primary mt-4">Sale Details</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="saleTable">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th> <!-- NEW column -->
                                            <th>Sale Type</th>
                                            <th>Available Units</th>
                                            <th>Sale Units</th>
                                            <th>Rate</th>
                                            <th>Weight</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <h5 class="fw-bold">Sub Total: <span id="subtotal">0</span></h5>
                                <button type="button" class="btn btn-primary" id="submitSale">
                                    <i class="fas fa-check-circle me-1"></i> Submit Sale
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin_panel.include.footer_include')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
// Removed translitMap logic in favor of Visual Urdu Keyboard
</script>
    <script>
    // Map for high-speed customer lookup (Safe JSON)
    const customerMap = @json($customers->pluck('id', 'customer_name'));

    // ---------- Helper: get top-selected customer id & name ----------
    function getTopCustomerInfo() {
        const cust = document.getElementById('customer');
        if (!cust) return { id: null, name: '' };
        let id = cust.value || null;
        let name = '';

        // Try selectedOptions first (modern)
        if (cust.selectedOptions && cust.selectedOptions.length) {
            name = cust.selectedOptions[0].text.trim();
        } else if (cust.options && cust.selectedIndex >= 0) {
            name = cust.options[cust.selectedIndex].text.trim();
        } else if (window.jQuery) {
            // fallback for select2 or other
            name = $('#customer option:selected').text().trim();
            id = $('#customer').val() || id;
        }

        // if default placeholder selected (disabled), make empty
        if (name === 'Select Customer' || name === '-- Select Customer --' || name === '') name = '';

        return { id, name };
    }

    function getModalCustomerInfo() {
        const cust = document.getElementById('modal_customer');
        if (!cust) return { id: null, name: '' };
        let id = cust.value || null;
        let name = '';

        if (cust.selectedOptions && cust.selectedOptions.length) {
            name = cust.selectedOptions[0].text.trim();
        } else if (cust.options && cust.selectedIndex >= 0) {
            name = cust.options[cust.selectedIndex].text.trim();
        }

        if (name === 'Select Customer') name = '';
        return { id, name };
    }
    
    </script>


    <script>
        // keep original jQuery add-customer form behavior
        $(document).ready(function() {
            $("#addCustomerForm").submit(function(e) {
                e.preventDefault();
                let $form = $(this);
                let $btn = $form.find('button[type="submit"]');

                $btn.prop('disabled', true).text('Processing...');

                $.ajax({
                    url: "{{ route('store-customer') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    data: $form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Save message to show after reload
                            sessionStorage.setItem('customer_success_message', response.message);
                            $("#addCustomerModal").modal("hide");
                            $form[0].reset();
                            
                            // Refresh page to update dropdowns
                            window.location.reload(); 
                        } else {
                            iziToast.error({ title: 'Error', message: response.message || 'Error creating customer', position: 'topRight' });
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                        let errorMsg = 'Could not save customer.';
                        if (errors) {
                            errorMsg = Object.values(errors).flat().join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        iziToast.error({ title: 'Error', message: errorMsg, position: 'topRight' });
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text('Submit');
                    }
                });
            });
        });
    </script>

    <script>
        // Show/hide customer select based on customer_type (original logic)
        document.getElementById('customer_type').addEventListener('change', function() {
            let type = this.value;
            let customerSelect = document.getElementById('customer_select');
            let customerNumberContainer = document.getElementById('customer_number_container');

            if (type === 'credit') {
                customerSelect.style.display = 'block';
                customerNumberContainer.style.display = 'block';
            } else {
                customerSelect.style.display = 'none';
                customerNumberContainer.style.display = 'none';
            }
        });

        // keep customer number update
        document.getElementById('customer').addEventListener('change', function() {
            let selectedOption = this.options[this.selectedIndex];
            document.getElementById('customer_number').value = selectedOption.dataset.number || '';
        });

        // ---------- Modal Logic: Open Modal and Fill Data ----------
        function addSaleRow(id, category, variety, unit, lotQuantity) {
            // Calculate remaining stock by subtracting what's already in the temporary sale table
            let committedQty = 0;
            document.querySelectorAll(`#saleTable tbody tr[data-lot-id="${id}"]`).forEach(row => {
                committedQty += parseFloat(row.querySelector(".quantity").value) || 0;
            });
            
            let remainingStock = lotQuantity - committedQty;

            // Fill Modal Fields
            document.getElementById('modal_lot_id').value = id;
            document.getElementById('modal_lot_desc').value = `${category} - ${variety} (${unit})`;
            document.getElementById('modal_available').value = remainingStock;
            
            // Pre-select global customer if one is picked
            const topCust = getTopCustomerInfo();
            const searchInput = document.getElementById('modal_customer_search');

            if (topCust.id && topCust.name) {
                searchInput.value = topCust.name;
            } else {
                searchInput.value = '';
            }

            // Reset inputs
            document.getElementById('modal_quantity').value = '';
            document.getElementById('modal_price').value = '';
            document.getElementById('modal_weight').value = '';
            document.getElementById('modal_amount').value = '0.00';
            document.getElementById('modal_sale_type').value = 'unit';
            document.getElementById('modal_weight').disabled = true;

            // Show Modal (JQuery style for better compatibility)
            $('#addSaleEntryModal').modal('show');


            // Auto-focus on search input after modal opens
            setTimeout(function() {
                searchInput.focus();
                searchInput.select();
            }, 500);
        }

        // Keydown support for Modal (Enter to add)
        $(document).on('keydown', '#addSaleEntryModal', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'BUTTON' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                confirmAddSaleItem();
            }
        });

        function toggleModalWeightInput() {
            let type = document.getElementById("modal_sale_type").value;
            let weightInput = document.getElementById("modal_weight");
            if (weightInput) {
                weightInput.disabled = (type === "unit");
                if (type === "unit") weightInput.value = "";
            }
            calculateModalAmount();
        }

        function calculateModalAmount() {
            let saleType = document.getElementById("modal_sale_type").value;
            let price = parseFloat(document.getElementById("modal_price").value) || 0;
            let quantity = parseFloat(document.getElementById("modal_quantity").value) || 0;
            let weight = parseFloat(document.getElementById("modal_weight").value) || 0;
            
            let amount = 0;
            if (saleType === "unit") {
                amount = quantity * price;
            } else {
                amount = weight * price;
            }
            
            document.getElementById("modal_amount").value = amount.toFixed(2);
        }

        function toggleModalCustomerInput() {
            let cat = document.getElementById("modal_sale_category").value;
            let searchInput = document.getElementById("modal_customer_search");
            if (cat === "cash") {
                searchInput.value = "";
                searchInput.disabled = true;
                searchInput.placeholder = "Not needed for Cash Sale";
            } else {
                searchInput.disabled = false;
                searchInput.placeholder = "Start typing name...";
                searchInput.focus();
            }
        }

        function confirmAddSaleItem() {
            const lotId = document.getElementById('modal_lot_id').value;
            const searchName = document.getElementById('modal_customer_search').value.trim();
            const custId = customerMap[searchName]; // Lookup ID
            const perRowSaleCategory = document.getElementById('modal_sale_category').value;
            
            const saleType = document.getElementById('modal_sale_type').value;
            const availableUnits = parseInt(document.getElementById('modal_available').value);
            const quantity = parseFloat(document.getElementById('modal_quantity').value) || 0;
            const price = parseFloat(document.getElementById('modal_price').value) || 0;
            const weight = parseFloat(document.getElementById('modal_weight').value) || null;
            const amount = parseFloat(document.getElementById('modal_amount').value) || 0;

            // Validation Logic
            if (perRowSaleCategory === 'credit' && !custId) {
                iziToast.warning({ title: 'Wait', message: 'Credit sale requires a registered customer.' });
                document.getElementById('modal_customer_search').focus();
                return;
            }
            
            // For Cash sale, use name or default
            let displayName = searchName || (perRowSaleCategory === 'cash' ? 'Cash Customer' : '');
            let finalCustId = custId || ""; // Store as empty string if null

            if (quantity <= 0) {
                iziToast.warning({ title: 'Wait', message: 'Quantity is required' });
                document.getElementById('modal_quantity').focus();
                return;
            }
            if (quantity > availableUnits) {
                iziToast.warning({ title: 'Stock Error', message: 'Not enough units in stock' });
                return;
            }
            if (price <= 0) {
                iziToast.warning({ title: 'Wait', message: 'Price is required' });
                document.getElementById('modal_price').focus();
                return;
            }

            // Add Row to Table
            let table = document.querySelector("#saleTable tbody");
            let row = document.createElement("tr");
            row.setAttribute("data-lot-id", lotId);

            row.innerHTML = `
                <td>
                    <div class="row-info small text-muted mb-1">${perRowSaleCategory.toUpperCase()}</div>
                    <input type="text" class="form-control" value="${displayName}" readonly style="width:200px;">
                    <input type="hidden" class="customer_id" value="${finalCustId}">
                    <input type="hidden" class="customer_type_row" value="${perRowSaleCategory}">
                </td>
                <td>
                    <input type="text" class="form-control" value="${saleType === 'unit' ? 'Unit' : 'KG'}" readonly style="width:100px;">
                    <input type="hidden" class="sale-type" value="${saleType}">
                </td>
                <td>${availableUnits}</td>
                <td><input type="number" class="form-control quantity" value="${quantity}" readonly style="width:100px;"></td>
                <td><input type="number" class="form-control price" value="${price}" readonly style="width:100px;"></td>
                <td><input type="number" class="form-control weight" value="${weight || ''}" readonly style="width:100px;"></td>
                <td class="amount" style="width:150px;">${amount.toFixed(2)}</td>
                <td><button class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
            `;

            table.appendChild(row);
            updateSubtotal();

            // Real-time stock reduction in modal
            let currentInModalField = parseFloat(document.getElementById('modal_available').value) || 0;
            document.getElementById('modal_available').value = (currentInModalField - quantity);

            // Feedback and Reset for next entry
            iziToast.success({ title: 'Added', message: `${quantity} units added for ${searchName}`, position: 'topRight', timeout: 1500 });
            
            // Clear inputs for next entry, KEEP Modal open
            document.getElementById('modal_customer_search').value = '';
            document.getElementById('modal_quantity').value = '';
            document.getElementById('modal_price').value = '';
            document.getElementById('modal_weight').value = '';
            document.getElementById('modal_amount').value = '0.00';
            
            // Refocus back to customer search
            document.getElementById('modal_customer_search').focus();
        }

        function updateSubtotal() {
            let subtotal = 0;
            document.querySelectorAll("#saleTable tbody tr").forEach(row => {
                let amount = parseFloat(row.querySelector(".amount").innerText) || 0;
                subtotal += amount;
            });
            document.querySelector("#subtotal").innerText = subtotal.toFixed(2);
        }

        function removeRow(button) {
            let row = button.closest("tr");
            row.remove();
            updateSubtotal();
        }
    </script>

    <script>
        // ---------- UPDATED submit handler: include row-specific customer_id ----------
        document.getElementById("submitSale").addEventListener("click", function(event) {
            event.preventDefault();

            let salesData = [];
            const topCustomerSelect = document.getElementById('customer');
            const topCustomerId = topCustomerSelect ? topCustomerSelect.value : null;

            document.querySelectorAll("#saleTable tbody tr").forEach(row => {
                let saleType = row.querySelector(".sale-type").value;
                let quantity = parseFloat(row.querySelector(".quantity").value) || 0;
                let price = parseFloat(row.querySelector(".price").value) || 0;
                let lotId = row.getAttribute("data-lot-id");
                let weight = parseFloat(row.querySelector(".weight").value) || null;
                let customerId = row.querySelector(".customer_id").value;
                let customerTypeRow = row.querySelector(".customer_type_row").value;

                if (lotId) {
                    salesData.push({
                        customer_id: (customerId && customerId !== "null" && customerId !== "") ? parseInt(customerId) : null,
                        customer_type: customerTypeRow,
                        lot_id: lotId,
                        quantity: quantity,
                        price: price,
                        weight: weight
                    });
                }
            });

            let saleDate = document.getElementById("sale_date").value;
            let customerType = document.getElementById("customer_type").value;

            // Disable button and show loading state
            const btn = document.getElementById("submitSale");
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...`;

            fetch("{{ route('lot.sale.store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        customer_type: customerType,
                        sale_date: saleDate,
                        sales: salesData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        sessionStorage.setItem('sale_success_message', data.message);
                        location.reload();
                    } else {
                        // Re-enable button on error
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Something went wrong' });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    // Re-enable button on error
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.' });
                });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Check for sale success
            let saleMessage = sessionStorage.getItem('sale_success_message');
            if (saleMessage) {
                iziToast.success({ title: 'Success', message: saleMessage, position: 'topRight' });
                sessionStorage.removeItem('sale_success_message');
            }

            // Check for customer success
            let customerMessage = sessionStorage.getItem('customer_success_message');
            if (customerMessage) {
                iziToast.success({ title: 'Success', message: customerMessage, position: 'topRight' });
                sessionStorage.removeItem('customer_success_message');
            }
        });
    </script>
</body>
