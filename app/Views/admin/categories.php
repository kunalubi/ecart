<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Categories - <?= esc($store['name']) ?></h4>
        <a href="<?= base_url('admin/add-category') ?>" class="btn btn-light">Add Category</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Parent</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><?= esc($cat['name']) ?></td>
                                <td>
                                    <?php
                                    $parent = null;
                                    if ($cat['parent_id']) {
                                        foreach ($categories as $p) {
                                            if ($p['id'] == $cat['parent_id']) {
                                                $parent = $p['name'];
                                                break;
                                            }
                                        }
                                    }
                                    echo $parent ? esc($parent) : '-';
                                    ?>
                                </td>
                                <td><?= esc($cat['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">No categories found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 