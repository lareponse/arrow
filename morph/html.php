<?php

function e($content, ?string $field=null){
    $e = is_array($content) 
            ? (array_key_exists($field, $content) ? $content[$field] : $field)
            : $content;
    return htmlspecialchars((string)$e, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function l($key, $file = null): string
{
    static $cache = [];

    if ($file && file_exists($file))
        $cache = include $file;

    return $cache[$key] ?? $key;
}