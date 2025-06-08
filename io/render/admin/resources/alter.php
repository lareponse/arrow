<div class="content-header">
    <div class="header-left">
        <h1><?= $data['is_edit'] ? 'Edit Resource' : 'Upload Resource' ?></h1>
        <p><?= $data['is_edit'] ? 'Update resource information' : 'Upload a new resource file' ?></p>
    </div>
    <div class="header-actions">
        <a href="/admin/resources" class="btn">← Back to Resources</a>
    </div>
</div>

<div class="content-body">
    <?php if (isset($data['errors']['general'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($data['errors']['general']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="resource-form">
        <?= csrf_field() ?>

        <div class="form-grid">
            <div class="form-main">
                <div class="form-group">
                    <label for="title" class="form-label">Title *</label>
                    <input type="text"
                        id="title"
                        name="title"
                        class="form-control <?= isset($data['errors']['title']) ? 'error' : '' ?>"
                        value="<?= htmlspecialchars($data['resource']['title'] ?? '') ?>"
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
                        value="<?= htmlspecialchars($data['resource']['slug'] ?? '') ?>"
                        placeholder="resource-url-slug"
                        required>
                    <div class="form-help">URL-friendly version of the title</div>
                    <?php if (isset($data['errors']['slug'])): ?>
                        <div class="form-error"><?= htmlspecialchars($data['errors']['slug']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description"
                        name="description"
                        class="form-control"
                        rows="6"
                        placeholder="Describe what this resource contains and who it's for"><?= htmlspecialchars($data['resource']['description'] ?? '') ?></textarea>
                    <div class="form-help">Brief description of the resource content</div>
                </div>

                <?php if (!$data['is_edit']): ?>
                    <div class="form-group">
                        <label for="file" class="form-label">File *</label>
                        <input type="file"
                            id="file"
                            name="file"
                            class="form-control <?= isset($data['errors']['file']) ? 'error' : '' ?>"
                            required>
                        <div class="form-help">Supported formats: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP</div>
                        <?php if (isset($data['errors']['file'])): ?>
                            <div class="form-error"><?= htmlspecialchars($data['errors']['file']) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-sidebar">
                <div class="sidebar-section">
                    <h3>Publish</h3>
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="draft" <?= ($data['resource']['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>
                                Draft
                            </option>
                            <option value="published" <?= ($data['resource']['status'] ?? '') === 'published' ? 'selected' : '' ?>>
                                Published
                            </option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-block">
                            <?= $data['is_edit'] ? 'Update Resource' : 'Upload Resource' ?>
                        </button>
                    </div>
                </div>

                <?php if ($data['is_edit'] && !empty($data['resource']['file_path'])): ?>
                    <div class="sidebar-section">
                        <h3>Current File</h3>
                        <div class="file-info">
                            <div class="file-name">
                                <?= htmlspecialchars(basename($data['resource']['file_path'])) ?>
                            </div>
                            <div class="file-meta">
                                <span class="file-type"><?= strtoupper($data['resource']['file_type']) ?></span>
                                <span class="file-size"><?= number_format($data['resource']['file_size'] / 1024, 1) ?> KB</span>
                            </div>
                            <a href="/resources/download/<?= urlencode($data['resource']['slug']) ?>"
                                class="btn btn-sm" target="_blank">Download</a>
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

        const slugField = document.getElementById('slug');
        if (!slugField.value || <?= $data['is_edit'] ? 'false' : 'true' ?>) {
            slugField.value = slug;
        }
    });

    // File input feedback
    document.getElementById('file')?.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const fileInfo = document.createElement('div');
            fileInfo.className = 'file-preview';
            fileInfo.innerHTML = `
                <strong>${file.name}</strong><br>
                <small>${(file.size / 1024).toFixed(1)} KB</small>
            `;

            const existing = this.parentNode.querySelector('.file-preview');
            if (existing) existing.remove();

            this.parentNode.appendChild(fileInfo);
        }
    });
</script>