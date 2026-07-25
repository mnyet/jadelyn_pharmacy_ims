<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 text-center">
                <div class="mb-4">
                    <i class="bi bi-shield-lock-fill text-danger" style="font-size: 5rem;"></i>
                </div>
                
                <div class="alert alert-danger shadow-sm py-4">
                    <h5 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Unauthorized Access</h5>
                    <p class="mb-0">
                        You do not have the administrative privileges required to access this section of the <strong>Jadelyn Pharmacy System</strong>.
                    </p>
                </div>

                <p class="text-muted mb-4">
                    If you believe this is a mistake, please contact the system administrator to update your user role.
                </p>

                <a href="<?= base_url('/') ?>" class="btn btn-outline-primary px-4">
                    <i class="bi bi-arrow-left me-2"></i> Return to Homepage
                </a>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>