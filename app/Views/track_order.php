<form method="get" action="<?= base_url('track-order') ?>">
    <input type="text" name="order_id" placeholder="Enter Order ID" required>
    <button type="submit">Track</button>
</form>
<?php if (isset($order)): ?>
    <h4>Status: <?= esc($order['status']) ?></h4>
    <!-- Aur bhi order details dikha sakte hain -->
<?php elseif (isset($not_found)): ?>
    <div class="alert alert-danger">Order not found!</div>
<?php endif; ?>

<a href="<?= base_url('track-order') ?>">Track Your Order</a>
