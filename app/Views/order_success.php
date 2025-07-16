<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success - <?= esc($store['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h4>Order Placed Successfully!</h4>
                    </div>
                    <div class="card-body text-center">
                        <h5>Your Order ID: <span class="text-primary">#<?= esc($order_id) ?></span></h5>
                        <p>Payment Method: <strong><?= esc($payment) ?></strong></p>
                        <?php if ($payment === 'UPI'): ?>
                            <div class="alert alert-info">Pay to UPI ID: <strong><?= esc($upi_id) ?></strong></div>
                            <small class="text-muted">(This is a demo UPI ID. No real payment will be processed.)</small>
                        <?php endif; ?>
                        <div class="mt-4">
                            <a href="<?= base_url('/storefront') ?>" class="btn btn-primary">Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 