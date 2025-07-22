<div class="page-header">
    <h1>Language Editor</h1>
    <div class="page-actions">
        <?php foreach ($languages as $file): ?>
            <?php $lang = basename($file, '.php'); ?>
            <a href="?lang=<?= $lang ?>"
                class="btn <?= $lang === $currentLang ? 'btn-primary' : 'btn-secondary' ?>">
                <?= strtoupper($lang) ?>
            </a>
        <?php endforeach; ?>
        <a href="?action=new" class="btn btn-outline">+ Add Language</a>
    </div>
</div>

<form method="post" class="alter-form">
    <div class="form-main">
        <?php foreach ($sections as $sectionName => $items): ?>
            <div class="meta-box">
                <header>
                    <h2><?= ucfirst($sectionName) ?></h2>
                </header>
                <?php foreach ($items as $item): ?>
                    <div class="form-group">
                        <label><?= $item['key'] ?></label>
                        <input type="text"
                            name="content[<?= htmlspecialchars($item['key']) ?>]"
                            value="<?= htmlspecialchars($item['value']) ?>"
                            class="form-control">
                    </div>
                <?php endforeach; ?>
                <button type="button" class="btn btn-outline btn-sm add-key"
                    data-section="<?= $sectionName ?>">+ Add Key</button>
            </div>
        <?php endforeach; ?>
    </div>

    <aside>
        <div class="meta-box">
            <header>
                <h2>Language Info</h2>
            </header>
            <dl class="stats-list">
                <dt>File</dt>
                <dd><code><?= $currentLang ?>.php</code></dd>
                <dt>Keys</dt>
                <dd><?= count($content) ?></dd>
                <dt>Sections</dt>
                <dd><?= count($sections) ?></dd>
            </dl>
        </div>
    </aside>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <button type="button" class="btn btn-outline">Add Section</button>
    </div>
</form>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};