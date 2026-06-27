@include('admin_panel.include.header_include')

<body>
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">

        <!-- sidebar start -->

        @include('admin_panel.include.sidebar_include')
        <!-- sidebar end -->

        <!-- navbar-wrapper start -->
        @include('admin_panel.include.navbar_include')
        <!-- navbar-wrapper end -->

        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                <div class="d-flex mb-30 flex-wrap gap-3 justify-content-between align-items-center">
                    <h6 class="page-title">Expense Records</h6>
                    <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center breadcrumb-plugins">
                        <button type="button" class="btn btn-outline--primary cuModalBtn fw-bold" data-toggle="modal" data-target="#cuModal" data-modal_title="Add Expense Record">
                            <i class="las la-plus"></i>Add New
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card b-radius--10">
                            <div class="card-body p-0">
                                @if (session()->has('success'))
                                <div class="alert alert-success">
                                    <strong>Success!</strong> {{ session('success') }}.
                                </div>
                                @endif
                                <div class="table-responsive--sm table-responsive">
                                    <table id="example" class="display table table--light" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>S.N.</th>
                                                <th>Date</th>
                                                <th>Category</th>
                                                <th>Title</th>
                                                <th>Amount</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expenses as $expense)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M, Y') }}</td>
                                                <td>{{ $expense->category->name ?? '-' }}</td>
                                                <td>{{ $expense->title }}</td>
                                                <td>{{ number_format($expense->amount, 2) }}</td>
                                                <td>{{ $expense->description }}</td>
                                                <td>
                                                    <div class="button--group">
                                                        <button type="button" class="btn btn-outline-primary editExpenseBtn fw-bold"
                                                            data-toggle="modal"
                                                            data-target="#editExpenseModal"
                                                            data-expense-id="{{ $expense->id }}"
                                                            data-category-id="{{ $expense->expense_category_id }}"
                                                            data-expense-title="{{ $expense->title }}"
                                                            data-expense-amount="{{ $expense->amount }}"
                                                            data-expense-date="{{ $expense->expense_date }}"
                                                            data-expense-description="{{ $expense->description }}">
                                                            <i class="la la-pencil"></i>Edit
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table><!-- table end -->
                                </div>
                            </div>
                        </div><!-- card end -->
                    </div>
                </div>

                <!--Add Modal -->
                <div id="cuModal" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><span class="type"></span></h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('store-expense') }}" method="POST">
                                @csrf

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select name="expense_category_id" class="form-control" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="number" step="0.01" name="amount" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn--primary h-45 w-100">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editExpenseLabel">Edit Expense Record</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('update-expense') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" id="editExpenseId" name="expense_id" class="form-control" required>
                                    
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select id="editExpenseCategoryId" name="expense_category_id" class="form-control" required>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" id="editExpenseTitle" name="title" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="number" step="0.01" id="editExpenseAmount" name="amount" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" id="editExpenseDate" name="expense_date" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea id="editExpenseDescription" name="description" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn--primary h-45 w-100">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>
    @include('admin_panel.include.footer_include')

    <script>
        $(document).ready(function() {
            // Edit expense button click event
            $('.editExpenseBtn').click(function() {
                var expenseId = $(this).data('expense-id');
                var categoryId = $(this).data('category-id');
                var title = $(this).data('expense-title');
                var amount = $(this).data('expense-amount');
                var expenseDate = $(this).data('expense-date');
                var description = $(this).data('expense-description');
                
                $('#editExpenseId').val(expenseId);
                $('#editExpenseCategoryId').val(categoryId);
                $('#editExpenseTitle').val(title);
                $('#editExpenseAmount').val(amount);
                $('#editExpenseDate').val(expenseDate);
                $('#editExpenseDescription').val(description);
            });
        });
    </script>
