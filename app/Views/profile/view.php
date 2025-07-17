<?php $store = $store ?? null; ?>
<?php $this->extend('admin/layout'); ?>
<?php $this->section('content'); ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">My Profile</div>
                <div class="card-body">
                    <h5 class="card-title mb-3">Profile Details</h5>
                    <p><strong>Name:</strong> <?= esc($user['name']) ?></p>
                    <p><strong>Email:</strong> <?= esc($user['email']) ?></p>
                    <p><strong>Role:</strong> <?= esc($user['role']) ?></p>
                    <a href="<?= base_url('profile/edit') ?>" class="btn btn-outline-primary">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?> 