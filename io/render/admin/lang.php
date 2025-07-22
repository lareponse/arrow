<?php
$path = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/../lang';
$languages = glob($path . '/*.php');
$currentLang = $_GET['lang'] ?? 'fr';
$currentFile = $path . '/' . $currentLang . '.php';

// Handle POST - save translations
if ($_POST) {
    $content = $_POST['content'] ?? [];

    // Backup existing file
    if (file_exists($currentFile)) {
        $backup = $currentFile . '.' . date('Y-m-d_H-i-s');
        copy($currentFile, $backup) or trigger_error("Backup failed: $backup", E_USER_WARNING);
    }

    $export = "<?php\n\nreturn " . var_export($content, true) . ";\n";
    file_put_contents($currentFile, $export) or trigger_error("Write failed: $currentFile", E_USER_ERROR);
    http_out(302, '', ['Location' => "?lang=$currentLang"]);
}

$content = file_exists($currentFile) ? (include $currentFile) : [];

// Group by section prefix
$sections = [];
foreach ($content as $key => $value) {
    [$section] = explode('.', $key, 2) + ['misc'];
    $sections[$section][] = ['key' => $key, 'value' => $value];
}

?>
<div class="page-header">
    <h1>Language Editor</h1>
    <div class="page-actions">
        <?php foreach ($languages as $file): $lang = basename($file, '.php'); ?>
            <a href="?lang=<?= $lang ?>"
                class="btn <?= $lang === $currentLang ? 'btn-primary' : 'btn-secondary' ?>">
                <?= strtoupper($lang) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<form method="post" class="alter-form">
<?= csrf_field(3600) ?>
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
                            name="content[<?= e($item['key']) ?>]"
                            value="<?= e($item['value']) ?>"
                            class="form-control">
                    </div>
                <?php endforeach; ?>
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
                <dd><?= count($content ?? []) ?></dd>
                <dt>Sections</dt>
                <dd><?= count($sections ?? []) ?></dd>
            </dl>
        </div>
    </aside>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
</form>

<?php
return fn($html, $args = []) =>
ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $html])[1];
