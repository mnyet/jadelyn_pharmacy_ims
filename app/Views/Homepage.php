<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <div class="container mt-5">
        <h1 class="mb-4">Dashboard</h1>
        <p class="text-muted mb-4">Bootstrap is working globally!</p>
        
        <!-- Dashboard Stats Grid -->
        <div class="row g-4 mb-4">
            <!-- Total Inventory Products -->
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100 bg-secondary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-normal mb-2">Inventory Items</h6>
                                <h2 class="mb-0 text-white"><?= $totalInventory ?? 0 ?></h2>
                            </div>
                            <div class="rounded-circle bg-white bg-opacity-25 p-3">
                                <i class="fas fa-warehouse fs-4 text-white"></i>
                            </div>
                        </div>
                        <a href="/product-list" class="text-white text-decoration-none small opacity-75">View All →</a>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-normal mb-2">Total Products</h6>
                                <h2 class="mb-0 text-white"><?= $totalProducts ?? 0 ?></h2>
                            </div>
                            <div class="rounded-circle bg-white bg-opacity-25 p-3">
                                <i class="fas fa-boxes fs-4 text-white"></i>
                            </div>
                        </div>
                        <a href="/product-pricing" class="text-white text-decoration-none small opacity-75">View All →</a>
                    </div>
                </div>
            </div>

            <!-- Low Stock Items -->
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100 bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-dark-50 text-uppercase fw-normal mb-2">Low Stock Items</h6>
                                <h2 class="mb-0 text-dark"><?= $lowStockItems ?? 0 ?></h2>
                            </div>
                            <div class="rounded-circle bg-dark bg-opacity-10 p-3">
                                <i class="fas fa-exclamation-triangle fs-4 text-dark"></i>
                            </div>
                        </div>
                        <a href="/product-list" class="text-dark text-decoration-none small opacity-75">View All →</a>
                    </div>
                </div>
            </div>

            <!-- Total Brands -->
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100 bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-normal mb-2">Total Brands</h6>
                                <h2 class="mb-0 text-white"><?= $totalBrands ?? 0 ?></h2>
                            </div>
                            <div class="rounded-circle bg-white bg-opacity-25 p-3">
                                <i class="fas fa-tag fs-4 text-white"></i>
                            </div>
                        </div>
                        <a href="/management/<?= ManagementTypes::BRANDS ?>" class="text-white text-decoration-none small opacity-75">View All →</a>
                    </div>
                </div>
            </div>

            <!-- Total Generic Products -->
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100 bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-normal mb-2">Generic Products</h6>
                                <h2 class="mb-0 text-white"><?= $totalGenericProducts ?? 0 ?></h2>
                            </div>
                            <div class="rounded-circle bg-white bg-opacity-25 p-3">
                                <i class="fas fa-capsules fs-4 text-white"></i>
                            </div>
                        </div>
                        <a href="/management/<?= ManagementTypes::GENERIC_NAME ?>" class="text-white text-decoration-none small opacity-75">View All →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome Alert -->
        <div class="alert alert-info border-0 shadow-sm">
            <i class="fas fa-info-circle me-2"></i>
            Welcome to the Jadelyn Pharmacy Management System. Use the navigation bar above to manage your inventory.
        </div>

        <!-- Recent Activity & Quick Actions -->
        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Inventory Updates</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-0">No recent updates yet.</p>
                        <!-- Add recent activity list here -->
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Add Product</a>
                            <a href="#" class="btn btn-success btn-sm"><i class="fas fa-chart-bar me-1"></i> Generate Report</a>
                            <a href="#" class="btn btn-warning btn-sm"><i class="fas fa-exclamation-circle me-1"></i> Check Stock</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>