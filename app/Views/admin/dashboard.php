<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php
// Helper to get monthly counts from PHP arrays (created_at field)
function getMonthlyCounts($items) {
    $months = array_fill(0, 12, 0);
    foreach ($items as $item) {
        if (isset($item['created_at'])) {
            $d = date_create($item['created_at']);
            if ($d && date_format($d, 'Y') == date('Y')) {
                $month = (int)date_format($d, 'n') - 1;
                $months[$month]++;
            }
        }
    }
    return $months;
}
$productsData = isset($products) ? getMonthlyCounts($products) : array_fill(0, 12, 0);
$usersData = isset($users) ? getMonthlyCounts($users) : array_fill(0, 12, 0);
$ordersData = isset($orders) ? getMonthlyCounts($orders) : array_fill(0, 12, 0);
$orderStatusCounts = isset($orderStatusCounts) ? $orderStatusCounts : [
    'pending'=>0,'processing'=>0,'completed'=>0,'cancelled'=>0,'delivered'=>0,'shipped'=>0
];
?>
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
                <h2><?= isset($orders) ? count($orders) : 0 ?></h2>
            </div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-white"><h5 class="mb-0">Products Overview (Line)</h5></div>
            <div class="card-body"><canvas id="productsChart" height="120"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-white"><h5 class="mb-0">Users Overview (Bar)</h5></div>
            <div class="card-body"><canvas id="usersChart" height="120"></canvas></div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-white"><h5 class="mb-0">Orders Overview (Doughnut)</h5></div>
            <div class="card-body"><canvas id="ordersChart" height="120"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-white"><h5 class="mb-0">Orders Status Overview (Pie)</h5></div>
            <div class="card-body"><canvas id="ordersStatusChart" height="120"></canvas></div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-white"><h5 class="mb-0">Inventory Overview</h5></div>
            <div class="card-body">
                <canvas id="inventoryChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const productsData = <?= json_encode($productsData) ?>;
const usersData = <?= json_encode($usersData) ?>;
const ordersData = <?= json_encode($ordersData) ?>;
const orderStatusLabels = <?= json_encode(array_keys($orderStatusCounts)) ?>;
const orderStatusData = <?= json_encode(array_values($orderStatusCounts)) ?>;
const orderStatusColors = [
    'rgba(255, 193, 7, 0.7)',    // pending
    'rgba(54, 162, 235, 0.7)',   // processing
    'rgba(40, 167, 69, 0.7)',    // completed
    'rgba(220, 53, 69, 0.7)',    // cancelled
    'rgba(102, 16, 242, 0.7)',   // delivered
    'rgba(255, 87, 34, 0.7)'     // shipped
];
const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
// Products - Line Chart
new Chart(document.getElementById('productsChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Products',
            data: productsData,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {responsive: true, plugins: {legend: {display: false}}}
});
// Users - Bar Chart
new Chart(document.getElementById('usersChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Users',
            data: usersData,
            backgroundColor: 'rgba(255, 193, 7, 0.7)',
            borderColor: 'rgba(255, 193, 7, 1)',
            borderWidth: 1
        }]
    },
    options: {responsive: true, plugins: {legend: {display: false}}}
});
// Orders - Doughnut Chart
new Chart(document.getElementById('ordersChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: months,
        datasets: [{
            label: 'Orders',
            data: ordersData,
            backgroundColor: [
                'rgba(23, 162, 184, 0.7)', 'rgba(23, 162, 184, 0.5)', 'rgba(23, 162, 184, 0.3)',
                'rgba(23, 162, 184, 0.2)', 'rgba(23, 162, 184, 0.1)', 'rgba(23, 162, 184, 0.05)',
                'rgba(23, 162, 184, 0.7)', 'rgba(23, 162, 184, 0.5)', 'rgba(23, 162, 184, 0.3)',
                'rgba(23, 162, 184, 0.2)', 'rgba(23, 162, 184, 0.1)', 'rgba(23, 162, 184, 0.05)'
            ]
        }]
    },
    options: {responsive: true, plugins: {legend: {display: true}}}
});
// Orders Status - Pie Chart
new Chart(document.getElementById('ordersStatusChart').getContext('2d'), {
    type: 'pie',
    data: {
        labels: orderStatusLabels,
        datasets: [{
            label: 'Orders by Status',
            data: orderStatusData,
            backgroundColor: orderStatusColors
        }]
    },
    options: {responsive: true, plugins: {legend: {display: true}}}
});
const inventoryLabels = <?= json_encode($inventoryLabels) ?>;
const inventoryData = <?= json_encode($inventoryData) ?>;
const inventoryChart = new Chart(document.getElementById('inventoryChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: inventoryLabels,
        datasets: [{
            label: 'Stock',
            data: inventoryData,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        aspectRatio: 2.5,
        plugins: {
            legend: { display: false },
            title: { display: false }
        },
        scales: {
            x: { title: { display: true, text: 'Product' } },
            y: { beginAtZero: true, title: { display: true, text: 'Stock' } }
        }
    }
});
</script>
<?= $this->endSection() ?> 