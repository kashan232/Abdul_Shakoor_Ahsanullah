@include('admin_panel.include.header_include')

<style>
    .premium-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.03);
        overflow: hidden;
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
    .btn-premium {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 12px 25px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(46, 204, 113, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(46, 204, 113, 0.35);
        color: #fff;
    }
    .icon-container {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(46, 204, 113, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    .icon-container i {
        font-size: 2.5rem;
        color: #27ae60;
    }
</style>

<body>
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        @include('admin_panel.include.navbar_include')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                <div class="d-flex mb-4 flex-wrap gap-3 justify-content-between align-items-center">
                    <h6 class="page-title">
                        <i class="fas fa-database"></i> System Backup Management
                    </h6>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="premium-card p-5 text-center mt-4">
                            <div class="icon-container">
                                <i class="fas fa-cloud-download-alt"></i>
                            </div>
                            <h3 class="mb-3 text-dark fw-bold">Manual SQL Backup</h3>
                            <p class="text-muted mb-4" style="font-size: 1.05rem;">
                                Generate a full database dump containing all your latest records, settings, and tables, and download it securely to your local machine.
                            </p>
                            
                            <a href="{{ route('backup.download.sql') }}" class="btn-premium">
                                <i class="fas fa-download"></i> Download .SQL Backup
                            </a>
                            
                            <div class="mt-5 pt-4 border-top">
                                <p class="small text-muted mb-0">
                                    <i class="fas fa-shield-alt text-success me-1"></i> Your data is processed securely and directly without any third-party services.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('admin_panel.include.footer_include')
</body>
