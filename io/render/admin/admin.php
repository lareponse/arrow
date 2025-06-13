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
<?php
return function ($view) {
    http(200, ob_capture(__DIR__.DIRECTORY_SEPARATOR.'layout.php', $view), ['Content-Type' => 'text/html; charset=UTF-8']);
}

?>