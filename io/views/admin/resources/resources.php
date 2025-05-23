<div class="content-header">
    <div class="header-left">
        <h1>Resources</h1>
        <p>Manage downloadable resources and files</p>
    </div>
    <div class="header-actions">
        <a href="/admin/resources/alter" class="btn btn-primary">Upload Resource</a>
    </div>
</div>

<div class="content-filters">
    <div class="filter-tabs">
        <a href="/admin/resources" class="filter-tab <?= $data['current_status'] === 'all' ? 'active' : '' ?>">
            All Resources
        </a>
        <a href="/admin/resources?status=published" class="filter-tab <?= $data['current_status'] === 'published' ? 'active' : '' ?>">
            Published
        </a>
        <a href="/admin/resources?status=draft" class="filter-tab <?= $data['current_status'] === 'draft' ? 'active' : '' ?>">
            Drafts
        </a>
    </div>
</div>

<div class="content-body">
    <?php if (!empty($data['resources'])): ?>
        <div class="resources-table">
            <table>
                <thead>
                    <tr>
                        <th>Resource</th>
                        <th>File</th>
                        <th>Uploader</th>
                        <th>Status</th>
                        <th>Uploaded</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['resources'] as $resource): ?>
                        <tr>
                            <td>
                                <div class="resource-info">
                                    <strong class="resource-title"><?= htmlspecialchars($resource['title']) ?></strong>
                                    <?php if ($resource['description']): ?>
                                        <div class="resource-description">
                                            <?= htmlspecialchars(substr($resource['description'], 0, 100)) ?><?= strlen($resource['description']) > 100 ? '...' : '' ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="file-info">
                                    <div class="file-name"><?= htmlspecialchars(basename($resource['file_path'])) ?></div>
                                    <div class="file-meta">
                                        <span class="file-type"><?= strtoupper($resource['file_type']) ?></span>
                                        <span class="file-size"><?= number_format($resource['file_size'] / 1024, 1) ?> KB</span>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($resource['uploader_name']) ?></td>
                            <td>
                                <span class="status status-<?= $resource['status'] ?>">
                                    <?= ucfirst($resource['status']) ?>
                                </span>
                            </td>
                            <td>
                                <time datetime="<?= $resource['created_at'] ?>">
                                    <?= date('M j, Y', strtotime($resource['created_at'])) ?>
                                </time>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if ($resource['status'] === 'published'): ?>
                                        <a href="/resources/download/<?= urlencode($resource['slug']) ?>" target="_blank" class="btn btn-sm">Download</a>
                                    <?php endif; ?>
                                    <a href="/admin/resources/alter/<?= $resource['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <button onclick="deleteResource(<?= $resource['id'] ?>, '<?= htmlspecialchars($resource['title'], ENT_QUOTES) ?>')"
                                        class="btn btn-sm btn-danger">Delete</button>
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
                    <a href="?page=<?= $data['pagination']['current'] - 1 ?>&status=<?= urlencode($data['current_status']) ?>"
                        class="pagination-link">← Previous</a>
                <?php endif; ?>

                <span class="pagination-info">
                    Page <?= $data['pagination']['current'] ?> of <?= $data['pagination']['total'] ?>
                </span>

                <?php if ($data['pagination']['has_next']): ?>
                    <a href="?page=<?= $data['pagination']['current'] + 1 ?>&status=<?= urlencode($data['current_status']) ?>"
                        class="pagination-link">Next →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">📁</div>
            <h3>No resources found</h3>
            <p>Get started by uploading your first resource.</p>
            <a href="/admin/resources/alter" class="btn btn-primary">Upload Resource</a>
        </div>
    <?php endif; ?>
</div>

<script>
    function deleteResource(id, title) {
        if (confirm(`Are you sure you want to delete "${title}"? This action cannot be undone.`)) {
            fetch(`/admin/resources/delete/${id}`, {
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
                        alert('Failed to delete resource: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    alert('Failed to delete resource: ' + error.message);
                });
        }
    }
</script>