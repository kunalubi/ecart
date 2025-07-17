<?php $this->extend('admin/layout'); ?>
<?php $this->section('content'); ?>

<h2 class="mb-4">Manage Role Permissions</h2>
<?php if (!empty($error)): ?>
    <div class="alert alert-danger"> <?= esc($error) ?> </div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"> <?= esc($success) ?> </div>
<?php endif; ?>

<div class="card shadow-sm mb-4" style="max-width: 900px; margin:auto;">
    <div class="card-header bg-primary text-white py-2">
        <form method="get" action="<?= base_url('admin/manageRolePermissions') ?>" class="row g-3 align-items-center mb-0">
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
    <div class="card-body p-3">
<?php if (empty($roles)): ?>
    <div class="alert alert-info">No roles found for this store. Please add a role first.</div>
<?php elseif (empty($permissions)): ?>
    <div class="alert alert-warning">No permissions found. Please add permissions in the database.</div>
<?php elseif (isset($selectedRole) && $selectedRole): ?>
    <form method="post">
        <table class="table table-sm table-bordered table-striped align-middle mb-2 text-center" style="font-size:0.95rem;">
            <thead class="table-light">
                <tr>
                    <th style="width:25%">Module</th>
                    <th style="width:15%">View</th>
                    <th style="width:15%">Add</th>
                    <th style="width:15%">Edit</th>
                    <th style="width:15%">Delete</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Group permissions by module
            $modules = [];
            foreach ($permissions as $perm) {
                $modules[$perm['module']][$perm['action']] = $perm['id'];
            }
            $actions = ['view', 'add', 'edit', 'delete'];
            ?>
            <?php foreach ($modules as $module => $acts): ?>
                <tr>
                    <td class="text-start"><strong><?= esc(ucfirst($module)) ?></strong></td>
                    <?php foreach ($actions as $action): ?>
                        <td>
                            <?php if (isset($acts[$action])): ?>
                                <input type="checkbox" name="permissions[]" value="<?= esc($acts[$action]) ?>" <?= in_array($acts[$action], $rolePermissions) ? 'checked' : '' ?> style="width:16px;height:16px;">
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-end">
            <button type="submit" class="btn btn-success btn-sm">Update Permissions</button>
        </div>
    </form>
<?php endif; ?>
    </div>
</div>

<?php $this->endSection(); ?> 