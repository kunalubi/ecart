<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<h2>Edit Category</h2>
<?php if ($error): ?><div class="alert alert-danger"><?= esc($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= esc($success) ?></div><?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="<?= esc($category['name']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Parent Category</label>
        <input type="text" name="parent_id" class="form-control" value="<?= esc($category['parent_id']) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= base_url('admin/categories') ?>" class="btn btn-secondary">Back</a>
</form>
<?= $this->endSection() ?>