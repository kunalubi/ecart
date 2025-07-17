<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Products - <?= esc($store['name']) ?></h4>
        <a href="<?= base_url('admin/add-product') ?>" class="btn btn-light">Add Product</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $prod): ?>
                            <tr>
                                <td><?= esc($prod['name']) ?></td>
                                <td>
                                    <?php if (!empty($prod['image'])): ?>
                                        <img src="<?= base_url('../public/uploads/' . $store['id'] . '/' . $prod['image']) ?>" alt="Product Image" style="width:50px;height:50px;object-fit:cover;">
                                    <?php else: ?>
                                        <span class="text-muted">No image</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $catName = '-';
                                    foreach ($categories as $cat) {
                                        if ($cat['id'] == $prod['category_id']) {
                                            $catName = $cat['name'];
                                            break;
                                        }
                                    }
                                    echo esc($catName);
                                    ?>
                                </td>
                                <td><?= esc($prod['price']) ?></td>
                                <td><?= esc($prod['created_at']) ?></td>
                                <td>
                                    <?php if (has_permission(session('role_id'), 'products', 'edit')): ?>
                                        <a href="<?= base_url('admin/edit-product/' . $prod['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <?php endif; ?>
                                    <?php if (has_permission(session('role_id'), 'products', 'delete')): ?>
                                        <a href="<?= base_url('admin/delete-product/' . $prod['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">No products found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
