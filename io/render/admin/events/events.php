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
