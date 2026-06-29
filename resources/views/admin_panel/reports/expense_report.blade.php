@include('admin_panel.include.header_include')
<body>
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        @include('admin_panel.include.navbar_include')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                <div class="d-flex mb-4 flex-wrap gap-3 justify-content-between align-items-center">
                    <h5 class="page-title fw-bold text-primary"><i class="las la-wallet"></i> Office Expenses Report</h5>
                </div>

                <div class="card shadow-sm border-0 rounded-4 p-4">
                    <form method="GET" action="{{ route('reports.expenses') }}" id="expenseReportForm" class="mb-4 bg-light p-4 rounded-3 border">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Expense Category</label>
                                <select name="category" class="form-select">
                                    <option value="All" {{ $selectedCategory == 'All' ? 'selected' : '' }}>All Expenses</option>
                                    @foreach($availableCategories as $cat)
                                        <option value="{{ $cat->id }}" {{ $selectedCategory == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary w-50 fw-bold"><i class="las la-filter"></i> Filter</button>
                                <a href="{{ route('reports.expenses') }}" class="btn btn-danger w-50 fw-bold"><i class="las la-sync"></i> Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle text-center" id="myTable">
                            <thead style="background: linear-gradient(90deg, #4338ca, #3b82f6); color: white; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                                <tr>
                                    <th class="text-center py-3 border-0">Date</th>
                                    <th class="text-center py-3 border-0">Category</th>
                                    <th class="text-center py-3 border-0">Title</th>
                                    <th class="text-center py-3 border-0">Description</th>
                                    <th class="text-center py-3 border-0">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @forelse($reportData as $data)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($data->expense_date)->format('d M, Y') }}</td>
                                        <td>
                                            <span class="badge rounded-pill bg-primary px-3 py-2 shadow-sm" style="font-size: 0.85rem;">
                                                <i class="las la-tag"></i> {{ $data->category->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="fw-bold text-dark">{{ $data->title }}</td>
                                        <td class="text-muted">{{ $data->description }}</td>
                                        <td class="text-end fw-bold text-danger fs-5">{{ number_format($data->amount) }} <small class="text-muted">PKR</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No expenses found for the selected criteria.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($reportData) > 0)
                                <tfoot>
                                    <tr class="table-secondary">
                                        <td colspan="4" class="text-end fw-bold fs-5 text-dark">Grand Total:</td>
                                        <td class="text-end text-danger fw-bolder fs-4">{{ number_format($totalAmount) }} <small class="text-muted fs-6">PKR</small></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin_panel.include.footer_include')
</body>
</html>
