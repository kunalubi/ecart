<?php helper('permission'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($store) ? esc($store['name']) : 'Admin Panel' ?> Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: #fff;
        }
        .sidebar a { color: #fff; text-decoration: none; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; }
        .category-tree ul { list-style: none; padding-left: 1rem; }
        .category-tree li { margin-bottom: 0.25rem; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('admin/dashboard') ?>"><?= isset($store) ? esc($store['name']) : 'Admin Panel' ?></a>
        <div class="d-flex align-items-center ms-auto">
            <span class="text-white me-3"><i class="bi bi-person-circle"></i> <?= esc(session('name')) ?></span>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar py-4">
            <div class="position-sticky">
                <h5 class="text-center mb-4">Admin</h5>
                <ul class="nav flex-column mb-4">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <?php if (has_permission(session('role_id'), 'categories', 'view')): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/categories') ?>">Categories</a></li>
                    <?php endif; ?>
                    <?php if (has_permission(session('role_id'), 'products', 'view')): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/products') ?>">Products</a></li>
                    <?php endif; ?>
                    <?php if (has_permission(session('role_id'), 'orders', 'view')): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/orders') ?>">Orders</a></li>
                    <?php endif; ?>
                    <?php if (has_permission(session('role_id'), 'users', 'view')): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/users') ?>">Users</a></li>
                    <?php endif; ?>
                    <?php if (has_permission(session('role_id'), 'roles', 'view')): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/roles') ?>">Roles</a></li>
                    <?php endif; ?>
                    <?php if (has_permission(session('role_id'), 'inventory', 'view')): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/inventory') ?>">Inventory</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('profile/view') ?>">Profile</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="<?= base_url('admin/logout') ?>">Logout</a></li>
                </ul>
                <div class="category-tree">
                    <h6 class="text-white-50">Category Tree</h6>
                    <?php
                    // Dummy expandable tree for now
                    function renderTree($categories, $parent_id = null) {
                        $hasChild = false;
                        foreach ($categories as $cat) {
                            if ($cat['parent_id'] == $parent_id) {
                                if (!$hasChild) {
                                    echo '<ul>';
                                    $hasChild = true;
                                }
                                echo '<li>' . esc($cat['name']);
                                renderTree($categories, $cat['id']);
                                echo '</li>';
                            }
                        }
                        if ($hasChild) echo '</ul>';
                    }
                    if (isset($categories)) renderTree($categories);
                    ?>
                </div>
            </div>
        </nav>
        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>
                Admin Panel
                </h3>
            </div>
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>

</body>
</html> 