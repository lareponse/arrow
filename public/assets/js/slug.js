export default function slugify(text) {
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
};