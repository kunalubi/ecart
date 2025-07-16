<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Orders - <?= esc($store['name'] ?? '') ?></h4>
    </div>
    <div class="card-body">
        <h2>Orders</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Date</th>
            </tr>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= esc($order['id']) ?></td>
                <td><?= esc($order['customer_name']) ?></td>
                <td><?= esc($order['customer_email']) ?></td>
                <td><?= esc($order['total']) ?></td>
                <td>
                    <form method="post" action="<?= base_url('admin/orders/update-status') ?>">
                        <input type="hidden" name="order_id" value="<?= esc($order['id']) ?>">
                        <select name="status" onchange="this.form.submit()">
                            <?php foreach (['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                                <option value="<?= $s ?>" <?= $order['status']==$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </td>
                <td><?= esc($order['payment_method']) ?></td>
                <td><?= esc($order['created_at']) ?></td>
                <td>
                    <a href="<?= base_url('admin/orders/invoice/'.$order['id']) ?>" class="btn btn-sm btn-info" target="_blank">Invoice</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
