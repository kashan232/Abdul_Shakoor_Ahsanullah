{{-- resources/views/admin_panel/daily_truck_sale.blade.php --}}
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

    .truck-block h5 {
        font-weight: 700;
        color: #2c3e50;
        border-left: 4px solid #2ecc71;
        padding-left: 10px;
        margin-top: 20px;
        margin-bottom: 15px;
    }

    /* ====== Print Specific Styles ====== */
    @media print {
        body, html {
            margin: 0;
            padding: 0;
            background: white !important;
        }

        /* Hide everything except .print-section */
        body * {
            visibility: hidden;
        }

        .print-section,
        .print-section * {
            visibility: visible;
        }

        .print-section {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
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

                {{-- Header & Filters --}}
                <div class="d-flex mb-4 justify-content-between align-items-center flex-wrap gap-3">
                    <h6 class="page-title">
                        <i class="fas fa-truck"></i> Daily Sale Truck Wise
                    </h6>
                    <button onclick="window.print()" class="btn-print">
                        <i class="fas fa-print"></i> Print Report
                    </button>
                </div>

                <div class="premium-card p-4 mb-4" id="daily-sale-div">
                    <div class="card-body p-0">
                        <form id="customerSaleForm" class="row align-items-end">
                            @csrf
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
                        </form>
                    </div>
                </div>

                <div class="table-responsive mt-4 d-none" id="loader-div" style="text-align: center;">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                {{-- Print‑section (also visible on screen) --}}
                <div id="printSection" class="print-section d-none">
                    <div class="container premium-card p-4">
                        <div class="print-header">
                            Daily Sale Truck Wise Report
                        </div>
                        <div class="text-center mb-3 text-muted" id="printDateRange" style="font-size:14px;"></div>

                        {{-- Dynamic per‑truck tables will be injected here --}}
                        <div id="printResults"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('admin_panel.include.footer_include')

    <script>
        $('#filterSales').on('click', function() {
            const start = $('#start_date').val();
            const end = $('#end_date').val();

            if (!start || !end) {
                alert('Please select both start and end dates.');
                return;
            }

            $.ajax({
                url: '{{ route("daily.truck.sale") }}',
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
                    
                    if (!response || response.length === 0) {
                        $('#printResults').html('<div class="text-center py-5 text-muted"><i class="fas fa-truck-loading fa-3x mb-3 d-block"></i><h4>No truck sales found for selected dates.</h4></div>');
                        $('#printDateRange').text(`From ${start} to ${end}`);
                        return;
                    }

                    // Group by truck_number
                    const grouped = {};
                    response.forEach(r => {
                        (grouped[r.truck_number] = grouped[r.truck_number] || []).push(r);
                    });

                    let grandTotal = 0;
                    let html = '';

                    // Build per‑truck tables
                    for (const [truck, rows] of Object.entries(grouped)) {
                        let truckTotal = 0;
                        let rowsHtml = '';

                        rows.forEach(r => {
                            rowsHtml += `
                            <tr>
                                <td class="text-start">${r.customer_name}</td>
                                <td class="text-end">${r.total_quantity}</td>
                            </tr>`;
                            truckTotal += parseFloat(r.total_quantity);
                        });

                        grandTotal += truckTotal;

                        html += `
                        <div class="truck-block">
                            <h5>Truck: ${truck}</h5>
                            <table class="print-table">
                                <thead>
                                    <tr>
                                        <th class="text-start">Customer</th>
                                        <th class="text-end">Quantity Sold</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${rowsHtml}
                                </tbody>
                                <tfoot style="background-color: #f8f9fa;">
                                    <tr>
                                        <td class="text-end fw-bold text-dark">Total for ${truck}</td>
                                        <td class="fw-bold text-dark">${truckTotal}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>`;
                    }

                    // Grand total
                    html += `<div class="truck-block mt-4">
                            <table class="print-table">
                                <tfoot style="background: linear-gradient(135deg, #27ae60, #2ecc71); color: white;">
                                    <tr>
                                        <td class="text-end"><h5 class="m-0 text-white border-0">Grand Total Sold</h5></td>
                                        <td class="text-end"><h5 class="m-0 text-white border-0">${grandTotal}</h5></td>
                                    </tr>
                                </tfoot>
                            </table>
                         </div>`;

                    // Inject into print area
                    $('#printDateRange').text(`From ${start} to ${end}`);
                    $('#printResults').html(html);
                },
                error: function() {
                    alert('Something went wrong. Please try again.');
                }
            });
        });
    </script>
</body>