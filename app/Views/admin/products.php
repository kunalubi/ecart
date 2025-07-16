<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Products - <?= esc($store['name']) ?></h4>
        <a href="<?= base_url('admin/add-product') ?>" class="btn btn-light">Add Product</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $prod): ?>
                            <tr>
                                <td><?= esc($prod['name']) ?></td>
                                <td>
                                    <?php
                                    $catName = '-';
                                    foreach ($categories as $cat) {
                                        if ($cat['id'] == $prod['category_id']) {
                                            $catName = $cat['name'];
                                            break;
                                        }
                                    }
                                    echo esc($catName);
                                    ?>
                                </td>
                                <td><?= esc($prod['price']) ?></td>
                                <td><?= esc($prod['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">No products found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
