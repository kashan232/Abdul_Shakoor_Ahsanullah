@include('admin_panel.include.header_include')
<style>
    .cash-book-header {
        background-color: #111;
        color: #fff;
        padding: 10px 15px;
        border-radius: 5px 5px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .cash-book-header h4 {
        color: #fff;
        margin: 0;
        font-weight: bold;
        letter-spacing: 1px;
    }
    .cash-book-filters {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    .cash-book-filters label {
        margin: 0;
        font-size: 0.9rem;
        color: #ccc;
    }
    .cash-book-filters input {
        border: 1px solid #444;
        padding: 5px 10px;
        border-radius: 3px;
        background: #fff;
        color: #000;
    }
    .cash-book-content {
        background: #fff;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 0 0 5px 5px;
    }
    .balance-box {
        text-align: right;
        margin-bottom: 20px;
    }
    .balance-box h6 {
        font-weight: bold;
        margin-bottom: 0;
        color: #333;
    }
    .balance-box h3 {
        font-weight: bold;
        color: #000;
        margin-top: 5px;
    }
    .cash-table {
        width: 100%;
        border-collapse: collapse;
    }
    .cash-table th, .cash-table td {
        border: 1px solid #ccc;
        padding: 10px;
    }
    .cash-table th {
        background-color: #34495e;
        color: #fff;
        text-transform: uppercase;
        font-size: 0.9rem;
    }
    .cash-table td {
        vertical-align: top;
    }
    .total-row {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .grand-total-row {
        background-color: #e9ecef;
        font-weight: bold;
        text-align: center;
    }
    .small-text {
        font-size: 0.8rem;
        color: #666;
        display: block;
    }
    .row-data {
        display: flex;
        justify-content: space-between;
    }
</style>

<body>
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        @include('admin_panel.include.navbar_include')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                <div class="cash-book-container">
                    <div class="cash-book-header">
                        <h4>DAILY CASH BOOK</h4>
                        <form method="GET" action="{{ route('cash-book') }}" id="cashBookFilterForm">
                            <div class="cash-book-filters">
                                <div class="d-flex align-items-center gap-2">
                                    <label>Start Date:</label>
                                    <input type="date" name="start_date" value="{{ $startDate }}">
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <label>End Date:</label>
                                    <input type="date" name="end_date" value="{{ $endDate }}">
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary ml-2">Filter</button>
                            </div>
                        </form>
                    </div>

                    <div class="cash-book-content">
                        <table class="cash-table">
                            <thead>
                                <tr>
                                    <th width="35%">RECEIPTS (DEBIT)</th>
                                    <th width="15%" class="text-end">AMOUNT</th>
                                    <th width="35%">PAYMENTS (CREDIT)</th>
                                    <th width="15%" class="text-end">AMOUNT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $maxRows = max(count($receipts), count($payments));
                                @endphp
                                @for ($i = 0; $i < $maxRows; $i++)
                                <tr>
                                    <!-- Receipts Column -->
                                    <td>
                                        @if(isset($receipts[$i]))
                                            <strong>{{ $receipts[$i]['type'] }}</strong>
                                            <span class="small-text">{{ $receipts[$i]['description'] }} | {{ \Carbon\Carbon::parse($receipts[$i]['date'])->format('d M, Y') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold">
                                        @if(isset($receipts[$i]))
                                            {{ number_format($receipts[$i]['amount']) }}
                                        @endif
                                    </td>

                                    <!-- Payments Column -->
                                    <td>
                                        @if(isset($payments[$i]))
                                            <strong>{{ $payments[$i]['type'] }}</strong>
                                            <span class="small-text">{{ $payments[$i]['description'] }} | {{ \Carbon\Carbon::parse($payments[$i]['date'])->format('d M, Y') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        @if(isset($payments[$i]))
                                            - {{ number_format($payments[$i]['amount']) }}
                                        @endif
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td>TOTAL RECEIPTS</td>
                                    <td class="text-end">{{ number_format($totalReceipts) }}</td>
                                    <td>TOTAL PAYMENTS</td>
                                    <td class="text-end text-danger">- {{ number_format($totalPayments) }}</td>
                                </tr>
                                <tr class="grand-total-row">
                                    <td colspan="2">Grand Total: {{ number_format($totalReceipts) }}</td>
                                    <td colspan="2">Grand Total: {{ number_format($totalPayments) }}</td>
                                </tr>
                                <tr class="text-center">
                                    <td colspan="4" class="p-3" style="background-color: #fff; color: red; font-size: 1.3rem; font-weight: 900; letter-spacing: 1px;">
                                        NET BALANCE: {{ number_format($totalReceipts - $totalPayments) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('admin_panel.include.footer_include')
</body>
</html>
