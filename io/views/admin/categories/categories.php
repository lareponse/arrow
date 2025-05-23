<div class="content-header">
    <div class="header-left">
        <h1>Categories</h1>
        <p>Manage content categories</p>
    </div>
    <div class="header-actions">
        <a href="/admin/categories/alter" class="btn btn-primary">Create Category</a>
    </div>
</div>

<div class="content-body">
    <?php if (!empty($data['categories'])): ?>
        <div class="categories-table">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Articles</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['categories'] as $category): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($category['name']) ?></strong>
                            </td>
                            <td>
                                <code><?= htmlspecialchars($category['slug']) ?></code>
                            </td>
                            <td>
                                <?= htmlspecialchars($category['description'] ?? '') ?>
                            </td>
                            <td>
                                <?= $category['article_count'] ?? 0 ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/admin/categories/alter/<?= $category['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <button onclick="deleteCategory(<?= $category['id'] ?>, '<?= htmlspecialchars($category['name'], ENT_QUOTES) ?>')"
                                        class="btn btn-sm btn-danger">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">🏷️</div>
            <h3>No categories found</h3>
            <p>Get started by creating your first category.</p>
            <a href="/admin/categories/alter" class="btn btn-primary">Create Category</a>
        </div>
    <?php endif; ?>
</div>

<script>
    function deleteCategory(id, name) {
        if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
            fetch(`/admin/categories/delete/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to delete category: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    alert('Failed to delete category: ' + error.message);
                });
        }
    }
</script>