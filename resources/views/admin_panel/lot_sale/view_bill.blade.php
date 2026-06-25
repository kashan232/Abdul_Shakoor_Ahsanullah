@include('admin_panel.include.header_include')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    @media print {
        body.color-print .print-header-content {
            display: block !important;
        }

        body.blank-print .print-header-content {
            display: none !important;
        }

        body.blank-print .header-bar-space {
            display: block !important;
            height: 180px; /* Space for pre-printed header */
        }

        body.color-print .header-bar-space {
            display: none !important;
        }

        .no-print {
            display: none !important;
        }
    }

    .print-btns {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: #fff;
        padding: 15px;
        border-bottom: 1px solid #ddd;
    }

    /* Screen display settings for new header */
    .print-header-content {
        background: linear-gradient(to bottom, #dbeafe, #bfdbfe);
        border: 1px solid #93c5fd;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        color: #1e3a8a;
        font-family: 'Jameel Noori Nastaleeq', 'Noto Nastaliq Urdu', 'Nafees Web Naskh', Tahoma, sans-serif;
        text-align: center;
        direction: rtl; /* Right to left for Urdu */
    }

    .ph-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        font-weight: bold;
        font-size: 16px;
        line-height: 1.4;
    }

    .ph-top-left, .ph-top-right {
        text-align: center;
    }
    
    .ph-top-center {
        font-family: 'Amiri', 'Traditional Arabic', serif; /* Arabic font */
        font-size: 20px;
        color: #1e40af;
        line-height: 1.6;
    }

    .ph-title {
        font-size: 42px;
        font-weight: 900;
        color: #431407; /* Dark brown */
        margin: 15px 0 10px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    }
    
    .ph-subtitle {
        display: flex;
        justify-content: space-around;
        font-size: 18px;
        font-weight: bold;
        color: #374151;
        margin-bottom: 15px;
    }

    .ph-address {
        background: #701a75; /* Purple background */
        color: white;
        display: inline-block;
        padding: 6px 25px;
        border-radius: 25px;
        font-size: 17px;
        font-weight: bold;
        letter-spacing: 0.5px;
    }

    .en-text {
        font-family: 'Arial', sans-serif;
        direction: ltr;
        display: inline-block;
        color: #1f2937;
    }
    
    .name-text {
        color: #ea580c; /* Orange color for names */
        font-size: 18px;
    }
</style>

<body>
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        @include('admin_panel.include.navbar_include')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                <!-- Print Buttons -->
                <div class="print-btns d-flex justify-content-end gap-2 no-print">
                    <button onclick="printDocument('blank')" class="btn btn-outline-secondary">Blank Print</button>
                    <button onclick="printDocument('color')" class="btn btn-primary">Color Print</button>
                </div>

                <!-- Header for color print -->
                <div class="print-header-content">
                    <div class="ph-top">
                        <div class="ph-top-right">
                            <div style="font-family: Arial, sans-serif; font-weight: 900; direction: ltr;">
                                <span style="color: #ef4444; font-size: 24px;">A.U</span>
                                <span style="color: #4b5563; font-size: 14px;">& BROTHERS</span>
                            </div>
                            <div class="name-text mt-1">احسان اللہ</div>
                            <div class="en-text">0300-9378192</div><br>
                            <div class="en-text">0313-9378192</div>
                        </div>
                        <div class="ph-top-center">
                            حَسْبُنَا اللَّهُ وَنِعْمَ الْوَكِيلُ<br>
                            نِعْمَ الْمَوْلَى وَنِعْمَ النَّصِيرُ
                        </div>
                        <div class="ph-top-left">
                            <div class="name-text">امان اللہ</div>
                            <div class="en-text">0321-3045286</div>
                            <div class="name-text mt-1">عطاء اللہ</div>
                            <div class="en-text">0308-3002916</div>
                        </div>
                    </div>
                    
                    <div class="ph-title">ماما عبدالشکور احسان اللہ اینڈ کو</div>
                    
                    <div class="ph-subtitle">
                        <div>آلو، پیاز، لہسن کمیشن ایجنٹس</div>
                        <div>پروپرائٹر: امان اللہ، عطاء اللہ</div>
                    </div>
                    
                    <div class="ph-address">
                        دکان نمبر 227-226-200 نیو سبزی مارکیٹ ہالانا کہ حیدرآباد
                    </div>
                </div>

                <!-- Blank print space -->
                <div class="header-bar-space mb-3" style="display: none;"></div>

                <div class="d-flex mb-4 flex-wrap gap-3 justify-content-between align-items-center">
                    <h4 class="fw-bold text-primary mb-4 no-print">Vendor Bill Details</h4>
                </div>

                <div class="card shadow-lg p-4">
                    <div class="card-body">
                        <!-- Truck Info -->
                        <div class="mb-4">
                            <h5 class="fw-bold">Truck Information</h5>
                            <div class="row">
                                <div class="col-md-6"><strong>Truck ID:</strong> {{ $bill->truck_id }}</div>
                                <div class="col-md-6"><strong>Truck Number:</strong> {{ $bill->trucknumber }}</div>
                            </div>
                        </div>

                        <!-- LOT Details -->
                        <h5 class="text-success">Lots Sold</h5>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Lot ID</th>
                                    <th>Unit In</th>
                                    <th>Units</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(json_decode($bill->lot_id) as $index => $lotId)
                                <tr>
                                    <td>{{ $lotId }}</td>
                                    <td>{{ json_decode($bill->unit_in)[$index] ?? '-' }}</td>
                                    <td>{{ json_decode($bill->sale_units)[$index] ?? '-' }}</td>
                                    <td>Rs. {{ number_format(json_decode($bill->rate)[$index] ?? 0) }}</td>
                                    <td>Rs. {{ number_format(json_decode($bill->amount)[$index] ?? 0) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <td colspan="4" class="text-end">Subtotal</td>
                                    <td>Rs. {{ number_format($bill->subtotal, 0) }}</td>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Expenses -->
                        <h5 class="text-danger mt-4">Expenses</h5>
                        @php
                        $finalAmounts = json_decode($bill->final_amount, true);
                        $categories = json_decode($bill->category, true);
                        $mazdoriTotal = 0;
                        foreach ($categories as $key => $cat) {
                        if (strtolower($cat) === 'mazdori') {
                        $mazdoriTotal += $finalAmounts[$key] ?? 0;
                        }
                        }
                        $expenseTotal = array_sum($finalAmounts);
                        @endphp

                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Category</th>
                                    <th>Final Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $index => $category)
                                <tr>
                                    <td>{{ $category }}</td>
                                    <td>Rs. {{ number_format($finalAmounts[$index] ?? 0) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <td colspan="1" class="text-end">Total Expenses</td>
                                    <td>Rs. {{ number_format($expenseTotal, 0) }}</td>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Summary -->
                        <!-- Summary Breakdown -->
                        <div class="mt-5 p-4 border rounded bg-white shadow-sm">
                            <h4 class="fw-bold text-center text-primary mb-4">Final Summary</h4>

                            <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                <span class="fw-bold fs-5">Subtotal</span>
                                <span class="fw-bold fs-5 text-dark">Rs. {{ number_format($bill->subtotal, 0) }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                <span class="fw-bold fs-5 text-danger">Total Expenses</span>
                                <span class="fw-bold fs-5 text-danger">Rs. {{ number_format($expenseTotal, 0) }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3">
                                <span class="fw-bold fs-4 text-success">Net Amount</span>
                                <span class="fw-bold fs-4 text-success">Rs. {{ number_format($bill->net_pay, 0) }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin_panel.include.footer_include')

    <script>
        function printDocument(mode) {
            if (mode === 'blank') {
                document.body.classList.remove('color-print');
                document.body.classList.add('blank-print');
            } else {
                document.body.classList.remove('blank-print');
                document.body.classList.add('color-print');
            }
            window.print();
        }
    </script>
</body>