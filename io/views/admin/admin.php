<div class="dashboard">
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p>Overview of your copro.academy content</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📝</div>
            <div class="stat-content">
                <h3>Articles</h3>
                <div class="stat-number"><?= $data['stats']['articles']['total'] ?></div>
                <div class="stat-details">
                    <?= $data['stats']['articles']['published'] ?> published,
                    <?= $data['stats']['articles']['draft'] ?> drafts
                </div>
            </div>
            <a href="/admin/articles" class="stat-link">Manage →</a>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <h3>Events</h3>
                <div class="stat-number"><?= $data['stats']['events']['total'] ?></div>
                <div class="stat-details">
                    <?= $data['stats']['events']['upcoming'] ?> upcoming,
                    <?= $data['stats']['events']['past'] ?> past
                </div>
            </div>
            <a href="/admin/events" class="stat-link">Manage →</a>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📁</div>
            <div class="stat-content">
                <h3>Resources</h3>
                <div class="stat-number"><?= $data['stats']['resources']['total'] ?></div>
                <div class="stat-details">
                    <?= $data['stats']['resources']['published'] ?> published
                </div>
            </div>
            <a href="/admin/resources" class="stat-link">Manage →</a>
        </div>

        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3>Users</h3>
                <div class="stat-number"><?= $data['stats']['users']['total'] ?></div>
                <div class="stat-details">
                    <?= $data['stats']['users']['admins'] ?> administrators
                </div>
            </div>
            <a href="/admin/users" class="stat-link">Manage →</a>
        </div>
    </div>

    <div class="recent-activity">
        <div class="activity-section">
            <div class="section-header">
                <h2>Recent Articles</h2>
                <a href="/admin/articles/alter" class="btn btn-primary">New Article</a>
            </div>
            <div class="activity-list">
                <?php if (!empty($data['recent_articles'])): ?>
                    <?php foreach ($data['recent_articles'] as $article): ?>
                        <div class="activity-item">
                            <div class="activity-content">
                                <h4><?= htmlspecialchars($article['title']) ?></h4>
                                <p>by <?= htmlspecialchars($article['author']) ?> •
                                    <span class="status status-<?= $article['status'] ?>"><?= ucfirst($article['status']) ?></span>
                                </p>
                                <small><?= date('M j, Y', strtotime($article['created_at'])) ?></small>
                            </div>
                            <div class="activity-actions">
                                <a href="/admin/articles/alter/<?= $article['id'] ?>">Edit</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-state">No articles yet. <a href="/admin/articles/alter">Create your first article</a></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="activity-section">
            <div class="section-header">
                <h2>Recent Events</h2>
                <a href="/admin/events/alter" class="btn btn-primary">New Event</a>
            </div>
            <div class="activity-list">
                <?php if (!empty($data['recent_events'])): ?>
                    <?php foreach ($data['recent_events'] as $event): ?>
                        <div class="activity-item">
                            <div class="activity-content">
                                <h4><?= htmlspecialchars($event['title']) ?></h4>
                                <p>by <?= htmlspecialchars($event['organizer']) ?> •
                                    <span class="status status-<?= $event['status'] ?>"><?= ucfirst($event['status']) ?></span>
                                </p>
                                <small><?= date('M j, Y g:i A', strtotime($event['start_datetime'])) ?></small>
                            </div>
                            <div class="activity-actions">
                                <a href="/admin/events/alter/<?= $event['id'] ?>">Edit</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-state">No events yet. <a href="/admin/events/alter">Create your first event</a></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="activity-section">
            <div class="section-header">
                <h2>Recent Resources</h2>
                <a href="/admin/resources/alter" class="btn btn-primary">New Resource</a>
            </div>
            <div class="activity-list">
                <?php if (!empty($data['recent_resources'])): ?>
                    <?php foreach ($data['recent_resources'] as $resource): ?>
                        <div class="activity-item">
                            <div class="activity-content">
                                <h4><?= htmlspecialchars($resource['title']) ?></h4>
                                <p>by <?= htmlspecialchars($resource['uploader']) ?> •
                                    <span class="file-type"><?= strtoupper($resource['file_type']) ?></span> •
                                    <span class="status status-<?= $resource['status'] ?>"><?= ucfirst($resource['status']) ?></span>
                                </p>
                                <small><?= date('M j, Y', strtotime($resource['created_at'])) ?></small>
                            </div>
                            <div class="activity-actions">
                                <a href="/admin/resources/alter/<?= $resource['id'] ?>">Edit</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-state">No resources yet. <a href="/admin/resources/alter">Upload your first resource</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .dashboard-header {
        margin-bottom: 2rem;
    }

    .dashboard-header h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .dashboard-header p {
        color: #666;
        font-size: 1.1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem;
        position: relative;
        transition: box-shadow 0.2s;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .stat-content h3 {
        font-size: 1rem;
        color: #666;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .stat-details {
        color: #888;
        font-size: 0.9rem;
    }

    .stat-link {
        position: absolute;
        top: 1rem;
        right: 1rem;
        color: #3498db;
        text-decoration: none;
        font-weight: 500;
    }

    .stat-link:hover {
        text-decoration: underline;
    }

    .recent-activity {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
    }

    .activity-section {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
    }

    .section-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fa;
    }

    .section-header h2 {
        font-size: 1.2rem;
        margin: 0;
        color: #333;
    }

    .btn {
        padding: 0.5rem 1rem;
        text-decoration: none;
        border-radius: 4px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .btn-primary {
        background: #3498db;
        color: white;
    }

    .btn-primary:hover {
        background: #2980b9;
    }

    .activity-list {
        padding: 1rem;
    }

    .activity-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-content h4 {
        margin: 0 0 0.5rem 0;
        font-size: 1rem;
        color: #333;
    }

    .activity-content p {
        margin: 0 0 0.25rem 0;
        font-size: 0.9rem;
        color: #666;
    }

    .activity-content small {
        color: #999;
        font-size: 0.8rem;
    }

    .status {
        padding: 0.2rem 0.5rem;
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

    .file-type {
        background: #e9ecef;
        color: #495057;
        padding: 0.2rem 0.4rem;
        border-radius: 3px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .activity-actions a {
        color: #3498db;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .activity-actions a:hover {
        text-decoration: underline;
    }

    .empty-state {
        color: #999;
        font-style: italic;
        text-align: center;
        padding: 2rem;
    }

    .empty-state a {
        color: #3498db;
        text-decoration: none;
    }

    .empty-state a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .dashboard {
            padding: 1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .recent-activity {
            grid-template-columns: 1fr;
        }

        .section-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .activity-item {
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
        }
    }
</style>