<?php $this->extend('admin/layout'); ?>
<?php $this->section('content'); ?>

<h2 class="mb-4">Manage Role Permissions</h2>
<?php if (!empty($error)): ?>
    <div class="alert alert-danger"> <?= esc($error) ?> </div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"> <?= esc($success) ?> </div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">Select Role</div>
    <div class="card-body">
        <form method="get" action="<?= base_url('admin/manageRolePermissions') ?>" class="row g-3">
            <div class="col-md-8">
                <select name="roleId" class="form-select" onchange="this.form.submit()">
                    <option value="">--Select Role--</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= esc($role['id']) ?>" <?= isset($selectedRole) && $selectedRole && $selectedRole['id'] == $role['id'] ? 'selected' : '' ?>><?= esc($role['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<?php if (empty($roles)): ?>
    <div class="alert alert-info">No roles found for this store. Please add a role first.</div>
<?php elseif (empty($permissions)): ?>
    <div class="alert alert-warning">No permissions found. Please add permissions in the database.</div>
<?php elseif (isset($selectedRole) && $selectedRole): ?>
    <h4 class="mb-3">Permissions for: <span class="text-primary"><?= esc($selectedRole['name']) ?></span></h4>
    <form method="post">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Module</th>
                    <th>Action</th>
                    <th>Allow</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($permissions as $perm): ?>
                <tr>
                    <td><?= esc($perm['module']) ?></td>
                    <td><?= esc($perm['action']) ?></td>
                    <td><input type="checkbox" name="permissions[]" value="<?= esc($perm['id']) ?>" <?= in_array($perm['id'], $rolePermissions) ? 'checked' : '' ?>></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-success">Update Permissions</button>
    </form>
<?php endif; ?>

<?php $this->endSection(); ?> 