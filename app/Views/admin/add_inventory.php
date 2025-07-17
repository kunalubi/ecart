<?php $this->extend('admin/layout'); ?>
<?php $this->section('content'); ?>
<div class="container mt-4">
    <h4>Add Inventory - <?= esc($store['name']) ?></h4>
    <?php if ($error): ?><div class="alert alert-danger"><?= esc($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= esc($success) ?></div><?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="product_id" class="form-label">Product</label>
            <select class="form-select" id="product_id" name="product_id" required>
                <option value="">Select Product</option>
                <?php foreach ($products as $prod): ?>
                    <option value="<?= $prod['id'] ?>"><?= esc($prod['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" min="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Inventory</button>
        <a href="<?= base_url('admin/inventory') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $this->endSection(); ?> 