<div class="content-header">
    <div class="header-left">
        <h1><?= $data['is_edit'] ? 'Edit Event' : 'Create Event' ?></h1>
        <p><?= $data['is_edit'] ? 'Update event information' : 'Create a new event' ?></p>
    </div>
    <div class="header-actions">
        <a href="/admin/events" class="btn">← Back to Events</a>
    </div>
</div>

<div class="content-body">
    <?php if (isset($data['errors']['general'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($data['errors']['general']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="event-form">
        <div class="form-grid">
            <div class="form-main">
                <div class="form-group">
                    <label for="title" class="form-label">Title *</label>
                    <input type="text"
                        id="title"
                        name="title"
                        class="form-control <?= isset($data['errors']['title']) ? 'error' : '' ?>"
                        value="<?= htmlspecialchars($data['event']['title'] ?? '') ?>"
                        required>
                    <?php if (isset($data['errors']['title'])): ?>
                        <div class="form-error"><?= htmlspecialchars($data['errors']['title']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="slug" class="form-label">URL Slug *</label>
                    <input type="text"
                        id="slug"
                        name="slug"
                        class="form-control <?= isset($data['errors']['slug']) ? 'error' : '' ?>"
                        value="<?= htmlspecialchars($data['event']['slug'] ?? '') ?>"
                        placeholder="event-url-slug"
                        required>
                    <div class="form-help">URL-friendly version of the title</div>
                    <?php if (isset($data['errors']['slug'])): ?>
                        <div class="form-error"><?= htmlspecialchars($data['errors']['slug']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description *</label>
                    <textarea id="description"
                        name="description"
                        class="form-control content-editor <?= isset($data['errors']['description']) ? 'error' : '' ?>"
                        rows="10"
                        required><?= htmlspecialchars($data['event']['description'] ?? '') ?></textarea>
                    <?php if (isset($data['errors']['description'])): ?>
                        <div class="form-error"><?= htmlspecialchars($data['errors']['description']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="location" class="form-label">Location</label>
                    <input type="text"
                        id="location"
                        name="location"
                        class="form-control"
                        value="<?= htmlspecialchars($data['event']['location'] ?? '') ?>"
                        placeholder="Event venue or online platform">
                    <div class="form-help">Physical address or online meeting details</div>
                </div>
            </div>

            <div class="form-sidebar">
                <div class="sidebar-section">
                    <h3>Schedule</h3>
                    <div class="form-group">
                        <label for="start_datetime" class="form-label">Start Date & Time *</label>
                        <input type="datetime-local"
                            id="start_datetime"
                            name="start_datetime"
                            class="form-control <?= isset($data['errors']['start_datetime']) ? 'error' : '' ?>"
                            value="<?= isset($data['event']['start_datetime']) ? date('Y-m-d\TH:i', strtotime($data['event']['start_datetime'])) : '' ?>"
                            required>
                        <?php if (isset($data['errors']['start_datetime'])): ?>
                            <div class="form-error"><?= htmlspecialchars($data['errors']['start_datetime']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="end_datetime" class="form-label">End Date & Time *</label>
                        <input type="datetime-local"
                            id="end_datetime"
                            name="end_datetime"
                            class="form-control <?= isset($data['errors']['end_datetime']) ? 'error' : '' ?>"
                            value="<?= isset($data['event']['end_datetime']) ? date('Y-m-d\TH:i', strtotime($data['event']['end_datetime'])) : '' ?>"
                            required>
                        <?php if (isset($data['errors']['end_datetime'])): ?>
                            <div class="form-error"><?= htmlspecialchars($data['errors']['end_datetime']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="sidebar-section">
                    <h3>Publish</h3>
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="draft" <?= ($data['event']['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>
                                Draft
                            </option>
                            <option value="published" <?= ($data['event']['status'] ?? '') === 'published' ? 'selected' : '' ?>>
                                Published
                            </option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-block">
                            <?= $data['is_edit'] ? 'Update Event' : 'Create Event' ?>
                        </button>
                    </div>
                </div>

                <div class="sidebar-section">
                    <h3>Featured Image</h3>
                    <div class="form-group">
                        <label for="image_url" class="form-label">Image URL</label>
                        <input type="url"
                            id="image_url"
                            name="image_url"
                            class="form-control"
                            value="<?= htmlspecialchars($data['event']['image_url'] ?? '') ?>"
                            placeholder="https://example.com/image.jpg">
                        <div class="form-help">Optional featured image for the event</div>
                    </div>
                    <?php if (!empty($data['event']['image_url'])): ?>
                        <div class="image-preview">
                            <img src="<?= htmlspecialchars($data['event']['image_url']) ?>"
                                alt="Featured image preview"
                                style="max-width: 100%; height: auto; border-radius: 4px;">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Auto-generate slug from title
    document.getElementById('title').addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');

        const slugField = document.getElementById('slug');
        if (!slugField.value || <?= $data['is_edit'] ? 'false' : 'true' ?>) {
            slugField.value = slug;
        }
    });

    // Image preview
    document.getElementById('image_url').addEventListener('input', function() {
        const previewContainer = document.querySelector('.image-preview');
        const imageUrl = this.value.trim();

        if (imageUrl) {
            if (!previewContainer) {
                const newPreview = document.createElement('div');
                newPreview.className = 'image-preview';
                this.parentNode.appendChild(newPreview);
            }

            const img = document.createElement('img');
            img.src = imageUrl;
            img.alt = 'Featured image preview';
            img.style.cssText = 'max-width: 100%; height: auto; border-radius: 4px; margin-top: 0.5rem;';

            img.onerror = function() {
                if (previewContainer) previewContainer.remove();
            };

            const container = document.querySelector('.image-preview') || document.createElement('div');
            container.className = 'image-preview';
            container.innerHTML = '';
            container.appendChild(img);

            if (!document.querySelector('.image-preview')) {
                this.parentNode.appendChild(container);
            }
        } else if (previewContainer) {
            previewContainer.remove();
        }
    });
</script>