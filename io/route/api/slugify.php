<?php
return function ($args): string {
    // 1. Strip HTML tags and normalize whitespace
    $text = strip_tags(trim($text));
    $text = preg_replace('/\s+/u', ' ', $text);

    // 2. Transliterate Unicode to ASCII
    if (class_exists(\Transliterator::class)) {
        $transliterator = \Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC; Latin-ASCII');
        $text = $transliterator->transliterate($text) ?: $text;
    } else {
        $text = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    }

    // 3. Convert to lowercase
    $text = mb_strtolower($text, 'UTF-8');

    // 4. Replace non-alphanumeric sequences with hyphens
    $text = preg_replace('/[^\p{L}\p{Nd}]+/u', '-', $text);

    // 5. Collapse multiple hyphens into one
    $text = preg_replace('/-+/u', '-', $text);

    // 6. Trim hyphens from the ends
    $slug = trim($text, '-');

    // 7. Fallback to a unique ID if empty
    if ($slug === '') {
        $slug = uniqid('item-', true);
    }

    http_out(200, $slug, [
        'Content-Type' => 'text/plain; charset=utf-8',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => '0',
    ]);
    exit;
};
