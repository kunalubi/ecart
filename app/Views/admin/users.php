<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Users Management</h4>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= esc($user['id']) ?></td>
                    <td><?= esc($user['name']) ?></td>
                    <td><?= esc($user['email']) ?></td>
                    <td>
                        <button class="toggle-status btn btn-sm <?= $user['status'] === 'active' ? 'btn-success' : 'btn-warning' ?>" data-user-id="<?= esc($user['id']) ?>" data-status="<?= esc($user['status']) ?>">
                            <?= ucfirst(esc($user['status'])) ?>
                        </button>
                    </td>
                    <td>
                        <a href="<?= base_url('admin/edit-user/' . $user['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="<?= base_url('admin/delete-user/' . $user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-status').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.getAttribute('data-user-id');
        const currentStatus = this.getAttribute('data-status');
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

        fetch('/ecart/admin/update-user-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ userId: userId, status: newStatus }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.setAttribute('data-status', newStatus);
                this.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                this.classList.toggle('btn-success');
                this.classList.toggle('btn-warning');
            } else {
                alert('Error updating status');
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
<?= $this->endSection() ?> 