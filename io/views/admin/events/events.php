<div class="content-header">
    <div class="header-left">
        <h1>Events</h1>
        <p>Manage upcoming and past events</p>
    </div>
    <div class="header-actions">
        <a href="/admin/events/alter" class="btn btn-primary">Create Event</a>
    </div>
</div>

<div class="content-filters">
    <div class="filter-tabs">
        <a href="/admin/events" class="filter-tab <?= $data['current_status'] === 'all' ? 'active' : '' ?>">
            All Events
        </a>
        <a href="/admin/events?status=published" class="filter-tab <?= $data['current_status'] === 'published' ? 'active' : '' ?>">
            Published
        </a>
        <a href="/admin/events?status=draft" class="filter-tab <?= $data['current_status'] === 'draft' ? 'active' : '' ?>">
            Drafts
        </a>
    </div>
</div>

<div class="content-body">
    <?php if (!empty($data['events'])): ?>
        <div class="events-table">
            <table>
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Date & Time</th>
                        <th>Organizer</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['events'] as $event): ?>
                        <?php
                        $start_date = new DateTime($event['start_datetime']);
                        $end_date = new DateTime($event['end_datetime']);
                        $now = new DateTime();
                        $is_past = $end_date < $now;
                        $is_upcoming = $start_date > $now;
                        ?>
                        <tr class="<?= $is_past ? 'event-past' : ($is_upcoming ? 'event-upcoming' : 'event-current') ?>">
                            <td>
                                <div class="event-info">
                                    <strong class="event-title"><?= htmlspecialchars($event['title']) ?></strong>
                                    <?php if ($event['location']): ?>
                                        <div class="event-location">
                                            📍 <?= htmlspecialchars($event['location']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($event['description']): ?>
                                        <div class="event-description">
                                            <?= htmlspecialchars(substr(strip_tags($event['description']), 0, 100)) ?><?= strlen(strip_tags($event['description'])) > 100 ? '...' : '' ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="event-datetime">
                                    <div class="datetime-start">
                                        <strong><?= $start_date->format('M j, Y') ?></strong>
                                        <span class="time"><?= $start_date->format('g:i A') ?></span>
                                    </div>
                                    <?php if ($start_date->format('Y-m-d') !== $end_date->format('Y-m-d')): ?>
                                        <div class="datetime-end">
                                            to <?= $end_date->format('M j, Y g:i A') ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="datetime-end">
                                            to <?= $end_date->format('g:i A') ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="event-timing">
                                        <?php if ($is_past): ?>
                                            <span class="timing-badge timing-past">Past</span>
                                        <?php elseif ($is_upcoming): ?>
                                            <span class="timing-badge timing-upcoming">Upcoming</span>
                                        <?php else: ?>
                                            <span class="timing-badge timing-current">Live</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($event['organizer_name']) ?></td>
                            <td>
                                <span class="status status-<?= $event['status'] ?>">
                                    <?= ucfirst($event['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if ($event['status'] === 'published'): ?>
                                        <a href="/events/event/<?= urlencode($event['slug']) ?>" target="_blank" class="btn btn-sm">View</a>
                                    <?php endif; ?>
                                    <a href="/admin/events/alter/<?= $event['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <button onclick="deleteEvent(<?= $event['id'] ?>, '<?= htmlspecialchars($event['title'], ENT_QUOTES) ?>')"
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
            <div class="empty-icon">📅</div>
            <h3>No events found</h3>
            <p>Get started by creating your first event.</p>
            <a href="/admin/events/alter" class="btn btn-primary">Create Event</a>
        </div>
    <?php endif; ?>
</div>

<script>
    function deleteEvent(id, title) {
        if (confirm(`Are you sure you want to delete "${title}"? This action cannot be undone.`)) {
            fetch(`/admin/events/delete/${id}`, {
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
                        alert('Failed to delete event: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    alert('Failed to delete event: ' + error.message);
                });
        }
    }
</script>

<style>
    .content-header {
        padding: 2rem;
        background: white;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-left h1 {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .header-left p {
        color: #666;
        margin: 0;
    }

    .content-filters {
        background: white;
        border-bottom: 1px solid #e0e0e0;
        padding: 0 2rem;
    }

    .filter-tabs {
        display: flex;
        gap: 0;
    }

    .filter-tab {
        padding: 1rem 1.5rem;
        text-decoration: none;
        color: #666;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
    }

    .filter-tab:hover {
        color: #3498db;
    }

    .filter-tab.active {
        color: #3498db;
        border-bottom-color: #3498db;
    }

    .content-body {
        padding: 2rem;
    }

    .events-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .events-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .events-table th {
        background: #f8f9fa;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 1px solid #e0e0e0;
    }

    .events-table td {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: top;
    }

    .events-table tr:last-child td {
        border-bottom: none;
    }

    .event-past {
        opacity: 0.7;
    }

    .event-current {
        background-color: #f0f8ff;
    }

    .event-info .event-title {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .event-location {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.25rem;
    }

    .event-description {
        font-size: 0.9rem;
        color: #666;
        line-height: 1.4;
    }

    .event-datetime {
        min-width: 140px;
    }

    .datetime-start {
        margin-bottom: 0.25rem;
    }

    .datetime-start strong {
        display: block;
        font-size: 0.95rem;
    }

    .time {
        color: #666;
        font-size: 0.85rem;
    }

    .datetime-end {
        color: #666;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .timing-badge {
        padding: 0.2rem 0.5rem;
        border-radius: 3px;
        font-size: 0.7rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .timing-past {
        background: #f8f9fa;
        color: #6c757d;
    }

    .timing-upcoming {
        background: #e7f3ff;
        color: #0066cc;
    }

    .timing-current {
        background: #fff3cd;
        color: #856404;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
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

        .events-table {
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

        .event-datetime {
            min-width: 120px;
        }
    }
</style>