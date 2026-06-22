@include('admin_panel.include.header_include')

<style>
    /* Professional Loading Overlay */
    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: opacity 0.5s ease-in-out;
    }
    .spinner-border {
        width: 3rem;
        height: 3rem;
        color: #0d6efd;
    }
    .progress-text {
        margin-top: 15px;
        font-weight: 500;
        color: #555;
    }
</style>

<body>
    <!-- Preloader Overlay -->
    <div id="loading-overlay">
        <div class="spinner-border" role="status"></div>
        <div class="progress-text">Preparing Truck Data... <span id="load-pct">0%</span></div>
    </div>

    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        @include('admin_panel.include.navbar_include')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                <div class="d-flex mb-4 flex-wrap gap-3 justify-content-between align-items-center">
                    <h4 class="fw-bold text-primary"><i class="fas fa-truck-loading me-2"></i> Sold Trucks</h4>
                </div>
                <div class="card shadow-lg border-0 b-radius--10">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="sold-trucks-table" class="table table--light style--two custom-data-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Truck Number</th>
                                        <th>Vendor</th>
                                        <th>Arrival Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- AJAX Data goes here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('admin_panel.include.footer_include')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            let table = $('#sold-trucks-table').DataTable({
                ajax: {
                    url: "{{ route('trucks-sold.data') }}",
                    dataSrc: function(json) {
                        $('#load-pct').text('100%');
                        setTimeout(() => {
                            $('#loading-overlay').fadeOut('slow');
                        }, 500);
                        return json.data;
                    }
                },
                columns: [
                    { data: null, render: (data, type, row, meta) => meta.row + 1 },
                    { data: 'truck_number' },
                    { data: 'vendor_id' },
                    { data: 'entry_date' },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let actionHtml = '';
                            
                            // Base URL for the project
                            let baseUrl = "{{ url('/') }}";

                            // Sold Logic
                            if (parseFloat(row.total_units) > 0) {
                                let saleUrl = baseUrl + "/show-Lots/" + row.id;
                                actionHtml += `<a href="${saleUrl}" class="btn btn-primary btn-sm me-1"><i class="fas fa-shopping-cart"></i> Sale</a>`;
                            } else {
                                actionHtml += `<span class="badge bg-danger p-2 me-1">Units Sold</span> `;
                                
                                if (row.bill_id) {
                                    let viewUrl = baseUrl + "/vendor-bill/view/" + row.bill_id;
                                    let bookUrl = baseUrl + "/bill-book/view/" + row.bill_id;
                                    actionHtml += `
                                        <a href="${viewUrl}" class="btn btn-info btn-sm me-1"><i class="fas fa-eye"></i> View Bill</a>
                                        <a href="${bookUrl}" class="btn btn-dark btn-sm me-1"><i class="fas fa-book"></i> Bill Book</a>
                                        <button class="btn btn-warning btn-sm" onclick="confirmDelete(${row.bill_id})"><i class="fas fa-trash-alt"></i> Delete Bill</button>
                                    `;
                                } else {
                                    let createUrl = baseUrl + "/create-bill/" + row.id + "/" + row.vendor_id;
                                    actionHtml += `<a href="${createUrl}" class="btn btn-outline-primary btn-sm me-1"><i class="fas fa-plus"></i> Create Bill</a>`;
                                }
                            }

                            // Sale Record always available
                            let recordUrl = baseUrl + "/sale-record/" + row.id;
                            actionHtml += `<a href="${recordUrl}" class="btn btn-success btn-sm"><i class="fas fa-history"></i> Sale Record</a>`;

                            return actionHtml;
                        }
                    }
                ],
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'pdf', 'csv'],
                pageLength: 25,
                order: [[0, 'asc']],
                initComplete: function() {
                    // Safety check if ajax was too fast or didn't trigger pct update
                    $('#loading-overlay').fadeOut();
                }
            });

            // Loading progress simulation
            let pct = 0;
            let interval = setInterval(() => {
                if (pct < 95) {
                    pct += Math.floor(Math.random() * 10) + 1;
                    if (pct > 95) pct = 95;
                    $('#load-pct').text(pct + '%');
                } else {
                    clearInterval(interval);
                }
            }, 100);
        });

        function confirmDelete(billId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This bill will be deleted permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('/delete-bill') }}/" + billId;
                }
            });
        }
    </script>
</body>