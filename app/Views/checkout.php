<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - <?= esc($store['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
    function toggleUPI() {
        var upiDiv = document.getElementById('upi-info');
        var upiRadio = document.getElementById('payment_upi');
        upiDiv.style.display = upiRadio.checked ? 'block' : 'none';
    }
    </script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Checkout</h4>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error) && $error): ?>
                            <div class="alert alert-danger"> <?= esc($error) ?> </div>
                        <?php endif; ?>
                        <h5>Order Summary</h5>
                        <ul class="list-group mb-3">
                            <?php $total = 0; ?>
                            <?php foreach ($cart as $item): ?>
                                <?php $subtotal = $item['price'] * $item['qty']; $total += $subtotal; ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= esc($item['name']) ?> x <?= $item['qty'] ?>
                                    <span>₹<?= $subtotal ?></span>
                                </li>
                            <?php endforeach; ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Total</strong>
                                <strong>₹<?= $total ?></strong>
                            </li>
                        </ul>
                        <form method="post" action="<?= base_url('checkout') ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Method</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" checked onclick="toggleUPI()">
                                    <label class="form-check-label" for="payment_cod">Cash on Delivery</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_upi" value="upi" onclick="toggleUPI()">
                                    <label class="form-check-label" for="payment_upi">Pay via UPI</label>
                                </div>
                            </div>
                            <div class="mb-3" id="upi-info" style="display:none;">
                                <label class="form-label">Pay to this UPI ID:</label>
                                <div class="alert alert-info mb-2">demo@upi</div>
                                <small class="text-muted">(This is a demo UPI ID. No real payment will be processed.)</small>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>toggleUPI();</script>
</body>
</html> 