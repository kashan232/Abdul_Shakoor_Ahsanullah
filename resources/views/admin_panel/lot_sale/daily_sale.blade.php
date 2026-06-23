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
    .premium-card:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
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

    /* ====== Common Styles (Screen + Print) ====== */

    .print-section .container {
        padding: 10px 15px;
    }

    .customer-name {
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 8px;
        color: #2c3e50;
        border-bottom: 2px solid #2ecc71;
        display: inline-block;
        padding-bottom: 4px;
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
    }

    .print-header {
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    /* ====== Print Specific Styles ====== */
    @media print {

        /* Show the print section */
        .print-section {
            display: block !important;
        }

        /* Remove margins/paddings of body and html */
        body,
        html {
            margin: 0;
            padding: 0;
        }

        /* Hide unwanted elements while printing */
        #daily-sale-div,
        #print-btn-sale,
        #daily-sale-head,
        .navbar-wrapper,
        .sidebar {
            display: none !important;
        }

        .print-table { border: 1px solid #000; border-radius: 0; }
        .print-table th, .print-table td { border-bottom: 1px solid #000; border-right: 1px solid #000; }
        .print-table th:last-child, .print-table td:last-child { border-right: none; }
        .customer-name { border-bottom: 1px solid #000; }

        /* Layout for rows and columns */
        .print-section .row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 0;
        }

        .print-section .col-print-6 {
            width: 48%;
            flex: 0 0 48%;
            padding: 5px;
            box-sizing: border-box;
            page-break-inside: avoid;
            margin-right: 2%;
            display: flex;
            flex-direction: column;
        }

        .print-section .row .col-print-6:nth-child(2n) {
            margin-right: 0;
        }

        .print-section .row:has(.col-print-6:only-child) .col-print-6 {
            width: 100%;
            margin-right: 0;
        }

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
                    <h6 class="page-title" id="daily-sale-head">
                        <i class="fas fa-calendar-day"></i> Daily Sale
                    </h6>
                    <button onclick="window.print()" class="btn-print" id="print-btn-sale">
                        <i class="fas fa-print"></i> Print Report
                    </button>
                </div>

                <div class="premium-card p-4 mb-4" id="daily-sale-div">
                    <div class="card-body p-0">
                        <form id="customerSaleForm">
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
                                    <button type="button" class="btn-premium w-100" id="filterSales">
                                        <i class="fas fa-search"></i> Search Sales
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

                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-hover shadow-sm rounded-3" style="width:100%">
                        <tbody id="salesTableBody" class="text-center">
                        </tbody>
                    </table>
                </div>

                <div class="print-section d-none" id="printSection">
                    <div class="container">
                        <div class="print-header">Daily Customer Sales Report</div>
                        <div class="text-center" id="printDateRange" style="font-size: 14px;"></div>

                        <div class="row" id="printContentRow">
                            <!-- Dynamic columns will be injected here -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('admin_panel.include.footer_include')
    <script>
        $('#filterSales').on('click', function() {
            let start = $('#start_date').val();
            let end = $('#end_date').val();

            if (!start || !end) {
                alert('Please select both start and end dates.');
                return;
            }

            $.ajax({
                url: '{{ route("daily.sales") }}',
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
                    if (!response || response.length === 0) {
                        $('#printContentRow').html('<div class="col-12 text-center py-5 text-muted"><i class="fas fa-box-open fa-3x mb-3 d-block"></i><h4>No sales found for selected dates.</h4></div>');
                        $('#printSection').removeClass('d-none');
                        return;
                    }

                    let grouped = {};
                    let grandLots = 0;
                    let grandWeight = 0;
                    let grandAmount = 0;

                    response.forEach(sale => {
                        let customer = sale.customer;
                        if (!grouped[customer]) grouped[customer] = [];
                        grouped[customer].push(sale);
                    });

                    let printContent = '';
                    let customerKeys = Object.keys(grouped);

                    for (let i = 0; i < customerKeys.length; i += 2) {
                        printContent += '<div class="row">';

                        let processCustomer = (customer) => {
                            let customerSales = grouped[customer];
                            let lotsHtml = '';
                            let totalLots = 0;
                            let totalWeight = 0;
                            let totalLotAmount = 0;

                            customerSales.forEach(item => {
                                lotsHtml += `
                                    <tr>
                                        <td class="text-center">${item.quantity}</td>
                                        <td class="text-center">${item.weight ? item.weight : '-'}</td>
                                        <td class="text-start">${item.unit} (${item.unit_in})</td>
                                        <td class="text-end">${item.price}</td>
                                        <td class="text-end">${item.total}</td>
                                    </tr>
                                `;
                                totalLots += parseFloat(item.quantity);
                                if (item.weight) totalWeight += parseFloat(item.weight);
                                totalLotAmount += parseFloat(item.total);
                            });

                            grandLots += totalLots;
                            grandWeight += totalWeight;
                            grandAmount += totalLotAmount;

                            printContent += `
                                <div class="col-print-6">
                                    <div class="customer-name">${customer}</div>
                                    <table class="print-table">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Lots</th>
                                            <th class="text-center">Weight</th>
                                            <th class="text-start">U.In</th>
                                            <th class="text-end">Rate</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                        <tbody>${lotsHtml}</tbody>
                                        <tfoot style="background-color: #f8f9fa;">
                                            <tr>
                                                <td colspan="4" class="text-end fw-bold">Total Lots:</td>
                                                <td class="fw-bold">${totalLots.toFixed(2)}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-end fw-bold">Total Weight:</td>
                                                <td class="fw-bold">${totalWeight.toFixed(2)}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-end fw-bold">Total Amount:</td>
                                                <td class="fw-bold">Rs. ${totalLotAmount.toFixed(2)}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            `;
                        };

                        let customer1 = customerKeys[i];
                        let customer2 = customerKeys[i + 1];

                        if (customer1) processCustomer(customer1);
                        if (customer2) processCustomer(customer2);

                        printContent += '</div>';
                    }

                    printContent += `
                        <div class="col-print-12 mt-4">
                            <h5>Grand Totals</h5>
                            <table class="print-table">
                                <tfoot>
                                    <tr>
                                        <td><strong>Grand Total Lots:</strong></td>
                                        <td>${grandLots.toFixed(2)}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Grand Total Weight:</strong></td>
                                        <td>${grandWeight.toFixed(2)}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Grand Total Amount:</strong></td>
                                        <td>${grandAmount.toFixed(2)}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    `;

                    $('#printContentRow').html(printContent);
                    $('#printDateRange').text(`From ${start} to ${end}`);
                    $('#printSection').removeClass('d-none');
                },
                error: function() {
                    alert('Something went wrong. Please try again.');
                }
            });
        });
    </script>

</body>