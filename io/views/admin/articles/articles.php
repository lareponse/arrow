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

<style>
 
    .articles-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .articles-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .articles-table th {
        background: #f8f9fa;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 1px solid #e0e0e0;
    }

    .articles-table td {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: top;
    }

    .articles-table tr:last-child td {
        border-bottom: none;
    }

    .article-title strong {
        display: block;
        margin-bottom: 0.25rem;
    }

    .article-excerpt {
        font-size: 0.9rem;
        color: #666;
        line-height: 1.4;
    }

    .status {
        padding: 0.25rem 0.5rem;
        border-radius: 3px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .status-published {
        background: #d4edda;
        color: #155724;
    }

    .status-draft {
        background: #fff3cd;
        color: #856404;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .btn {
        padding: 0.5rem 1rem;
        text-decoration: none;
        border-radius: 4px;
        font-size: 0.9rem;
        font-weight: 500;
        border: 1px solid #ddd;
        background: white;
        color: #333;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn:hover {
        background: #f8f9fa;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }

    .btn-primary {
        background: #3498db;
        color: white;
        border-color: #3498db;
    }

    .btn-primary:hover {
        background: #2980b9;
        border-color: #2980b9;
    }

    .btn-danger {
        background: #e74c3c;
        color: white;
        border-color: #e74c3c;
    }

    .btn-danger:hover {
        background: #c0392b;
        border-color: #c0392b;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        margin-top: 2rem;
    }

    .pagination-link {
        padding: 0.5rem 1rem;
        text-decoration: none;
        color: #3498db;
        border: 1px solid #3498db;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .pagination-link:hover {
        background: #3498db;
        color: white;
    }

    .pagination-info {
        color: #666;
        font-size: 0.9rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 8px;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .empty-state p {
        color: #666;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .content-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .content-body {
            padding: 1rem;
        }

        .articles-table {
            overflow-x: auto;
        }

        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-tabs {
            overflow-x: auto;
            white-space: nowrap;
        }
    }
</style>