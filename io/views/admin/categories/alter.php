<div class="content-header">
    <div class="header-left">
        <h1><?= $data['is_edit'] ? 'Edit Category' : 'Create Category' ?></h1>
        <p><?= $data['is_edit'] ? 'Update category information' : 'Create a new content category' ?></p>
    </div>
    <div class="header-actions">
        <a href="/admin/categories" class="btn">← Back to Categories</a>
    </div>
</div>

<div class="content-body">
    <?php if (isset($data['errors']['general'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($data['errors']['general']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="form-container">
        <div class="form-group">
            <label for="name" class="form-label">Name *</label>
            <input type="text"
                id="name"
                name="name"
                class="form-control <?= isset($data['errors']['name']) ? 'error' : '' ?>"
                value="<?= htmlspecialchars($data['category']['name'] ?? '') ?>"
                required>
            <?php if (isset($data['errors']['name'])): ?>
                <div class="form-error"><?= htmlspecialchars($data['errors']['name']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="slug" class="form-label">URL Slug *</label>
            <input type="text"
                id="slug"
                name="slug"
                class="form-control <?= isset($data['errors']['slug']) ? 'error' : '' ?>"
                value="<?= htmlspecialchars($data['category']['slug'] ?? '') ?>"
                placeholder="category-url-slug"
                required>
            <div class="form-help">URL-friendly version of the name (lowercase, hyphens only)</div>
            <?php if (isset($data['errors']['slug'])): ?>
                <div class="form-error"><?= htmlspecialchars($data['errors']['slug']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description"
                name="description"
                class="form-control"
                rows="4"
                placeholder="Brief description of this category"><?= htmlspecialchars($data['category']['description'] ?? '') ?></textarea>
            <div class="form-help">Optional description of what this category contains</div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= $data['is_edit'] ? 'Update Category' : 'Create Category' ?>
            </button>
            <a href="/admin/categories" class="btn">Cancel</a>
        </div>
    </form>
</div>

<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
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
</script>