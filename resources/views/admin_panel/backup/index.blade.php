@include('admin_panel.include.header_include')

<body>
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        @include('admin_panel.include.navbar_include')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h4 class="mb-0"><i class="fas fa-database me-2"></i> System Backup Management</h4>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info border-start border-info border-4">
                                    <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i> How it works?</h5>
                                    <p class="mb-0">You can manually download a full database backup in <strong>.SQL</strong> format or trigger an email backup to <strong>janmuhammad1917@gmail.com</strong>.</p>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6 mb-3">
                                        <div class="p-4 border rounded text-center bg-light">
                                            <i class="fas fa-file-download fa-3x text-success mb-3"></i>
                                            <h4>Manual SQL Download</h4>
                                            <p class="text-muted">Generate a full database dump and download it directly to your computer.</p>
                                            <a href="{{ route('backup.download.sql') }}" class="btn btn-success btn-lg">
                                                <i class="fas fa-download me-2"></i> Download .SQL Backup
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="p-4 border rounded text-center bg-light">
                                            <i class="fas fa-envelope-open-text fa-3x text-primary mb-3"></i>
                                            <h4>Email Backup Test</h4>
                                            <p class="text-muted">Send a test SQL backup to your registered email address immediately.</p>
                                            <form action="{{ route('backup.trigger.email') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-paper-plane me-2"></i> Send Backup to Email
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 p-3 bg-dark text-white rounded">
                                    <h6><i class="fas fa-clock me-2"></i> Automatic Daily Backup Status:</h6>
                                    <p class="small mb-0 text-success">
                                        <i class="fas fa-check-circle me-1"></i> Enabled - Scheduled to run daily at 11:59 PM.
                                    </p>
                                    <p class="small text-warning mt-2 mb-0">
                                        <i class="fas fa-exclamation-triangle me-1"></i> <strong>Important:</strong> Ensure your SMTP settings in <code>.env</code> are correct for email backups to work.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin_panel.include.footer_include')
</body>
