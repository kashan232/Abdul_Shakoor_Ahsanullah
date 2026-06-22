
@if($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

@include('admin_panel.include.header_include')
<meta name="csrf-token" content="{{ csrf_token() }}">

<body>
  <div class="page-wrapper default-version">
    @include('admin_panel.include.sidebar_include')
    @include('admin_panel.include.navbar_include')

    <div class="body-wrapper">
      <div class="bodywrapper__inner">
        <div class="d-flex mb-4 flex-wrap gap-3 justify-content-between align-items-center">
          <h4 class="fw-bold text-primary">
            Lots Available for Bulk Sale (Truck: {{ $truck->truck_number }})
          </h4>
        </div>

        <div class="card shadow-lg p-4">
          <div class="card-body">
            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div id="alertContainer" class="mt-3"></div>

            <div class="row mb-3">
              <div class="col-md-4">
                <label for="global_customer_type" class="form-label">Customer Type</label>
                <select id="global_customer_type" class="form-select">
                  <option value="cash">Cash Customer</option>
                  <option value="credit">Credit Customer</option>
                </select>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Category</th>
                    <th>Variety</th>
                    <th>Unit</th>
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
                      <td>{{ $lot->total_units }}</td>
                      <td>{{ $lot->lot_quantity }}</td>
                      <td>
                        @if($lot->lot_quantity > 0)
                          <button
                            type="button"
                            class="btn btn-success btn-sm"
                            onclick="addSaleRow(
                              {{ $lot->id }},
                              '{{ addslashes($lot->category) }}',
                              '{{ addslashes($lot->variety) }}',
                              '{{ addslashes($lot->unit) }}',
                              {{ $lot->lot_quantity }}
                            )"
                          >
                            Add to Bulk Sale
                          </button>
                        @else
                          <span class="btn btn-danger btn-sm">Out of Stock</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <h4 class="fw-bold text-primary mt-4">Sale Details</h4>

            <form id="bulkSaleForm" method="POST" action="{{ route('lot.sale.bulk.store', ['truck_id' => $truck->id]) }}">
              @csrf

              <label for="sale_date" class="form-label">Sale Date</label>
              <input id="sale_date" name="sale_date" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>

              <table class="table table-bordered" id="saleTable">
                <thead>
                  <tr>
                    <th>Customer</th>
                    <th>Customer Type</th>
                    <th>Sale Type</th> <!-- single column (unit/weight) -->
                    <th>Available</th>
                    <th>Quantity</th>
                    <th>Weight</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>

              <div class="d-flex justify-content-between mt-4">
                <h5 class="fw-bold">
                  Sub Total: <span id="subtotal">0.00</span>
                </h5>
                <button type="submit" class="btn btn-primary" id="submitSale">
                  <i class="fas fa-check-circle me-1"></i> Submit Bulk Sale
                </button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>




@include('admin_panel.include.footer_include')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
let rowIdx = 0;
let lotSoldQuantities = {};

function addSaleRow(lotId, category, variety, unit, availableQty) {
  if (!lotSoldQuantities[lotId]) lotSoldQuantities[lotId] = 0;

  const row = document.createElement('tr');
  const customerOptions = `
    <option disabled selected>Select Customer</option>
    @foreach($customers as $c)
      <option value="{{ $c->id }}">{{ $c->customer_name }}</option>
    @endforeach
  `;
  const globalType = document.getElementById('global_customer_type').value;
  const remaining = availableQty - lotSoldQuantities[lotId];

  const saleTypeDropdown = `
    <select name="sales[${rowIdx}][sale_type]" class="form-select sale-type">
      <option value="unit">Sale in Unit</option>
      <option value="weight">Sale by Weight</option>
    </select>
  `;
  const weightInput = `
    <input type="number" name="sales[${rowIdx}][weight]" class="form-control weight-field" min="0.01" step="any" disabled>
  `;

  row.innerHTML = `
    <td>
      <select name="sales[${rowIdx}][customer_id]" class="form-select sale-customer" style="width: 200px;">
        ${customerOptions}
      </select>
    </td>
    <td>
      <input type="text" name="sales[${rowIdx}][customer_type]" class="form-control" value="${globalType}" readonly>
    </td>
    <td>${saleTypeDropdown}</td>
    <td class="available">${remaining}</td>
    <td>
      <input type="number" name="sales[${rowIdx}][quantity]" class="form-control quantity" data-lot="${lotId}" min="1" max="${remaining}">
    </td>
    <td>${weightInput}</td>
    <td>
      <input type="number" name="sales[${rowIdx}][rate]" class="form-control rate" min="0">
    </td>
    <td class="amount">0.00</td>
    <td>
      <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this, ${lotId})">×</button>
    </td>
    <input type="hidden" name="sales[${rowIdx}][lot_id]" value="${lotId}">
  `;

  document.querySelector('#saleTable tbody').appendChild(row);

  // Select2 on customer dropdown
  $(row).find('.sale-customer').select2({ dropdownParent: $('#bulkSaleForm') });

  attachEvents(row, lotId, availableQty);
  applyGlobalCustomerTypeToRow(row);
  rowIdx++;
}

function attachEvents(row, lotId, availableQty) {
  row.querySelector('.quantity').addEventListener('input', () => calculate(row, lotId, availableQty));
  row.querySelector('.rate').addEventListener('input', () => calculate(row, lotId, availableQty));
  row.querySelector('.sale-type').addEventListener('change', (e) => {
    toggleWeight(row, e.target.value);
    calculate(row, lotId, availableQty);
  });
  const weightEl = row.querySelector('.weight-field');
  if (weightEl) {
    weightEl.addEventListener('input', () => calculate(row, lotId, availableQty));
  }
}

function toggleWeight(row, mode) {
  const weightInput = row.querySelector('.weight-field');
  if (!weightInput) return;
  weightInput.disabled = (mode !== 'weight');
  if (weightInput.disabled) weightInput.value = '';
}

function calculate(row, lotId, availableQty) {
  const qtyEl    = row.querySelector('.quantity');
  const rateEl   = row.querySelector('.rate');
  const typeEl   = row.querySelector('.sale-type');
  const weightEl = row.querySelector('.weight-field');

  const qty    = parseFloat(qtyEl.value)  || 0;
  const rate   = parseFloat(rateEl.value) || 0;
  const weight = (!weightEl.disabled && weightEl) ? parseFloat(weightEl.value) || 0 : 0;

  let totalSold = 0;
  document.querySelectorAll(`input.quantity[data-lot="${lotId}"]`).forEach(el => {
    totalSold += parseFloat(el.value) || 0;
  });

  if (totalSold > availableQty) {
    alert('Total sold cannot exceed available units!');
    qtyEl.value = '';
    row.querySelector('.amount').innerText = '0.00';
    updateSubtotal();
    return;
  }

  const amount = (typeEl.value === 'weight') ? (rate * weight) : (rate * qty);
  row.querySelector('.amount').innerText = amount.toFixed(2);
  row.querySelector('.available').innerText = (availableQty - totalSold).toString();

  lotSoldQuantities[lotId] = totalSold;
  updateSubtotal();
}
function removeRow(btn, lotId) {
  btn.closest('tr').remove();

  let totalSold = 0;
  document.querySelectorAll(`input.quantity[data-lot="${lotId}"]`)
    .forEach(el => totalSold += (parseFloat(el.value) || 0));

  lotSoldQuantities[lotId] = totalSold;
  updateSubtotal();
}

function updateSubtotal() {
  let sum = 0;
  document.querySelectorAll('#saleTable tbody tr .amount').forEach(el => {
    sum += (parseFloat(el.innerText) || 0);
  });
  document.getElementById('subtotal').innerText = sum.toFixed(2);
}

// Apply global customer type to all existing rows
function applyGlobalCustomerTypeToAllRows() {
  const type = document.getElementById('global_customer_type').value;

  document.querySelectorAll('input[name$="[customer_type]"]').forEach(inp => {
    inp.value = type;
  });

  const isCash = (type === 'cash');
  document.querySelectorAll('select.sale-customer').forEach(sel => {
    sel.disabled = isCash;
    if (isCash) {
      sel.value = '';
      $(sel).trigger('change');
    }
  });
}

// Apply global type to just-added row
function applyGlobalCustomerTypeToRow(row) {
  const type = document.getElementById('global_customer_type').value;
  const inp = row.querySelector('input[name$="[customer_type]"]');
  if (inp) inp.value = type;

  const isCash = (type === 'cash');
  const sel = row.querySelector('select.sale-customer');
  if (sel) {
    sel.disabled = isCash;
    if (isCash) {
      sel.value = '';
      $(sel).trigger('change');
    }
  }
}

document.addEventListener('DOMContentLoaded', function () {
  $('.sale-customer').select2({
    dropdownParent: $('#bulkSaleForm'),
    width: 'resolve'
  });

  const gct = document.getElementById('global_customer_type');
  if (gct) {
    gct.addEventListener('change', applyGlobalCustomerTypeToAllRows);
  }

  document.getElementById('bulkSaleForm').addEventListener('submit', function(e) {
    const rows = document.querySelectorAll('#saleTable tbody tr');
    if (rows.length === 0) {
      e.preventDefault();
      alert('Please add at least one sale item before submitting.');
      return;
    }

    if (document.getElementById('global_customer_type').value === 'credit') {
      for (const row of rows) {
        const customerSelect = row.querySelector('select[name$="[customer_id]"]');
        if (!customerSelect || !customerSelect.value) {
          e.preventDefault();
          alert('Please select a customer for all credit sale items.');
          return;
        }
      }
    }

    // Disable button and show loading state if validation passed
    const btn = document.getElementById('submitSale');
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...`;
  });

  applyGlobalCustomerTypeToAllRows(); // On page load
});
</script>


</body>