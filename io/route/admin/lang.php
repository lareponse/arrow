// admin/lang.php
<?php


$title = 'Language Editor';

$path = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/../lang';
$languages = glob($path . '/*.php');
vd($languages);

$currentLang = $_GET['lang'] ?? 'fr';
$currentFile = null;
foreach ($languages as $langFile) {
    $langName = basename($langFile, '.php');
    if ($langName === $currentLang) {
        $currentFile = $langFile;
        break;
    }
}

$content = file_exists($currentFile) ? include $currentFile : [];

// Group content by section (split on first dot)
$sections = [];
foreach ($content as $key => $value) {
    $parts = explode('.', $key, 2);
    $section = $parts[0];
    $sections[$section][] = ['key' => $key, 'value' => $value];
}

return [
    'title' => $title,
    'languages' => $languages,
    'currentLang' => basename($langFile, '.php'),
    'sections' => $sections,
];

?>