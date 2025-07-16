<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-bg-primary mb-3">
            <div class="card-body text-center">
                <h5 class="card-title">Products</h5>
                <h2><?= isset($products) ? count($products) : 0 ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success mb-3">
            <div class="card-body text-center">
                <h5 class="card-title">Categories</h5>
                <h2><?= isset($categories) ? count($categories) : 0 ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning mb-3">
            <div class="card-body text-center">
                <h5 class="card-title">Users</h5>
                <h2><?= isset($users) ? count($users) : 0 ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-info mb-3">
            <div class="card-body text-center">
                <h5 class="card-title">Orders</h5>
                <h2>0</h2>
            </div>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Orders Overview (Sample Graph)</h5>
    </div>
    <div class="card-body">
        <canvas id="ordersChart" height="80"></canvas>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('ordersChart').getContext('2d');
const ordersChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [{
            label: 'Orders',
            data: [3, 7, 4, 5, 8, 2, 6], // Dummy data
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        }
    }
});
</script>
<?= $this->endSection() ?> 