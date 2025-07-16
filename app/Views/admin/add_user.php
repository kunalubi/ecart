<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4>Add User - <?= esc($store['name']) ?></h4>
            </div>
            <div class="card-body">
                <?php if(isset($error) && $error): ?>
                    <div class="alert alert-danger"> <?= esc($error) ?> </div>
                <?php endif; ?>
                <?php if(isset($success) && $success): ?>
                    <div class="alert alert-success"> <?= esc($success) ?> </div>
                <?php endif; ?>
                <form method="post" action="<?= base_url('admin/add-user') ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add User</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 