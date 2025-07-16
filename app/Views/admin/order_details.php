<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Order Details / Invoice - <?= esc($store['name'] ?? '') ?></h4>
    </div>
    <div class="card-body">
        <div class="alert alert-info">Order details and invoice functionality coming soon.</div>
    </div>
</div>
<?= $this->endSection() ?>
