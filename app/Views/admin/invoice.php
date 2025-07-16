<!-- app/Views/admin/invoice.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?= esc($order['id']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-white">
<div class="container mt-4">
    <h2>Invoice #<?= esc($order['id']) ?></h2>
    <p><strong>Date:</strong> <?= esc($order['created_at']) ?></p>
    <p><strong>Customer:</strong> <?= esc($order['customer_name']) ?></p>
    <p><strong>Email:</strong> <?= esc($order['customer_email']) ?></p>
    <p><strong>Phone:</strong> <?= esc($order['phone']) ?></p>
    <p><strong>Address:</strong> <?= esc($order['address']) ?></p>
    <hr>
    <h4>Order Items</h4>
    <table class="table table-bordered">
        <tr>
            <th>Product ID</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= esc($item['product_id']) ?></td>
            <td><?= esc($item['quantity']) ?></td>
            <td><?= esc($item['price']) ?></td>
            <td><?= esc($item['price'] * $item['quantity']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <h5 class="text-end">Total: <strong><?= esc($order['total']) ?></strong></h5>
</div>
</body>
</html>
