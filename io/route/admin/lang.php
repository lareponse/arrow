<?php
return function () {
    $path = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/../lang';
    $languages = glob($path . '/*.php');
    $currentLang = $_GET['lang'] ?? 'fr';
    $currentFile = $path . '/' . $currentLang . '.php';

    // Handle POST - save translations
    if ($_POST) {
        $content = $_POST['content'] ?? [];

        // Backup existing file
        if (file_exists($currentFile)) {
            $backup = $currentFile . '.' . date('Ymd_His');
            copy($currentFile, $backup) or throw new RuntimeException("Backup failed: $backup", 500);
        }

        $export = "<?php\n\nreturn " . var_export($content, true) . ";\n";
        file_put_contents($currentFile, $export) or throw new RuntimeException("Write failed: $currentFile", 500);
        http_out(200, '', ['Location' => "?lang=$currentLang"]);
    }

    $content = file_exists($currentFile) ? (include $currentFile) : [];

    // Group by section prefix
    $sections = [];
    foreach ($content as $key => $value) {
        [$section] = explode('.', $key, 2) + ['misc'];
        $sections[$section][] = ['key' => $key, 'value' => $value];
    }
    return compact('title', 'languages', 'currentLang', 'sections', 'content');
};
