<div class="content-header">
    <div class="header-left">
        <h1>Articles</h1>
        <p>Manage blog posts and content</p>
    </div>
    <div class="header-actions">
        <a href="/admin/articles/alter" class="btn btn-primary">Create Article</a>
    </div>
</div>

<div class="content-filters">
    <div class="filter-tabs">
        <a href="/admin/articles" class="filter-tab <?= $data['current_status'] === 'all' ? 'active' : '' ?>">
            All Articles
        </a>
        <a href="/admin/articles?status=published" class="filter-tab <?= $data['current_status'] === 'published' ? 'active' : '' ?>">
            Published
        </a>
        <a href="/admin/articles?status=draft" class="filter-tab <?= $data['current_status'] === 'draft' ? 'active' : '' ?>">
            Drafts
        </a>
    </div>
</div>

<div class="content-body">
    <?php if (!empty($data['articles'])): ?>
        <div class="articles-table">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['articles'] as $article): ?>
                        <tr>
                            <td>
                                <div class="article-title">
                                    <strong><?= htmlspecialchars($article['title']) ?></strong>
                                    <?php if ($article['excerpt']): ?>
                                        <div class="article-excerpt">
                                            <?= htmlspecialchars(substr($article['excerpt'], 0, 100)) ?><?= strlen($article['excerpt']) > 100 ? '...' : '' ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($article['author_name']) ?></td>
                            <td>
                                <span class="status status-<?= $article['status'] ?>">
                                    <?= ucfirst($article['status']) ?>
                                </span>
                            </td>
                            <td>
                                <time datetime="<?= $article['created_at'] ?>">
                                    <?= date('M j, Y', strtotime($article['created_at'])) ?>
                                </time>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/blog/article/<?= urlencode($article['slug']) ?>" target="_blank" class="btn btn-sm">View</a>
                                    <a href="/admin/articles/alter/<?= $article['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <button onclick="deleteArticle(<?= $article['id'] ?>, '<?= htmlspecialchars($article['title'], ENT_QUOTES) ?>')"
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
            <div class="empty-icon">📝</div>
            <h3>No articles found</h3>
            <p>Get started by creating your first article.</p>
            <a href="/admin/articles/alter" class="btn btn-primary">Create Article</a>
        </div>
    <?php endif; ?>
</div>

<script>
    function deleteArticle(id, title) {
        if (confirm(`Are you sure you want to delete "${title}"? This action cannot be undone.`)) {
            fetch(`/admin/articles/delete/${id}`, {
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
                        alert('Failed to delete article: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    alert('Failed to delete article: ' + error.message);
                });
        }
    }
</script>