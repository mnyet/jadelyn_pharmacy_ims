<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <div class="container mt-5">
        <h1>Transaction List</h1>
        <div class="container mt-5">
            <div class="input-group mb-3" style="width: 100%;">
                <input type="text" id="transactionSearchBar" class="form-control" placeholder="Search by Transaction Number...">
                
                <button class="btn btn-primary" type="button" id="btnSearch">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
            <div class="table-responsive">
                <table id="transactionListTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Transaction Number</th>
                            <th>Purchase Date</th>
                            <th>Total Amount</th>
                            <th>Employee</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Transaction Detail Modal -->
    <div class="modal fade" id="transactionDetailModal" tabindex="-1" aria-labelledby="transactionDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionDetailModalLabel">
                        <i class="fas fa-receipt me-2"></i>Transaction Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="transactionDetailBody">

                    <!-- Transaction Meta Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block mb-1">Transaction Number</small>
                                <strong class="fs-5" id="tdTransactionCode">—</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block mb-1">Date & Time</small>
                                <strong id="tdTransactionDate">—</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block mb-1">Cashier</small>
                                <strong id="tdCashier">—</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block mb-1">Items Sold</small>
                                <strong id="tdItemCount">—</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Products DataTable -->
                    <h6 class="mb-2">
                        <i class="fas fa-boxes me-1"></i> Products
                    </h6>
                    <div class="table-responsive">
                        <table id="transactionDetailTable" class="table table-sm table-bordered table-hover" style="width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Lot No.</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <!-- Total -->
                    <div class="d-flex justify-content-end mt-3">
                        <div class="border rounded p-3" style="min-width: 220px;">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total:</span>
                                <strong class="fs-4 text-success" id="tdTotal">₱0.00</strong>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jadelyn-pharmacy/TransactionListView.js') ?>"></script>
<?= $this->endSection() ?>