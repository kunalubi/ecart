<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Ecart Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .hero {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
    </style>
</head>
<body>
    <div class="container hero text-center">
        <h1 class="display-4 mb-3">Welcome to <span class="text-primary">Ecart</span> Multi-Store E-commerce Platform</h1>
        <p class="lead mb-4">Launch your own online store in minutes. Powerful admin dashboard, multi-store support, and full control over your business.</p>
        <a href="<?= base_url('login') ?>" class="btn btn-lg btn-primary">Go to Admin Login</a>
        <div class="mt-5 text-muted">&copy; <?= date('Y') ?> Ecart Platform. All rights reserved.</div>
    </div>
</body>
</html> 