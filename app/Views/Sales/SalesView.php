<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <div class="container-fluid mt-5">
        <div class="row g-4">
            <!-- LEFT COLUMN: Product List -->
            <div class="col-lg-8">
                <div class="ps-5">
                    <!-- Sales Header -->
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-cash-register fs-4 me-3"></i>
                        <h2 class="mb-0">Sales</h2>
                    </div>

                    <!-- Action Buttons Row -->
                    <div class="d-flex gap-2 mb-4 flex-wrap">
                        <button class="btn btn-outline-dark btn-sm" id="btnQuickSale" disabled>
                            <i class="fas fa-bolt me-1"></i> Quick Sale (F1) (Soon)
                        </button>
                        <button class="btn btn-outline-dark btn-sm" id="btnClearAll">
                            Clear All
                        </button>
                    </div>

                    <!-- Product Search Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Product Search</h5>
                                <span class="badge bg-light text-dark border">Ctrl+F to focus</span>
                            </div>
                            
                            <div class="input-group mb-4">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="productSearchBar" class="form-control border-start-0 bg-light" placeholder="Search products by name or lot number...">
                            </div>

                            <!-- Product Table -->
                            <div class="table-responsive">
                                <table id="productListTable" class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Lot Number</th>
                                            <th>Product</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Shopping Cart + Recent Transactions -->
            <div class="col-lg-4">
                <div class="pe-5">
                    <!-- Shopping Cart Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <!-- Cart Header -->
                            <div class="d-flex align-items-center mb-4">
                                <i class="fas fa-shopping-cart fs-5 me-2"></i>
                                <h5 class="mb-0">Cart (<span id="cartItemCount">0</span>)</h5>
                            </div>

                            <!-- Cart Items -->
                            <div class="cart-items mb-4">
                                <!-- Cart items will be dynamically added here -->
                            </div>

                            <!-- Cart Totals -->
                            <div class="border-top pt-3 mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span id="cart-subtotal">₱0.00</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2">
                                    <span>Total:</span>
                                    <span id="cart-total">₱0.00</span>
                                </div>
                            </div>

                            <!-- Process Payment Button -->
                            <button class="btn btn-dark w-100 py-3" id="btnProcessPayment">
                                <i class="fas fa-cash-register me-2"></i> Process Payment (Ctrl+Enter)
                            </button>
                        </div>
                    </div>

                    <!-- Recent Transactions Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <!-- Header -->
                            <div class="d-flex align-items-center mb-4">
                                <i class="fas fa-history fs-5 me-2"></i>
                                <h5 class="mb-0">Recent Transactions</h5>
                            </div>

                            <!-- Transaction List -->
                            <div class="transaction-list">
                                <!-- Transactions will be rendered here by JS -->
                                     <table id="transactionListTable" class="table table-borderless mb-0">
                                        <thead class="d-none">
                                            <tr><th>Transaction</th></tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jadelyn-pharmacy/SalesView.js') ?>"></script>

    <!-- Templates -->
     <template id="cartItemTemplate">
        <div class="cart-item border rounded p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <div class="fw-semibold product-name"></div>
                    <small class="text-muted product-price-label"></small>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-dark btn-sm btn-qty-minus" data-id="">
                        <i class="fas fa-minus"></i>
                    </button>
                    <span class="fw-semibold cart-item-qty">1</span>
                    <button class="btn btn-outline-dark btn-sm btn-qty-plus" data-id="">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <button class="btn btn-danger btn-sm btn-remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </template>
<?= $this->endSection() ?>