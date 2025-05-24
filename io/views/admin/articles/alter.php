<div class="content-header">
    <div class="header-left">
        <h1><?= $data['is_edit'] ? 'Edit Article' : 'Create Article' ?></h1>
        <p><?= $data['is_edit'] ? 'Update your article content' : 'Create a new blog post' ?></p>
    </div>
    <div class="header-actions">
        <a href="/admin/articles" class="btn">← Back to Articles</a>
    </div>
</div>

<div class="content-body">
    <?php if (isset($data['errors']['general'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($data['errors']['general']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="article-form">
        <div class="form-grid">
            <div class="form-main">
                <div class="form-group">
                    <label for="title" class="form-label">Title *</label>
                    <input type="text"
                        id="title"
                        name="title"
                        class="form-control <?= isset($data['errors']['title']) ? 'error' : '' ?>"
                        value="<?= htmlspecialchars($data['article']['title'] ?? '') ?>"
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
                        value="<?= htmlspecialchars($data['article']['slug'] ?? '') ?>"
                        placeholder="article-url-slug"
                        required>
                    <div class="form-help">URL-friendly version of the title (lowercase, hyphens only)</div>
                    <?php if (isset($data['errors']['slug'])): ?>
                        <div class="form-error"><?= htmlspecialchars($data['errors']['slug']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="excerpt" class="form-label">Excerpt</label>
                    <textarea id="excerpt"
                        name="excerpt"
                        class="form-control"
                        rows="3"
                        placeholder="Brief description of the article"><?= htmlspecialchars($data['article']['excerpt'] ?? '') ?></textarea>
                    <div class="form-help">Short summary shown in article listings</div>
                </div>

                <div class="form-group">
                    <label for="content" class="form-label">Content *</label>
                    <textarea id="content"
                        name="content"
                        class="form-control content-editor <?= isset($data['errors']['content']) ? 'error' : '' ?>"
                        rows="20"
                        required><?= htmlspecialchars($data['article']['content'] ?? '') ?></textarea>
                    <?php if (isset($data['errors']['content'])): ?>
                        <div class="form-error"><?= htmlspecialchars($data['errors']['content']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-sidebar">
                <div class="sidebar-section">
                    <h3>Publish</h3>
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="draft" <?= ($data['article']['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>
                                Draft
                            </option>
                            <option value="published" <?= ($data['article']['status'] ?? '') === 'published' ? 'selected' : '' ?>>
                                Published
                            </option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-block">
                            <?= $data['is_edit'] ? 'Update Article' : 'Create Article' ?>
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
                            value="<?= htmlspecialchars($data['article']['image_url'] ?? '') ?>"
                            placeholder="https://example.com/image.jpg">
                        <div class="form-help">Optional featured image for the article</div>
                    </div>
                    <?php if (!empty($data['article']['image_url'])): ?>
                        <div class="image-preview">
                            <img src="<?= htmlspecialchars($data['article']['image_url']) ?>"
                                alt="Featured image preview"
                                style="max-width: 100%; height: auto; border-radius: 4px;">
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($data['categories'])): ?>
                    <div class="sidebar-section">
                        <h3>Categories</h3>
                        <div class="categories-list">
                            <?php foreach ($data['categories'] as $category): ?>
                                <label class="checkbox-label">
                                    <input type="checkbox"
                                        name="categories[]"
                                        value="<?= $category['id'] ?>"
                                        <?= in_array($category['id'], $data['article']['categories'] ?? []) ? 'checked' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
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

        // Only auto-fill if slug is empty or if we're creating a new article
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

    // Basic content editor enhancements
    document.getElementById('content').addEventListener('keydown', function(e) {
        // Tab key inserts 4 spaces
        if (e.key === 'Tab') {
            e.preventDefault();
            const start = this.selectionStart;
            const end = this.selectionEnd;
            this.value = this.value.substring(0, start) + '    ' + this.value.substring(end);
            this.selectionStart = this.selectionEnd = start + 4;
        }
    });
</script>
