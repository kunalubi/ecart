<?php $this->extend('admin/layout'); ?>
<?php $this->section('content'); ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Users - <?= esc($store['name']) ?></h4>
        <?php if (has_permission(session('role_id'), 'users', 'add')): ?>
            <a href="<?= base_url('admin/add-user') ?>" class="btn btn-primary">Add User</a>
        <?php endif; ?>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= esc($user['name']) ?></td>
                        <td><?= esc($user['email']) ?></td>
                        <td><?= esc($user['role']) ?></td>
                        <td><?= esc($user['created_at']) ?></td>
                        <td>
                            <?php if (has_permission(session('role_id'), 'users', 'edit')): ?>
                                <a href="<?= base_url('admin/edit-user/' . $user['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <?php endif; ?>
                            <?php if (has_permission(session('role_id'), 'users', 'delete')): ?>
                                <a href="<?= base_url('admin/delete-user/' . $user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">No users found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $this->endSection(); ?> 