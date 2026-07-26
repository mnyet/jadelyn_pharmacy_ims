<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <div class="container mt-5">
        <h1>Product Pricing</h1>
        <div class="container mt-5">
            <div class="input-group mb-3" style="width: 100%;">
                <select class="form-select" id="searchBy" style="max-width: 170px;">
                    <option value="<?= SearchTypes::BRAND_NAME ?>">Brand Name</option>
                    <option value="<?= SearchTypes::GENERIC_NAME ?>">Generic Name</option>
                </select>

                <input type="text" id="productSearchBar" class="form-control" placeholder="Search products...">
                
                <button class="btn btn-primary" type="button" id="btnSearch">
                    <i class="fas fa-search"></i> Search
                </button>
                <button class="btn btn-success" id="btnAddProduct">
                    <i class="fas fa-plus"></i> Add New Pricing
                </button>
            </div>
            <div class="table-responsive">
                <table id="productPriceTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Generic Name</th>
                            <th>Brand</th>
                            <th>Product Type</th>
                            <th>Unit Price</th>
                            <th>Selling Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="productPriceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="productPriceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productPriceModalLabel">Add price on a product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="productPriceForm">
                    <div class="modal-body">
                        <input type="hidden" id="productPriceIdModal" name="id">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Generic Name</label>
                                <select name="generic_name_id" id="modal_generic_name_id" class="form-select" required>
                                    <option value="" selected disabled>Select Generic Name...</option>
                                    <?php if(!empty($genericNames)): ?>
                                        <?php foreach ($genericNames as $genericName): ?>
                                            <option value="<?= $genericName->id ?>"><?= $genericName->name ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Brand</label>
                                <select name="brand_id" id="modal_brand_id" class="form-select" required>
                                    <option value="" selected disabled>Select Brand...</option>
                                    <?php if(!empty($brandNames)): ?>
                                        <?php foreach ($brandNames as $brand): ?>
                                            <option value="<?= $brand->id ?>"><?= $brand->name ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Product Type</label>
                                <select name="product_type_id" id="modal_product_type_id" required>
                                    <option value="" selected disabled>Select Type...</option>
                                    <?php if(!empty($productTypes)): ?>
                                        <?php foreach ($productTypes as $type): ?>
                                            <option value="<?= $type->id ?>"><?= $type->name ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Unit Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="text" 
                                        class="form-control" 
                                        name="price" 
                                        id="modal_unit_price" 
                                        step="0.01" 
                                        min="0.00"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                        placeholder="0.00" 
                                        required>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <label class="form-label fw-bold">Selling Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="text" 
                                        class="form-control" 
                                        name="price" 
                                        id="modal_selling_price" 
                                        step="0.01" 
                                        min="0.00"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                        placeholder="0.00" 
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="btnSaveProductPrice">Save Pricing</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jadelyn-pharmacy/ProductPricingView.js') ?>"></script>
<?= $this->endSection() ?>