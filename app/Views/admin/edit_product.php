<?php $this->extend('admin/layout'); ?>
<?php $this->section('content'); ?>
<div class="container mt-4">
    <h4>Edit Product - <?= esc($store['name']) ?></h4>
    <?php if ($error): ?><div class="alert alert-danger"><?= esc($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= esc($success) ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= esc($product['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required><?= esc($product['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= esc($product['price']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <?php if ($product['image']): ?>
                <div class="mb-2">
                    <img src="<?= base_url('../public/uploads/' . $store['id'] . '/' . $product['image']) ?>" alt="Product Image" style="width:80px;height:80px;object-fit:cover;">
                </div>
            <?php endif; ?>
            <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg">
            <small class="text-muted">Only PNG/JPG allowed. Leave blank to keep current image.</small>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="<?= base_url('admin/products') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $this->endSection(); ?> 