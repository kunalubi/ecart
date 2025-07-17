<?php $this->extend('admin/layout'); ?>
<?php $this->section('content'); ?>
<div class="container mt-4">
    <h4>Edit Profile</h4>
    <?php if ($error): ?><div class="alert alert-danger"><?= esc($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= esc($success) ?></div><?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= esc($user['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= esc($user['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $this->endSection(); ?> 