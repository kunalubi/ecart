<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($store['name']) ?> - Storefront</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#"><?= esc($store['name']) ?></a>
            <div class="d-flex">
                <a href="<?= base_url('cart') ?>" class="btn btn-warning me-2">Cart</a>
                <a href="<?= base_url('login') ?>" class="btn btn-light">Login</a>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($product['name']) ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">₹<?= esc($product['price']) ?></h6>
                                <p class="card-text"><?= esc($product['description']) ?></p>
                                <form method="post" action="<?= base_url('cart/add') ?>">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="number" name="qty" value="1" min="1" class="form-control mb-2" style="width: 90px; display: inline-block;">
                                    <button type="submit" class="btn btn-success">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">No products found.</div>
                </div>
            <?php endif; ?>
        </div>
        <div class="text-center my-4">
            <a href="<?= base_url('track-order') ?>" class="btn btn-outline-primary btn-lg">Track Your Order</a>
        </div>
    </div>
</body>
</html> 
