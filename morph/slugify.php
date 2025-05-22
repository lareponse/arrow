<?php

function slugify(string $text): string
{
    // 1. Strip HTML tags and normalize whitespace
    $text = strip_tags(trim($text));
    $text = preg_replace('/\s+/u', ' ', $text);

    // 2. Transliterate Unicode to ASCII where possible (requires PHP intl extension)
    if (class_exists(\Transliterator::class)) {
        $transliterator = \Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC; Latin-ASCII');
        $text = $transliterator->transliterate($text) ?: $text;
    } else {
        // Fallback to iconv if intl is unavailable
        $text = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    }

    // 3. Convert to lowercase (multibyte-safe)
    $text = mb_strtolower($text, 'UTF-8');

    // 4. Replace any remaining non-alphanumeric characters with hyphens
    $text = preg_replace('/[^\p{L}\p{Nd}]+/u', '-', $text);

    // 5. Collapse multiple hyphens into one
    $text = preg_replace('/-+/u', '-', $text);

    // 6. Trim hyphens from the ends
    $slug = trim($text, '-');

    // 7. Fallback to a unique ID if slug is empty
    if ($slug === '') {
        $slug = uniqid('item-', true);
    }

    return $slug;
}
