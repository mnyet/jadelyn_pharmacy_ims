<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <input type="hidden" id="managementType" value="<?= $managementType ?>">
    <div class="container mt-5">
        <?php
            $managementTypeName = 'Unknown';

            if ($managementType == 1) {
                $managementTypeName = 'Generic Name';
            } elseif ($managementType == 2) {
                $managementTypeName = 'Product Type';
            } elseif ($managementType == 3) {
                $managementTypeName = 'User';
            } elseif ($managementType == 4) {
                $managementTypeName = 'User Role';
            } elseif ($managementType == 5) {
                $managementTypeName = 'Brand';
            }
        ?>
        <h1><?= $managementTypeName ?> List</h1>
        <div class="container mt-5">
            <div class="input-group mb-3" style="width: 100%;">
                <input type="text" id="searchText" class="form-control" placeholder="Search <?= $managementTypeName ?>...">
                
                <button class="btn btn-primary" type="button" id="btnSearch">
                    <i class="fas fa-search"></i> Search
                </button>
                <button class="btn btn-success" id="btnAdd">
                    <i class="fas fa-plus"></i> Add <?= $managementTypeName ?>
                </button>
            </div>
            <div class="table-responsive">
                <table id="managementListTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th><?= $managementTypeName ?></th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="managementModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="managementModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="managementModalLabel">Manage Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="managementForm">
                    <div class="modal-body">
                        <input type="hidden" id="mgmtId" name="id">
                        
                        <div class="mb-3" id="mgmtNameSection">
                            <label for="mgmtName" class="form-label fw-bold">Name</label>
                            <input type="text" 
                                class="form-control" 
                                id="mgmtName" 
                                name="name" 
                                placeholder="Enter name here..." 
                                required 
                                autocomplete="off">
                            <div class="form-text">Ensure the name is unique and correctly spelled.</div>
                        </div>

                        <!-- Form for User Role -->
                        <div id="userRoleManagementSection" style="display: none;">
                            <div class="mb-3">
                                <label for="mgmtRoleCode" class="form-label fw-bold">Role Code</label>
                                <input type="text" class="form-control" id="mgmtRoleCode" name="rolecode" placeholder="Enter role code here..." autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label for="mgmtRoleDescription" class="form-label fw-bold">Description (Optional)</label>
                                <input type="text" class="form-control" id="mgmtRoleDescription" name="description" placeholder="Enter description here..." autocomplete="off">
                            </div>
                        </div>
                        

                        <!-- Form for User Management -->
                        <div id="userManagementSection" style="display: none;">
                            <div class="mb-3">
                                <label for="mgmtRole" class="form-label fw-bold">Role</label>
                                <select class="form-select" id="mgmtRole" name="role_id">
                                    <option value="">Select Role</option>
                                    <?php if (!empty($userRoles)): ?>
                                        <?php foreach ($userRoles as $role): ?>
                                            <option value="<?= $role->id ?>"><?= $role->name ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="mgmtUsername" class="form-label fw-bold">Username</label>
                                <input type="text" class="form-control" id="mgmtUsername" name="username" placeholder="Enter username here..." autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label for="mgmtEmail" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" id="mgmtEmail" name="email" placeholder="Enter email here..." autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label for="mgmtPassword" class="form-label fw-bold">Password</label>
                                <input type="password" class="form-control" id="mgmtPassword" name="password" placeholder="Enter password here..." autocomplete="off">
                                
                                <div class="form-check form-switch mt-3" id="changePasswordSection" style="display: none;">
                                    <input class="form-check-input" type="checkbox" id="enablePasswordChange">
                                    <label class="form-check-label fw-bold" for="enablePasswordChange text-primary">
                                        Change Password?
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="btnSaveManagement">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jadelyn-pharmacy/ManagementView.js') ?>"></script>
<?= $this->endSection() ?>