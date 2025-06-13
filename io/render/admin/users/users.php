<div class="content-header">
    <div class="header-left">
        <h1>Users</h1>
        <p>Manage user accounts and pergoals</p>
    </div>
    <div class="header-actions">
        <a href="/admin/users/alter" class="btn btn-primary">Add User</a>
    </div>
</div>

<div class="content-filters">
    <div class="filter-tabs">
        <a href="/admin/users" class="filter-tab <?= $data['current_status'] === 'all' ? 'active' : '' ?>">
            All Users
        </a>
        <a href="/admin/users?status=active" class="filter-tab <?= $data['current_status'] === 'active' ? 'active' : '' ?>">
            Active
        </a>
        <a href="/admin/users?status=inactive" class="filter-tab <?= $data['current_status'] === 'inactive' ? 'active' : '' ?>">
            Inactive
        </a>
        <a href="/admin/users?role=admin" class="filter-tab <?= $data['current_role'] === 'admin' ? 'active' : '' ?>">
            Admins
        </a>
    </div>
</div>

<div class="content-body">
    <?php if (!empty($data['users'])): ?>
        <div class="users-table">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['users'] as $user): ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <strong class="user-name"><?= htmlspecialchars($user['full_name']) ?></strong>
                                    <div class="user-email"><?= htmlspecialchars($user['email']) ?></div>
                                </div>
                            </td>
                            <td>
                                <code><?= htmlspecialchars($user['username']) ?></code>
                            </td>
                            <td>
                                <span class="role role-<?= $user['role'] ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="status status-<?= $user['status'] ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </td>
                            <td>
                                <time datetime="<?= $user['created_at'] ?>">
                                    <?= date('M j, Y', strtotime($user['created_at'])) ?>
                                </time>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/admin/users/alter/<?= $user['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <?php if ($user['status'] === 'active'): ?>
                                        <button onclick="toggleUserStatus(<?= $user['id'] ?>, 'inactive', '<?= htmlspecialchars($user['full_name'], ENT_QUOTES) ?>')"
                                            class="btn btn-sm btn-warning">Deactivate</button>
                                    <?php else: ?>
                                        <button onclick="toggleUserStatus(<?= $user['id'] ?>, 'active', '<?= htmlspecialchars($user['full_name'], ENT_QUOTES) ?>')"
                                            class="btn btn-sm btn-success">Activate</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($data['pagination']['total'] > 1): ?>
            <div class="pagination">
                <?php if ($data['pagination']['has_prev']): ?>
                    <a href="?page=<?= $data['pagination']['current'] - 1 ?>&status=<?= urlencode($data['current_status']) ?>&role=<?= urlencode($data['current_role']) ?>"
                        class="pagination-link">← Previous</a>
                <?php endif; ?>

                <span class="pagination-info">
                    Page <?= $data['pagination']['current'] ?> of <?= $data['pagination']['total'] ?>
                </span>

                <?php if ($data['pagination']['has_next']): ?>
                    <a href="?page=<?= $data['pagination']['current'] + 1 ?>&status=<?= urlencode($data['current_status']) ?>&role=<?= urlencode($data['current_role']) ?>"
                        class="pagination-link">Next →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">👥</div>
            <h3>No users found</h3>
            <p>No users match the current filter criteria.</p>
        </div>
    <?php endif; ?>
</div>

<script>
    function toggleUserStatus(id, newStatus, name) {
        const action = newStatus === 'active' ? 'activate' : 'deactivate';
        if (confirm(`Are you sure you want to ${action} ${name}?`)) {
            fetch(`/admin/users/status/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(`Failed to ${action} user: ` + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    alert(`Failed to ${action} user: ` + error.message);
                });
        }
    }
</script>