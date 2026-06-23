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
        color: #2ecc71;
        background: rgba(46, 204, 113, 0.1);
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
        border-color: #2ecc71;
        box-shadow: 0 0 0 0.2rem rgba(46, 204, 113, 0.25);
    }
    .btn-premium {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(46, 204, 113, 0.2);
    }
    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(46, 204, 113, 0.3);
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

    .print-section .container {
        padding: 10px 15px;
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
        padding: 12px 8px;
        border-bottom: 1px solid #e9ecef;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .print-table td {
        padding: 12px 8px;
        border-bottom: 1px solid #e9ecef;
        text-align: center;
    }

    .print-header {
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 5px;
        color: #2c3e50;
    }

    .print-date-range {
        text-align: center;
        font-size: 14px;
        margin-bottom: 15px;
        color: #7f8c8d;
    }

    @media print {
        .print-section {
            display: block !important;
        }
        body, html {
            margin: 0;
            padding: 0;
        }
        #daily-recovery-div, #print-btn-recovery, #daily-recovery-head, .navbar-wrapper, .sidebar {
            display: none !important;
        }
        .print-table { border: 1px solid #000; border-radius: 0; }
        .print-table th, .print-table td { border-bottom: 1px solid #000; border-right: 1px solid #000; }
        .print-table th:last-child, .print-table td:last-child { border-right: none; }
        
        @page {
            size: auto;
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
                    <h6 class="page-title mt-2 mb-2" id="daily-recovery-head">
                        <i class="fas fa-hand-holding-usd"></i> Daily Recoveries
                    </h6>
                    <button onclick="window.print()" class="btn-print mt-2 mb-2" id="print-btn-recovery">
                        <i class="fas fa-print"></i> Print Report
                    </button>
                </div>

                <div class="premium-card p-4 mb-4" id="daily-recovery-div">
                    <div class="card-body p-0">
                        <form id="customerRecoveryForm">
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
                                    <button type="button" class="btn-premium w-100" id="filterRecoveries">
                                        <i class="fas fa-search"></i> Search Recoveries
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive mt-4 d-none" id="loader-div" style="text-align: center;">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div class="print-section d-none" id="printSection">
                    <div class="container premium-card p-4">
                        <div class="print-header">Daily Customer Recoveries</div>
                        <div class="print-date-range" id="printDateRange"></div>
                        <table class="print-table">
                            <thead>
                                <tr>
                                    <th class="text-start">Customer</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-start">Description</th>
                                    <th class="text-center">Payment Method</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody id="printContentRow"></tbody>
                            <tfoot style="background-color: #f8f9fa;">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold text-success">Total Recoveries:</td>
                                    <td class="fw-bold text-success text-end" id="printTotalRecoveryAmount"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('admin_panel.include.footer_include')

    <script>
        $('#filterRecoveries').on('click', function() {
            let start = $('#start_date').val();
            let end = $('#end_date').val();

            if (!start || !end) {
                alert('Please select both start and end dates.');
                return;
            }

            $.ajax({
                url: '{{ route("daily.recovery") }}',
                type: 'POST',
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
                    $('#printSection').removeClass('d-none');
                    let totalRecoveryAmount = 0;
                    let content = '';
                    let printContent = '';

                    if (!response || response.length === 0) {
                        $('#printContentRow').html('<tr><td colspan="5" class="text-center py-4 text-muted"><i class="fas fa-search-dollar fa-2x mb-2 d-block"></i> No recoveries found for selected dates.</td></tr>');
                        $('#printTotalRecoveryAmount').text('Rs. 0.00');
                        $('#printDateRange').text(`From ${start} to ${end}`);
                        return;
                    }

                    response.forEach(item => {
                        content += `<tr>
                            <td class="text-start">${item.customer}</td>
                            <td class="text-center">${item.date}</td>
                            <td class="text-start">${item.description}</td>
                            <td class="text-center">${item.payment_method}</td>
                            <td class="text-end">${parseFloat(item.amount).toFixed(2)}</td>
                        </tr>`;

                        printContent += `<tr>
                            <td class="text-start"><strong>${item.customer}</strong></td>
                            <td class="text-center">${item.date}</td>
                            <td class="text-start">${item.description}</td>
                            <td class="text-center"><span class="badge bg-secondary">${item.payment_method}</span></td>
                            <td class="text-end"><strong>Rs. ${parseFloat(item.amount).toFixed(2)}</strong></td>
                        </tr>`;

                        totalRecoveryAmount += parseFloat(item.amount);
                    });

                    $('#recoveryTableBody').html(content);
                    $('#printContentRow').html(printContent);

                    $('#totalRecoveryAmount').text('Rs. ' + totalRecoveryAmount.toFixed(2));
                    $('#printTotalRecoveryAmount').text('Rs. ' + totalRecoveryAmount.toFixed(2));
                    $('#printDateRange').text(`From ${start} to ${end}`);
                },
                error: function() {
                    alert('Something went wrong. Please try again.');
                }
            });
        });
    </script>
</body>
