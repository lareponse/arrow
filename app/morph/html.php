<?php

function e($content, ?string $field = null)
{
    $e = is_array($content)
        ? (array_key_exists($field, $content) ? $content[$field] : $field)
        : $content;
    return htmlspecialchars((string)$e, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Initialize/reset cache with array
//      l(null, ['contact.email' => 'contact@email.dns']);
//
// Initialize/reset cachefrom file
//      l(null, require 'app/lang/eo.php');
//
// Usage examples:
//      echo l('contact.name');                      Simple translation
//      echo l('form.min_length', 8);                Translation with arguments
//      echo l('form.range', 5, 20);                 Translation with multiple arguments
function l($key, ...$args): string
{
    static $cache = [];

    // Handle initialization first
    if ($key === null && isset($args[0]) && is_array($args[0])) {
        $cache = $args[0];
        return '';
    }

    (empty($cache) || !isset($cache[$key])) && trigger_error("Language key '$key' not found in cache #" . count($cache), E_USER_NOTICE);

    $text = $cache[$key] ?? $key;

    if ($args) {
        try {
            $text = vsprintf($text, $args);
        } catch (\Exception $e) {
            trigger_error("Error formatting language string '$key': " . $e->getMessage(), E_USER_WARNING);
        }
    }

    return $text;
}

function viewport($section = null, $key=null): mixed
{
    static $cache = [];

    if (empty($cache)) {
        $cache = [
            'coproacademy' => db()->query("SELECT slug, label FROM `coproacademy`;")->fetchAll(PDO::FETCH_KEY_PAIR),
        ];
    }

    if($section === null)
        return $cache;

    if($key === null)
        return $cache[$section] ?? null;

    if(isset($cache[$section][$key]))
        return e($cache[$section],$key);

    return null;
}