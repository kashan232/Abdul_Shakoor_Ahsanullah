@include('admin_panel.include.header_include')
<meta name="csrf-token" content="{{ csrf_token() }}">

<body>
<div class="page-wrapper default-version">
@include('admin_panel.include.sidebar_include')
@include('admin_panel.include.navbar_include')
<style>
.table-readonly {
    background: #f8f9fa;
}
.table-readonly th {
    background: #e9ecef;
    font-weight: 600;
}
.table-readonly td {
    color: #495057;

}
</style>
    <style>
.table-input th {
    background: #f1f3f5;
    font-weight: 600;
}
.table-input input,
.table-input select {
    background: #ffffff;
    border-radius: 6px;
}
.table-input input:focus,
.table-input select:focus {
    border-color: #4dabf7;
    box-shadow: 0 0 0 0.15rem rgba(77,171,247,.25);
}
</style>
<style>
.table-mazdori {
    background: #fff8e1;
}
.table-mazdori th {
    background: #ffecb3;
    font-weight: 600;
}
</style>
<style>
.table-expense {
    background: #fff5f5;
}
.table-expense th {
    background: #ffe3e3;
    font-weight: 600;
}
</style>

<div class="body-wrapper">
<div class="bodywrapper__inner">

<h4 class="fw-bold text-primary mb-4">Create Bill For Vendor</h4>

<div class="card shadow-lg p-4">
<div class="card-body">

{{-- ❌ NO DEFAULT FORM SUBMIT --}}
<form id="billForm" onsubmit="return false;">
@csrf

{{-- BILL DATE --}}
<div class="mb-3">
<label>Bill Date</label>
<input type="date" class="form-control" name="bill_date" id="bill_date" required>
</div>

<h5 class="fw-bold text-primary mt-4 d-flex gap-3 align-items-center">
    <span>
        🚚 Truck:
        <span class="text-dark">{{ $truck->truck_number }}</span>
    </span>

    <span class="badge bg-light text-dark border">
         Vendor:
        <strong>{{ $truck->vendor_id }}</strong>
    </span>
</h5>

<input type="hidden" name="truck_id" value="{{ $truck->id }}">
<input type="hidden" name="truck_number" value="{{ $truck->truck_number }}">
<input type="hidden" name="vendor_id" value="{{ $vendor_id }}">

{{-- LOT LIST --}}
<div class="table-responsive mt-3">
<table class="table table-striped table-readonly">
<thead>
<tr>
<th>Category</th>
<th>Variety</th>
<th>Unit</th>
<th>Unit In</th>
<th>Lot Units</th>
<th>Total Weight</th>
<th>Avg Sale</th>
<th>Total Sale</th>
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
<td>{{ $lot->total_weight }}</td>
<td>{{ number_format($lot->average_sale,2) }}</td>
<td>{{ number_format($lot->total_sale,2) }}</td>
<td>
<button type="button" class="btn btn-sm btn-primary"
onclick="addBillRow({{ $lot->id }}, '{{ $lot->category }}', '{{ $lot->variety }}', '{{ $lot->unit }}', {{ $lot->total_units }}, '{{ $lot->unit_in }}', {{ $lot->total_weight }})">
Add
</button>
</td>
</tr>
@endforeach
</tbody>
<tfoot class="table-light fw-bold">
<tr>
    <td colspan="4" class="text-end">TOTAL</td>
    <td>{{ $lots->sum('total_units') }}</td>
    <td>{{ $lots->sum('total_weight') }}</td>
    <td>-</td>
    <td>{{ number_format($lots->sum('total_sale'), 2) }}</td>
    <td></td>
</tr>
</tfoot>
</table>
</div>

{{-- BILL DETAILS --}}
<h5 class="fw-bold text-primary mt-4">Bill Details</h5>

<div class="table-responsive">
<table class="table table-bordered table-input" id="billTable">
<thead>
<tr>
<th>Item</th>
<th>Lot Units</th>
<th>Weight</th>
<th>Sale Type</th>
<th>Sale Units</th>
<th>Rate</th>
<th>Amount</th>
<th>Action</th>
</tr>
</thead>
<tbody></tbody>
<tfoot class="table-secondary fw-bold">
<tr>
    <td class="text-end">TOTAL</td>
    <td id="footerTotalUnits">0</td>
    <td>-</td>
    <td>-</td>
    <td id="footerSaleUnits">0</td>
    <td>-</td>
    <td id="footerAmount">0</td>
    <td></td>
</tr>
</tfoot>
</table>
</div>

<div class="alert alert-secondary py-2">

    <strong>Sub Total:</strong>
    <span class="float-end" id="subtotal">0</span>
</div>

<hr>

{{-- MAZDORI --}}
<h5 class="fw-bold text-primary">Mazdori</h5>

<table class="table table-bordered table-mazdori" id="mazdoriTable">
<thead>
<tr>
<th>Unit In</th>
<th>Units</th>
<th>Rate</th>
<th>Total</th>
<th>Action</th>
</tr>
</thead>
<tbody></tbody>
</table>

<div class="alert alert-warning py-2">
    <strong>Total Mazdori:</strong>
    <span class="float-end" id="totalMazdori">0</span>
</div>

<hr>

{{-- EXPENSES --}}
<h5 class="fw-bold text-primary">Expenses</h5>

<button type="button" class="btn btn-sm btn-success mb-2" onclick="addExpenseRow(); return false;">
Add Expense
</button>

<table class="table table-bordered table-expense" id="expenseTable">
<thead>
<tr>
<th>Type</th>
<th>Value</th>
<th>Final</th>
<th>Action</th>
</tr>
</thead>
<tbody></tbody>
</table>

<h6>Total Expenses: <span id="totalExpense">0</span></h6>
<div class="d-flex gap-2 mt-2">
<select id="adjustment_type"
        class="form-control"
        style="width:60px;">
<option value="+">+</option>
<option value="-">-</option>
</select>
<input type="number"
       id="adjustment"
       class="form-control text-end"
       placeholder="Adjustment"
       style="max-width:140px;">
</div>
<div class="alert alert-success py-3 fs-5">
    <strong>Net Pay to Vendor:</strong>
    <span class="float-end" id="netPay">0</span>
</div>



{{-- SAVE --}}
<button type="button" class="btn btn-primary mt-4" id="submitBill">
    <i class="fas fa-check-circle me-1"></i> Save Bill
</button>

</form>
</div>
</div>

</div>
</div>
</div>

@include('admin_panel.include.footer_include')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let lotTracker = {};
let mazdoriTracker = {};

function addBillRow(id, category, variety, unit, total_units, unit_in, total_weight) {

    if (!lotTracker[id]) {
        lotTracker[id] = {
            total: total_units,
            used: 0
        };
    }

    const tbody = document.querySelector('#billTable tbody');
    const tr = document.createElement('tr');

    tr.dataset.lot = id;
    tr.dataset.total = total_units;

    tr.innerHTML = `
        <td>
            ${category} - ${variety} (${unit})
            <input type="hidden" class="lot-id" value="${id}">
        </td>
        <td>${total_units}</td>
        <td>
            <input type="number" class="form-control weight" value="${total_weight}" min="0">
        </td>
        <td>
            <select class="form-control calc-type">
                <option value="unit">Unit</option>
                <option value="kg">KG</option>
            </select>
        </td>
        <td>
            <input type="number" class="form-control sale-units" min="1">
        </td>
        <td>
            <input type="number" class="form-control rate" min="1">
        </td>
        <td>
            <input type="number" class="form-control amount" readonly>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger"
                onclick="removeRow(this)">
                Remove
            </button>
        </td>
    `;

    tbody.appendChild(tr);

    tr.querySelectorAll('.sale-units, .rate, .weight, .calc-type')
        .forEach(el => el.addEventListener('input', () => calculateRow(tr)));
        
        addMazdoriRow(id, total_units, unit_in);
calculateMazdoriTotal();
updateBillFooter();
}



function calculateRow(row) {

    const lotId = row.querySelector('.lot-id').value;
    const totalUnits = lotTracker[lotId].total;
    const saleUnits = parseFloat(row.querySelector('.sale-units').value) || 0;
    const rate = parseFloat(row.querySelector('.rate').value) || 0;
    const weight = parseFloat(row.querySelector('.weight').value) || 0;
    const type = row.querySelector('.calc-type').value;

    // 🔥 Recalculate used units
    let used = 0;
    document.querySelectorAll('#billTable tbody tr').forEach(r => {
        if (r.querySelector('.lot-id')?.value === lotId) {
            used += parseFloat(r.querySelector('.sale-units')?.value) || 0;
        }
    });

    if (used > totalUnits) {
        Swal.fire(
            'Units Exceeded',
            `Lot total units = ${totalUnits}, you entered ${used}`,
            'error'
        );
        row.querySelector('.sale-units').value = '';
        return;
    }

    let amount = (type === 'kg')
        ? weight * rate
        : saleUnits * rate;

    row.querySelector('.amount').value = Math.round(amount);
    calculateTotal();
    updateBillFooter();
}

function removeRow(btn) {
    btn.closest('tr').remove();
    calculateTotal();
    updateBillFooter();

}
function addMazdoriRow(lotId, totalUnits, unitIn) {

    if (mazdoriTracker[lotId]) return;

    mazdoriTracker[lotId] = true;

    const tbody = document.querySelector('#mazdoriTable tbody');
    const tr = document.createElement('tr');
    tr.dataset.lot = lotId;

    tr.innerHTML = `
        <td>${unitIn}</td>
        <td>${totalUnits}</td>
        <td>
            <input type="number"
                   class="form-control mazdori-rate"
                   data-lot="${lotId}"
                   min="0">
        </td>
        <td>
            <input type="number"
                   class="form-control mazdori-total"
                   readonly>
        </td>
        <td>
            <button type="button"
                    class="btn btn-sm btn-danger"
                    onclick="removeMazdoriRow(${lotId})">
                Remove
            </button>
        </td>
    `;

    tbody.appendChild(tr);

    tr.querySelector('.mazdori-rate')
        .addEventListener('input', calculateMazdori);
}
function calculateMazdori() {

    let total = 0;

    document.querySelectorAll('#mazdoriTable tbody tr').forEach(row => {

        const rate = parseFloat(
            row.querySelector('.mazdori-rate')?.value
        ) || 0;

        const units = parseFloat(row.children[1].innerText) || 0;

        const amount = rate * units;

        row.querySelector('.mazdori-total').value = Math.round(amount);

        total += amount;
    });

    document.getElementById('totalMazdori').innerText = Math.round(total);
    calculateExpenses();
}
function removeMazdoriRow(lotId) {
    document.querySelector(`#mazdoriTable tr[data-lot="${lotId}"]`)?.remove();
    delete mazdoriTracker[lotId];
    calculateMazdori();
}
   function copyMazdori() {
            const value = document.getElementById('totalMazdori').innerText;
            navigator.clipboard.writeText(value).then(() => {}).catch(err => {});
        }
        function handleTypeChange(select) {
    const row   = select.closest('tr');
    const input = row.querySelector('.expense-input');
    const final = row.querySelector('.expense-final');

    if (select.value === "Mazdori") {
        const totalMazdori = parseFloat(
            document.getElementById('totalMazdori').innerText
        ) || 0;

        input.value = totalMazdori;
        final.value = totalMazdori;
    }
    else {
        input.value = '';
        final.value = '';
    }

    calculateExpenses();
}
function addExpenseRow() {

    const tbody = document.querySelector('#expenseTable tbody');
    const tr = document.createElement('tr');

    tr.innerHTML = `
        <td>
            <select class="form-control expense-type" onchange="handleTypeChange(this)">
                <option value="">Select</option>
                <option value="Mazdori">Mazdori</option>
                <option value="Commission">Commission (%)</option>
                <option value="Rent">Rent</option>
                <option value="Market Tax">Market Tax</option>
            </select>
        </td>
        <td>
            <input type="number"
                   class="form-control expense-input">
        </td>
        <td>
            <input type="number"
                   class="form-control expense-final"
                   readonly>
        </td>
        <td>
            <button type="button"
                    class="btn btn-sm btn-danger"
                    onclick="this.closest('tr').remove(); calculateExpenses();">
                Remove
            </button>
        </td>
    `;

    tbody.appendChild(tr);

    tr.querySelector('.expense-type').addEventListener('change', () => calculateExpenses());
    tr.querySelector('.expense-input').addEventListener('input', () => calculateExpenses());
}
function calculateExpenses() {
    const subtotal = parseFloat(document.getElementById('subtotal').textContent) || 0;

    let totalExpense = 0;

    document.querySelectorAll('#expenseTable tbody tr').forEach(row => {
        const type = row.querySelector('.expense-type')?.value || '';
        const value = parseFloat(row.querySelector('.expense-input')?.value) || 0;

        let final = 0;

        if (type === 'Commission') {
            final = subtotal * (value / 100);
        } else {
            final = value;
        }

        row.querySelector('.expense-final').value = Math.round(final);
        totalExpense += final;
    });

    document.getElementById('totalExpense').textContent = Math.round(totalExpense);

let adjustment = parseFloat(document.getElementById('adjustment').value);
if (isNaN(adjustment)) adjustment = 0;

if (document.getElementById('adjustment_type').value === '-') {
    adjustment = -adjustment;
}



    const netPay = subtotal - totalExpense + adjustment;

    document.getElementById('netPay').textContent = Math.round(netPay);
    document.getElementById('net_pay_to_vendor').value = Math.round(netPay);
}

function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.amount').forEach(a => {
        total += parseFloat(a.value) || 0;
    });

    document.getElementById('subtotal').innerText = Math.round(total);
    calculateMazdoriTotal();
    calculateExpenses();
}

const billViewUrl = "{{ route('bill-book', ':id') }}";

/* ================= BILL SAVE ================= */
document.getElementById('submitBill').addEventListener('click', function () {

let lotCheck = {};

document.querySelectorAll('#billTable tbody tr').forEach(row => {
    const lotId = row.querySelector('.lot-id').value;
    const units = parseFloat(row.querySelector('.sale-units').value) || 0;

    if (!lotCheck[lotId]) lotCheck[lotId] = 0;
    lotCheck[lotId] += units;
});

for (let lotId in lotCheck) {

    const totalAllowed = document.querySelector(
        `#billTable tr[data-lot="${lotId}"]`
    )?.dataset.total;

    if (lotCheck[lotId] < totalAllowed) {
        Swal.fire(
            'Incomplete Lot',
            `Lot ${lotId} sale units are less than total units`,
            'error'
        );
        return; // ❌ STOP SAVE
    }
}

const dateInput = document.getElementById('bill_date').value;
if (!dateInput) {
Swal.fire('Required','Please select bill date','warning');
return;
}


let bills = [];
document.querySelectorAll('#billTable tbody tr').forEach(row => {
bills.push({
lot_id: row.dataset.lot,
sale_units: row.querySelector('.sale-units').value,
rate: row.querySelector('.rate').value,
amount: row.querySelector('.amount').value,
unit_in: row.dataset.unit,
weight: row.querySelector('.weight').value
});
});

if (bills.length === 0) {
Swal.fire('Empty','Add at least one bill row','warning');
return;
}

let expenses = [];
document.querySelectorAll('#expenseTable tbody tr').forEach(row => {
expenses.push({
category: row.querySelector('.expense-type').value,
value: row.querySelector('.expense-input').value,
final_amount: row.querySelector('.expense-final').value
});
});

const payload = {
bill_date: dateInput,
truck_id: document.querySelector('[name=truck_id]').value,
trucknumber: document.querySelector('[name=truck_number]').value,
vendorId: document.querySelector('[name=vendor_id]').value,
subtotal: document.getElementById('subtotal').innerText,
total_expense: document.getElementById('totalExpense').innerText,
net_pay: document.getElementById('netPay').innerText,
adjustment: document.getElementById('adjustment').value,
bill_details: bills,
expenses: expenses
};

// Disable button and show loading state
const btn = document.getElementById('submitBill');
const originalHtml = btn.innerHTML;
btn.disabled = true;
btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...`;

fetch("{{ route('vendor.bill.store') }}",{
method:'POST',
headers:{
'Content-Type':'application/json',
'X-CSRF-TOKEN':'{{ csrf_token() }}'
},
body:JSON.stringify(payload)
})
.then(res=>res.json())
.then(data=>{
if(data.success){
Swal.fire('Saved',data.message,'success');
setTimeout(()=>{
window.location.href = billViewUrl.replace(':id', data.bill_id);
},500);
}else{
// Re-enable on error
btn.disabled = false;
btn.innerHTML = originalHtml;
Swal.fire('Error',data.message,'error');
}
})
.catch(()=>{
// Re-enable on error
btn.disabled = false;
btn.innerHTML = originalHtml;
Swal.fire('Error','Server error','error');
});
});

document.getElementById('adjustment')
    .addEventListener('input', calculateExpenses);

document.getElementById('adjustment_type')
    .addEventListener('change', calculateExpenses);

function updateBillFooter() {

    let totalSaleUnits = 0;
    let totalAmount = 0;

    document.querySelectorAll('#billTable tbody tr').forEach(row => {

        const saleUnits = parseFloat(row.querySelector('.sale-units')?.value) || 0;
        const amount = parseFloat(row.querySelector('.amount')?.value) || 0;

        totalSaleUnits += saleUnits;
        totalAmount += amount;
    });

    document.getElementById('footerSaleUnits').innerText = Math.round(totalSaleUnits);
    document.getElementById('footerAmount').innerText = Math.round(totalAmount);
}
</script>

</body>