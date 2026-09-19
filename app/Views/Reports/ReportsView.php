<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <div class="container mt-5">
        <div class="container mt-5">
            <input type="hidden" id="baseUrl" value="<?= base_url() ?>">
            <input type="hidden" id="csrfToken" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

            <!-- Page Header -->
            <div class="d-flex align-items-center mb-4">
                <h1 class="mb-0">Reports</h1>
            </div>

            <!-- ============================= -->
            <!-- Product Reports Section       -->
            <!-- ============================= -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">
                        <i class="fas fa-boxes me-2"></i>Product Reports
                    </h5>

                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label for="productReportType" class="form-label fw-semibold">Report Type</label>
                            <select id="productReportType" class="form-select">
                                <option value="" selected disabled>Select a report...</option>
                                <?php if(!empty($productReports)): ?>
                                    <?php foreach ($productReports as $report): ?>
                                        <option
                                            value="<?= $report->id ?>"
                                        >
                                            <?= $report->report_name ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" id="btnGenerateProductReport">
                                <i class="fas fa-file-alt me-1"></i> Generate
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Separator -->
            <hr class="my-4 opacity-25">

            <!-- ============================= -->
            <!-- Sales Reports Section          -->
            <!-- ============================= -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">
                        <i class="fas fa-cash-register me-2"></i>Sales Reports
                    </h5>

                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="salesReportType" class="form-label fw-semibold">Report Type</label>
                            <select id="salesReportType" class="form-select">
                                <option value="" selected disabled>Select a report...</option>
                                <?php if(!empty($salesReports)): ?>
                                    <?php foreach ($salesReports as $report): ?>
                                        <option
                                            value="<?= $report->id ?>"
                                            data-is-periodic="<?= $report->is_periodic ?>"
                                        >
                                            <?= $report->report_name ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="salesReportMonth" class="form-label fw-semibold">Month</label>
                            <select id="salesReportMonth" class="form-select">
                                <option value="" selected disabled>Month</option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="salesReportYear" class="form-label fw-semibold">Year</label>
                            <select id="salesReportYear" class="form-select">
                                <option value="" selected disabled>Year</option>
                                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                    <option value="<?= $y ?>"><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" id="btnGenerateSalesReport">
                                <i class="fas fa-file-alt me-1"></i> Generate
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="<?= base_url('assets/js/jadelyn-pharmacy/ReportsView.js') ?>"></script>
<?= $this->endSection() ?>