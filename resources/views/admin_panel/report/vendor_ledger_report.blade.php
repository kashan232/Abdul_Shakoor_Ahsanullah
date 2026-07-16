<!-- meta tags and other links -->
@include('admin_panel.include.header_include')
<style>
    /* Premium UI Styles */
    .premium-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.03);
        overflow: hidden;
        padding: 24px;
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .page-title i {
        color: #3b82f6;
        background: rgba(59, 130, 246, 0.1);
        padding: 10px;
        border-radius: 12px;
        font-size: 1.2rem;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 10px 15px;
        box-shadow: none;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }

    .btn-premium {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 24px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
    }
    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
        color: #fff;
    }

    .btn-danger-premium {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 24px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);
    }
    .btn-danger-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(239, 68, 68, 0.3);
        color: #fff;
    }

    /* Ledger Specific Styles */
    .ledger-container {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 24px;
        max-width: 100%;
        margin: 20px auto 0;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.02);
    }

    .ledger-header {
        text-align: center;
        font-size: 24px;
        font-weight: 800;
        color: #2c3e50;
        padding-bottom: 16px;
        border-bottom: 2px solid #e9ecef;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .ledger-info {
        display: flex;
        justify-content: space-between;
        padding: 16px 0;
        border-bottom: 2px solid #e9ecef;
        color: #4b5563;
        font-size: 15px;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 15px;
    }

    th, td {
        border: 1px solid #e9ecef;
        padding: 12px 16px;
        text-align: center;
        color: #374151;
    }

    thead th {
        background: #f8f9fa;
        color: #1f2937;
        font-weight: 700;
        font-size: 14px;
        text-transform: uppercase;
        border-bottom: 2px solid #e9ecef;
    }

    tbody tr:hover {
        background: #f8fafc;
    }

    .opening-balance {
        text-align: right;
        font-weight: bold;
        padding: 12px 16px;
        background: #f8f9fa;
        color: #1f2937;
    }

    tfoot td {
        font-weight: bold;
        background: #f8f9fa;
        color: #1f2937;
        border-top: 2px solid #e9ecef;
    }

    .text-success { color: #10b981 !important; }
    .text-danger { color: #ef4444 !important; }
    .text-primary { color: #3b82f6 !important; }
</style>

<body>
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        <!-- sidebar end -->

        <!-- navbar-wrapper start -->
        @include('admin_panel.include.navbar_include')
        <!-- navbar-wrapper end -->

        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                <div class="d-flex mb-30 flex-wrap gap-3 justify-content-between align-items-center">
                    <h6 class="page-title">Vendors ledger</h6>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="premium-card mb-4">
                            <form id="ledgerSearchForm">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="fw-bold text-muted mb-2">Select Vendors</label>
                                        <select id="Vendor" class="form-control">
                                            <option value="">-- Select Vendors --</option>
                                            @foreach($Vendors as $Vendor)
                                            <option value="{{ $Vendor->id }}">{{ $Vendor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="fw-bold text-muted mb-2">Start Date</label>
                                        <input type="date" id="start_date" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="fw-bold text-muted mb-2">End Date</label>
                                        <input type="date" id="end_date" class="form-control">
                                    </div>
                                </div>
                                <div class="text-center mt-4">
                                    <button type="button" id="searchLedger" class="btn-premium">
                                        <i class="las la-search me-2"></i> Search
                                    </button>
                                </div>
                            </form>
                            <div class="text-end mt-2">
                                <button id="downloadPdf" class="btn-danger-premium d-none">
                                    <i class="las la-file-pdf me-2"></i> Download PDF
                                </button>
                            </div>
                            <div id="ledgerResult" class="mt-4" style="display: none;">
                                <div class="ledger-container">
                                    <div class="ledger-header">
                                        <i class="las la-file-invoice-dollar me-2" style="color: #3b82f6;"></i> Vendor Ledger
                                    </div>
                                    <div class="ledger-info">
                                        <span><i class="las la-user-tie me-2 text-muted"></i> <strong>Vendor:</strong> <span id="VendorName" class="text-primary fw-bold"></span></span>
                                        <span><i class="las la-calendar me-2 text-muted"></i> <strong>Duration:</strong> From <span id="startDate" class="fw-bold"></span> To <span id="endDate" class="fw-bold"></span></span>
                                    </div>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>INV-No</th>
                                                <th>Description</th>
                                                <th>Debit</th>
                                                <th>Credit</th>
                                                <th>Balance</th>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="opening-balance">Opening Balance:</td>
                                                <td id="openingBalance">Rs. 0</td>
                                            </tr>
                                        </thead>
                                        <tbody id="ledgerData"></tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3"><strong>Totals:</strong></td>
                                                <td id="totalDebit">0</td>
                                                <td id="totalCredit">0</td>
                                                <td id="closingBalance">0</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin_panel.include.footer_include')
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function formatDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        $(document).ready(function() {
            $('#searchLedger').click(function() {
                var VendorId = $('#Vendor').val();
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();

                if (!VendorId) {
                    alert('Please select a Vendor.');
                    return;
                }

                $.ajax({
                    url: "{{ route('fetch-Vendor-ledger-report') }}",
                    type: "GET",
                    data: {
                        Vendor_id: VendorId,
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        $('#ledgerResult').show();
                        $('#VendorName').text($('#Vendor option:selected').text());
                        $('#startDate').text(formatDate(response.startDate));
                        $('#endDate').text(formatDate(response.endDate));

                        let openingBalance = parseFloat(response.opening_balance) || 0;
                        let balance = openingBalance;
                        let totalDebit = 0,
                            totalCredit = 0;
                        let ledgerHTML = "";

                        // Opening Balance Entry
                        ledgerHTML += `
                        <tr>
                            <td>${formatDate(response.startDate)}</td>
                            <td>-</td>
                            <td class="fw-bold">Opening Balance</td>
                            <td>-</td>
                            <td>-</td>
                            <td class="fw-bold text-primary">Rs. ${balance.toFixed(2)}</td>
                        </tr>`;

                        // Combine and sort entries
                        let allEntries = [];

                        response.local_sales.forEach(entry => {
                            let lotsTotal = 0;
                            try {
                                if (entry.sale_units) {
                                    let saleUnitsArr = JSON.parse(entry.sale_units);
                                    if (Array.isArray(saleUnitsArr)) {
                                        saleUnitsArr.forEach(u => lotsTotal += parseFloat(u) || 0);
                                    } else {
                                        lotsTotal = parseFloat(entry.sale_units) || 0;
                                    }
                                }
                            } catch (e) {
                                lotsTotal = parseFloat(entry.sale_units) || 0;
                            }

                            let truckStr = entry.trucknumber ? `<strong>Truck:</strong> ${entry.trucknumber}` : '';
                            let subtotal = parseFloat(entry.subtotal || 0).toLocaleString();
                            let expense = parseFloat(entry.total_expense || 0).toLocaleString();
                            let adjustment = parseFloat(entry.adjustment || 0).toLocaleString();
                            
                            let detailsHtml = `Bill Created <br><span style="font-size: 13px; color: #555; display: inline-block; margin-top: 4px;">${truckStr} | <strong>Lots:</strong> ${lotsTotal} | <strong>Subtotal:</strong> ${subtotal} | <strong>Exp:</strong> ${expense} | <strong>Adj:</strong> ${adjustment}</span>`;

                            allEntries.push({
                                date: entry.sale_date,
                                type: 'sale',
                                invoice_number: entry.id,
                                amount: parseFloat(entry.total) || 0,
                                description: detailsHtml
                            });
                        });

                        response.recoveries.forEach(entry => {
                            allEntries.push({
                                date: entry.date,
                                type: 'recovery',
                                amount: parseFloat(entry.amount_paid) || 0,
                                description: 'Payment To Vendor'
                            });
                        });

                        allEntries.sort((a, b) => new Date(a.date) - new Date(b.date));

                        // Display Entries
                        allEntries.forEach(entry => {
                            if (entry.type === 'sale') {
                                balance += entry.amount;
                                totalDebit += entry.amount;
                                ledgerHTML += `
                                <tr>
                                    <td>${formatDate(entry.date)}</td>
                                    <td>${entry.invoice_number}</td>
                                    <td>${entry.description}</td>
                                    <td>Rs. ${entry.amount.toFixed(2)}</td>
                                    <td>-</td>
                                    <td class="fw-bold ${balance < 0 ? 'text-danger' : 'text-success'}">Rs. ${balance.toFixed(2)}</td>
                                </tr>`;
                            } else if (entry.type === 'recovery') {
                                balance -= entry.amount;
                                totalCredit += entry.amount;
                                ledgerHTML += `
                                <tr>
                                    <td>${formatDate(entry.date)}</td>
                                    <td>-</td>
                                    <td>${entry.description}</td>
                                    <td>-</td>
                                    <td>Rs. ${entry.amount.toFixed(2)}</td>
                                    <td class="fw-bold ${balance < 0 ? 'text-danger' : 'text-success'}">Rs. ${balance.toFixed(2)}</td>
                                </tr>`;
                            }
                        });

                        $('#ledgerData').html(ledgerHTML);
                        $('#openingBalance').text(`Rs. ${openingBalance.toFixed(2)}`);
                        $('#totalDebit').text(`Rs. ${totalDebit.toFixed(2)}`);
                        $('#totalCredit').text(`Rs. ${totalCredit.toFixed(2)}`);
                        $('#closingBalance').text(`Rs. ${balance.toFixed(2)}`);
                    }
                });
            });
        });
    </script>
    <script>
        document.getElementById("downloadPdf").addEventListener("click", function() {
            const element = document.querySelector(".ledger-container");

            html2canvas(element).then(canvas => {
                const imgData = canvas.toDataURL("image/png");
                const pdf = new jspdf.jsPDF("p", "mm", "a4");

                const imgProps = pdf.getImageProperties(imgData);
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
                pdf.save("Vendor_ledger .pdf");
            });
        });

        // Show PDF button only when result appears
        $('#searchLedger').click(function() {
            setTimeout(() => {
                $('#downloadPdf').removeClass('d-none');
            }, 500);
        });
    </script>