<?php $this->extend('admin/layout'); ?>
<?php $this->section('content'); ?>

<h2 class="mb-4">Roles Management</h2>
<?php if (!empty($error)): ?>
    <div class="alert alert-danger"> <?= esc($error) ?> </div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"> <?= esc($success) ?> </div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">Add New Role</div>
    <div class="card-body">
        <form method="post" class="row g-3">
            <div class="col-md-5">
                <label for="roleName" class="form-label">Role Name</label>
                <input type="text" class="form-control" id="roleName" name="name" required>
            </div>
            <div class="col-md-5">
                <label for="roleDesc" class="form-label">Description</label>
                <input type="text" class="form-control" id="roleDesc" name="description">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Add Role</button>
            </div>
        </form>
    </div>
</div>

<h3 class="mb-3">Existing Roles</h3>
<?php if (empty($roles)): ?>
    <div class="alert alert-info">No roles found for this store.</div>
<?php else: ?>
<table class="table table-bordered table-striped align-middle">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Manage Permissions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($roles as $role): ?>
        <tr>
            <td><?= esc($role['id']) ?></td>
            <td><?= esc($role['name']) ?></td>
            <td><?= esc($role['description']) ?></td>
            <td><a class="btn btn-sm btn-outline-primary" href="<?= base_url('admin/manageRolePermissions/' . $role['id']) ?>">Manage Permissions</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?php $this->endSection(); ?> 