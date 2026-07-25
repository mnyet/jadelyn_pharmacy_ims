<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="card login-card mx-auto col-lg-5 col-md-6">
        <div class="card-body p-5">
            <h5 class="text-center mb-4 text-muted">Pharmacy Internal Management System</h5>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger py-2 small">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('auth/login-verify') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>