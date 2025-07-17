<?php $this->extend('admin/layout'); ?>
<?php $this->section('content'); ?>
<div class="container mt-4">
    <h4>Inventory - <?= esc($store['name']) ?></h4>
    <?php if (has_permission(session('role_id'), 'inventory', 'add')): ?>
        <a href="<?= base_url('admin/add-inventory') ?>" class="btn btn-primary mb-3">Add Inventory</a>
    <?php endif; ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Product</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($inventory)): ?>
                <?php foreach ($inventory as $inv): ?>
                    <tr>
                        <td><?= esc($inv['product_name']) ?></td>
                        <td><?= esc($inv['stock']) ?></td>
                        <td>
                            <?php if (has_permission(session('role_id'), 'inventory', 'edit')): ?>
                                <a href="<?= base_url('admin/edit-inventory/' . $inv['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <?php endif; ?>
                            <?php if (has_permission(session('role_id'), 'inventory', 'delete')): ?>
                                <a href="<?= base_url('admin/delete-inventory/' . $inv['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3" class="text-center">No inventory records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $this->endSection(); ?> 