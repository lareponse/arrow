<script>
function slugify(text) {
    // 1. Strip HTML tags and normalize whitespace
    text = text.replace(/<[^>]*>/g, '').trim();
    text = text.replace(/\s+/g, ' ');

    // 2. Transliterate Unicode to ASCII
    text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');

    // 3. Convert to lowercase
    text = text.toLowerCase();

    // 4. Replace non-alphanumeric sequences with hyphens
    text = text.replace(/[^\p{L}\p{N}]+/gu, '-');

    // 5. Collapse multiple hyphens into one
    text = text.replace(/-+/g, '-');

    // 6. Trim hyphens from the ends
    let slug = text.replace(/^-+|-+$/g, '');

    // 7. Fallback to a unique ID if empty
    if (slug === '') {
        slug = 'item-' + Date.now() + '-' + Math.random().toString(36).substr(2, 6);
    }
    return slug;
}

// Examples
console.log(slugify('Été en forêt')); // "ete-en-foret"
console.log(slugify('<h1>Hello, world!</h1>')); // "hello-world"
</script>
<?php
return function (string $text): string{
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

    return $slug;
};
