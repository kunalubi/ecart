<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart - <?= esc($store['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Your Cart</h4>
                    </div>
                    <div class="card-body">
                        <?php if(session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
                        <?php endif; ?>
                        <?php if(session()->getFlashdata('success')): ?>
                            <div class="alert alert-success"> <?= session()->getFlashdata('success') ?> </div>
                        <?php endif; ?>
                        <?php if (!empty($cart)): ?>
                        <form method="post" action="<?= base_url('cart/update') ?>">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $total = 0; ?>
                                        <?php foreach ($cart as $item): ?>
                                            <?php $subtotal = $item['price'] * $item['qty']; $total += $subtotal; ?>
                                            <tr>
                                                <td><?= esc($item['name']) ?></td>
                                                <td>₹<?= esc($item['price']) ?></td>
                                                <td style="width: 100px;">
                                                    <input type="number" name="qty[<?= $item['id'] ?>]" value="<?= $item['qty'] ?>" min="1" class="form-control">
                                                </td>
                                                <td>₹<?= $subtotal ?></td>
                                                <td>
                                                    <form method="post" action="<?= base_url('cart/remove') ?>" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Total</th>
                                            <th colspan="2">₹<?= $total ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-secondary">Update Cart</button>
                                <form action="<?= base_url('checkout') ?>" method="get">
                                    <button type="submit" class="btn btn-primary">Checkout</button>
                                </form>
                            </div>
                        </form>
                        <?php else: ?>
                            <div class="alert alert-info">Your cart is empty.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 