<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <div class="container mt-5">
        <h1>Transaction List</h1>
        <div class="container mt-5">
            <div class="input-group mb-3" style="width: 100%;">
                <select class="form-select" id="searchBy" style="max-width: 170px;">
                    <option value="<?= SearchTypes::PRODUCT_NAME ?>">Product Name</option>
                    <option value="<?= SearchTypes::CATEGORY ?>">Category</option>
                    <option value="<?= SearchTypes::BRAND ?>">Brand</option>
                    <option value="<?= SearchTypes::LOT_NUMBER ?>">Lot Number</option>
                </select>

                <input type="text" id="productSearchBar" class="form-control" placeholder="Search products...">
                
                <button class="btn btn-primary" type="button" id="btnSearch">
                    <i class="fas fa-search"></i> Search
                </button>
                <button class="btn btn-success" id="btnAddProduct">
                    <i class="fas fa-plus"></i> Add New Product
                </button>
            </div>
            <table id="productListTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Purchase Date</th>
                        <th>Lot Number</th>
                        <th>Expiry Date</th>
                        <th>Product Name</th>
                        <th>Brand</th>
                        <th>Product Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="productForm">
                    <div class="modal-body">
                        <input type="hidden" id="productIdModal" name="id">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Product Name</label>
                                <input type="text" class="form-control" name="name" id="modal_product_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Lot Number</label>
                                <input type="text" class="form-control" name="lot_number" id="modal_lot_number" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Brand</label>
                                <select class="form-select" name="brand_id" id="modal_brand_id" required>
                                    <option value="" selected disabled>Select Brand...</option>
                                    <?php if(!empty($brands)): ?>
                                        <?php foreach ($brands as $brand): ?>
                                            <option value="<?= $brand->id ?>"><?= $brand->name ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Product Type</label>
                                <select class="form-select" name="product_type_id" id="modal_product_type_id" required>
                                    <option value="" selected disabled>Select Type...</option>
                                    <?php if(!empty($productTypes)): ?>
                                        <?php foreach ($productTypes as $type): ?>
                                            <option value="<?= $type->id ?>"><?= $type->name ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="text" 
                                        class="form-control" 
                                        name="price" 
                                        id="modal_price" 
                                        step="0.01" 
                                        min="0.00"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                        placeholder="0.00" 
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Expiry Date</label>
                                <input type="date" class="form-control" name="expiry_date" id="modal_expiry_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Purchase Date</label>
                                <input type="date" class="form-control" name="purchase_date" id="modal_purchase_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="btnSaveProduct">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jadelyn-pharmacy/ProductListView.js') ?>"></script>
<?= $this->endSection() ?>