@include('admin_panel.include.header_include')
<style>
    /* Premium UI Styles */
    .premium-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.03);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
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
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
    }
    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
        color: #fff;
    }
    .btn-print {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(231, 76, 60, 0.2);
    }
    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(231, 76, 60, 0.3);
        color: #fff;
    }

    /* ====== Common Styles (Screen + Print) ====== */

    .print-section .container {
        padding: 10px 15px;
    }

    .column-title {
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 12px;
        color: #2c3e50;
        text-align: center;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .print-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        font-size: 14px;
        line-height: 1.2;
        margin-bottom: 15px;
    }

    .print-table th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        padding: 8px;
        border-bottom: 1px solid #e9ecef;
    }

    .print-table td {
        padding: 8px;
        border-bottom: 1px solid #e9ecef;
        text-align: center;
        color: #2c3e50; /* Ensure text is dark and visible */
    }

    .print-header {
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    .totals-row {
        background-color: #f8f9fa;
        font-weight: bold;
        font-size: 15px;
    }

    /* ====== Print Specific Styles ====== */
    @media print {
        .print-section {
            display: block !important;
        }
        body, html {
            margin: 0;
            padding: 0;
        }
        #filter-div,
        #print-btn,
        #page-head,
        .navbar-wrapper,
        .sidebar {
            display: none !important;
        }
        .print-table { border: 1px solid #000; border-radius: 0; }
        .print-table th, .print-table td { border-bottom: 1px solid #000; border-right: 1px solid #000; }
        .print-table th:last-child, .print-table td:last-child { border-right: none; }
        .column-title { border: 1px solid #000; border-radius: 0; background: #e9ecef !important; -webkit-print-color-adjust: exact; }

        .print-section .row {
            display: flex;
            flex-wrap: nowrap; /* Keep side by side in print */
            margin-bottom: 0;
            width: 100%;
        }

        .print-section .col-print-6 {
            width: 50%;
            flex: 0 0 50%;
            padding: 5px;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        @page {
            size: A4 landscape; /* Landscape for side-by-side */
            margin: 10mm;
        }
    }
</style>

<body>
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        @include('admin_panel.include.navbar_include')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                <div class="d-flex mb-4 flex-wrap gap-3 justify-content-between align-items-center">
                    <h6 class="page-title" id="page-head">
                        <i class="fas fa-balance-scale"></i> Sale & Recovery Report
                    </h6>
                    <button onclick="window.print()" class="btn-print" id="print-btn">
                        <i class="fas fa-print"></i> Print Report
                    </button>
                </div>

                <div class="premium-card p-4 mb-4" id="filter-div">
                    <div class="card-body p-0">
                        <form id="reportForm">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-muted fw-bold">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-muted fw-bold">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <button type="button" class="btn-premium w-100" id="filterReport">
                                        <i class="fas fa-search"></i> Generate Report
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive mt-4 d-none" id="loader-div" style="text-align: center;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- Print & Display Section -->
                <div class="print-section d-none bg-white p-4 rounded-4 shadow-sm" id="printSection">
                    <div class="container-fluid p-0">
                        <div class="print-header">Sale & Recovery Report</div>
                        <div class="text-center mb-4 text-muted" id="printDateRange" style="font-size: 16px;"></div>

                        <div class="row">
                            <!-- Left Side: Sales -->
                            <div class="col-md-6 col-print-6">
                                <div class="column-title text-primary"><i class="fas fa-box-open me-2"></i>Sales</div>
                                <table class="print-table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Customer</th>
                                            <th>Lots</th>
                                            <th>Weight</th>
                                            <th>Total (PKR)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="salesTableBody" class="text-dark">
                                        <!-- Injected via JS -->
                                    </tbody>
                                    <tfoot class="totals-row">
                                        <tr>
                                            <td colspan="3" class="text-end">Grand Total Sales:</td>
                                            <td id="grandTotalSales" class="text-success">0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Right Side: Recoveries -->
                            <div class="col-md-6 col-print-6">
                                <div class="column-title text-success"><i class="fas fa-hand-holding-usd me-2"></i>Recoveries</div>
                                <table class="print-table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Customer</th>
                                            <th>Date</th>
                                            <th>Amount (PKR)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recoveriesTableBody" class="text-dark">
                                        <!-- Injected via JS -->
                                    </tbody>
                                    <tfoot class="totals-row">
                                        <tr>
                                            <td colspan="2" class="text-end">Grand Total Recovery:</td>
                                            <td id="grandTotalRecovery" class="text-success">0</td>
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

    @include('admin_panel.include.footer_include')
    <script>
        $('#filterReport').on('click', function() {
            let start = $('#start_date').val();
            let end = $('#end_date').val();

            if (!start || !end) {
                alert('Please select both start and end dates.');
                return;
            }

            $.ajax({
                url: '{{ route("fetch.sale.recovery.report") }}',
                type: 'POST',
                dataType: 'json',
                beforeSend: function() {
                    $('#printSection').addClass('d-none');
                    $('#loader-div').removeClass('d-none');
                },
                data: {
                    _token: '{{ csrf_token() }}',
                    start_date: start,
                    end_date: end
                },
                success: function(response) {
                    $('#loader-div').addClass('d-none');
                    try {
                        let salesHtml = '';
                        let grandSales = 0;
                        let salesData = response.sales || [];
                        
                        if(salesData.length > 0) {
                            salesData.forEach(s => {
                                let total = parseFloat(s.total) || 0;
                                salesHtml += `
                                    <tr>
                                        <td class="text-start fw-bold">${s.customer || '-'}</td>
                                        <td>${s.quantity || '-'}</td>
                                        <td>${s.weight || '-'}</td>
                                        <td class="text-end">${total.toLocaleString()}</td>
                                    </tr>
                                `;
                                grandSales += total;
                            });
                        } else {
                            salesHtml = '<tr><td colspan="4" class="text-center text-muted">No sales found.</td></tr>';
                        }

                        let recoveriesHtml = '';
                        let grandRecovery = 0;
                        let recoveriesData = response.recoveries || [];
                        
                        if(recoveriesData.length > 0) {
                            recoveriesData.forEach(r => {
                                let amount = parseFloat(r.amount_paid) || 0;
                                recoveriesHtml += `
                                    <tr>
                                        <td class="text-start fw-bold">${r.customer || '-'}</td>
                                        <td>${r.date || '-'}</td>
                                        <td class="text-end">${amount.toLocaleString()}</td>
                                    </tr>
                                `;
                                grandRecovery += amount;
                            });
                        } else {
                            recoveriesHtml = '<tr><td colspan="3" class="text-center text-muted">No recoveries found.</td></tr>';
                        }

                        $('#salesTableBody').html(salesHtml);
                        $('#grandTotalSales').text(grandSales.toLocaleString());

                        $('#recoveriesTableBody').html(recoveriesHtml);
                        $('#grandTotalRecovery').text(grandRecovery.toLocaleString());

                        $('#printDateRange').html(`<strong>From:</strong> ${start} &nbsp;&nbsp;|&nbsp;&nbsp; <strong>To:</strong> ${end}`);
                        $('#printSection').removeClass('d-none').show();
                    } catch (e) {
                        console.error(e);
                        alert("Display Error: " + e.message);
                    }
                },
                error: function(xhr, status, error) {
                    $('#loader-div').addClass('d-none');
                    alert('AJAX Error: ' + error);
                }
            });
        });
    </script>
</body>
