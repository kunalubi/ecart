<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success text-center">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>All Stores</h2>
            <div>
                <a href="<?= base_url('masteradmin/register') ?>" class="btn btn-primary me-2">Register New Store</a>
                <a href="<?= base_url('masteradmin/logout') ?>" class="btn btn-outline-danger">Logout</a>
            </div>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Store Name</th>
                    <th>Superadmin Email</th>
                    <th>User Count</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stores as $i => $store): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= esc($store['name']) ?></td>
                        <td><?= esc($store['superadmin']['email'] ?? '-') ?></td>
                        <td><?= esc($store['user_count']) ?></td>
                        <td>
                            <?php if ($store['status'] === 'approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php elseif ($store['status'] === 'pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($store['status'] !== 'approved'): ?>
                                <form method="post" action="<?= base_url('masteradmin/approve_store/'.$store['id']) ?>" style="display:inline-block">
                                    <button class="btn btn-success btn-sm" type="submit">Approve</button>
                                </form>
                            <?php endif; ?>
                            <?php if ($store['status'] !== 'rejected'): ?>
                                <form method="post" action="<?= base_url('masteradmin/reject_store/'.$store['id']) ?>" style="display:inline-block">
                                    <button class="btn btn-danger btn-sm" type="submit">Reject</button>
                                </form>
                            <?php endif; ?>
                            <form method="post" action="<?= base_url('masteradmin/delete_store/'.$store['id']) ?>" style="display:inline-block" onsubmit="return confirm('Are you sure you want to delete this store and all its users?');">
                                <button class="btn btn-outline-danger btn-sm" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html> 