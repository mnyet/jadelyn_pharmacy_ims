<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            <i class="bi bi-capsule me-2"></i>Jadelyn Pharmacy
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-2">
                <?php if (session()->get('isLoggedIn')):?>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary text-white border-0" href="/product-list">
                            Product List (Inventory)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary text-white border-0" href="/product-pricing">
                            Product Pricing
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-success text-white border-0" href="/transaction-list">
                            Transactions
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="btn btn-outline-info text-white border-0 dropdown-toggle" href="#" id="mgmtDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Management
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark shadow" aria-labelledby="mgmtDropdown">
                            <li><a class="dropdown-item" href="/management/<?= ManagementTypes::GENERIC_NAME ?>">Generic Names</a></li>
                            <li><a class="dropdown-item" href="/management/<?= ManagementTypes::BRANDS ?>">Brands</a></li>
                            <li><a class="dropdown-item" href="/management/<?= ManagementTypes::PRODUCT_TYPE ?>">Product Types</a></li>
                            
                            <?php if (session()->get('userRoleId') == UserRoles::ADMIN): ?>
                                <li><hr class="dropdown-divider border-light opacity-50"></li>
                                <li>
                                    <a class="dropdown-item" href="/management/<?= ManagementTypes::USERS ?>">
                                        Manage Users
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/management/<?= ManagementTypes::ROLES ?>">
                                        Manage User Roles
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-danger text-white border-0" href="/logout">
                            Logout (<?= session()->get('username') ?>)
                        </a>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>