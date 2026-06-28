# Arrow API

Reference for the `lareponse\arrow` single-row persistence helpers.

## Imports

```php
use function lareponse\arrow\row;
use function lareponse\arrow\qb_id;

use const lareponse\arrow\ROW_CREATE;
use const lareponse\arrow\ROW_EDIT;
use const lareponse\arrow\ROW_ERROR;
use const lareponse\arrow\ROW_GET;
use const lareponse\arrow\ROW_LOAD;
use const lareponse\arrow\ROW_MORE;
use const lareponse\arrow\ROW_RESET;
use const lareponse\arrow\ROW_SAVE;
use const lareponse\arrow\ROW_SCHEMA;
use const lareponse\arrow\ROW_SET;
use const lareponse\arrow\ROW_UPDATE;
```

## Core Function

```php
$article = row($pdo, 'article');
$article = row($pdo, 'article', 'slug');
$article = row($pdo, 'article', 'slug', ['id']);
```

`row()` returns a closure that keeps one row context in memory. The context can load one row, discover or receive schema, sort incoming data into editable and auxiliary state, save edits, return selected state, and keep the latest exception in `ROW_ERROR`.

`$table` and `$unique` must be SQL identifier-shaped names. They are validated before the closure is returned.

The optional fourth argument lists immutable fields. Immutable fields can be loaded and read, but never enter `ROW_EDIT`.

## Basic Operations

### Read

```php
$article = row(db(), 'article');
$form_data = $article(ROW_GET | ROW_LOAD, ['slug' => 'how-to-php']);
```

The first successful load also creates schema from the returned database columns.

### Update

```php
$article = row(db(), 'article');
$article(ROW_LOAD, ['slug' => 'how-to-php']);
$article(ROW_SET, [
    'title' => 'New Title',
    'published_at' => date('Y-m-d H:i:s')
]);
$article(ROW_SAVE);
```

### Create

```php
$post_data = [
    'title' => 'New Article',
    'content' => 'Content',
    'slug' => 'new-title'
];

$article = row(db(), 'article');
$article(ROW_CREATE, $post_data);
```

One-line form:

```php
row(db(), 'article')(ROW_CREATE, $post_data);
```

### Compound Update

```php
$clean_data = [
    'id' => 42,
    'content' => 'Content with extra',
    'slug' => 'new-title'
];

$article = row(db(), 'article');
$article(ROW_UPDATE, $clean_data);
```

With a custom unique field:

```php
$clean_data = [
    'slug' => 'new-title',
    'title' => 'New Title'
];

$article = row(db(), 'article', 'slug');
$article(ROW_UPDATE, $clean_data);
```

## Schema

Schema is the save boundary. Fields inside schema can become `ROW_EDIT`; unknown fields stay in `ROW_MORE`.

### Automatic Schema

```php
$article(ROW_LOAD, ['slug' => 'how-to-php']);
$schema = $article(ROW_GET | ROW_SCHEMA);
```

### Manual Schema

`ROW_SCHEMA` expects field names as array keys:

```php
$article(ROW_SCHEMA | ROW_SET, array_flip([
    'slug',
    'title',
    'content',
    'published_at'
]));
```

### Introspected Schema

```php
$article(ROW_SCHEMA | ROW_SET);
```

With no schema boat, Arrow calls `select_schema()` for the configured table.

## Data Separation

```php
$article(ROW_SET, [
    'title' => 'Valid field',
    'article_tags' => ['php', 'web']
]);
```

If `title` is in schema, it goes to `ROW_EDIT`. If `article_tags` is not in schema, it goes to `ROW_MORE`.

`ROW_MORE` data is never saved to the database.

## Schema Boundary Rules

- No schema yet: `ROW_LOAD` filter names must be SQL identifier-shaped.
- Schema exists: `ROW_LOAD` filter names must exist in `ROW_SCHEMA`.
- `ROW_SET`: schema fields become `ROW_EDIT`; unknown fields become `ROW_MORE`; immutable fields are ignored.
- `ROW_SAVE`: requires schema and persists only fields inside schema.

## Immutable Fields

Use the fourth `row()` argument for fields that must never be edited, such as auto-increment ids:

```php
$article = row($pdo, 'article', 'slug', ['id']);
$article(ROW_LOAD, ['id' => 42]);
$article(ROW_SET, [
    'id' => 99,
    'title' => 'Updated Title'
]);
```

`id` can be used to load the row, but it will not enter `ROW_EDIT`.

## Retrieval

```php
$valid_data = $article(ROW_GET);
$edit_only = $article(ROW_GET | ROW_EDIT);
$more_only = $article(ROW_GET | ROW_MORE);
$everything = $article(ROW_GET | ROW_LOAD | ROW_EDIT | ROW_MORE);
```

Field filters:

```php
$subset = $article(ROW_GET, ['slug', 'title']);
$title = $article(ROW_GET, ['title']);
```

A single requested field returns the value directly.

## SQL Generation

Arrow only generates SQL for changed values:

```php
$article(ROW_LOAD, ['slug' => 'how-to-php']);
$article(ROW_SET, [
    'title' => 'How to PHP',
    'published_at' => '2023-10-01 12:00:00'
]);
$article(ROW_SAVE);
```

If only `published_at` changed, Arrow generates an update for that field only.

## Identifier Quoting

Arrow quotes SQL identifiers with `qb_id()`.

```php
qb_id('article');      // `article` by default
qb_id('article', '"'); // "article"
```

`SQL_IDENTIFIER_QUOTE` is set to backticks by default for MySQL, MariaDB, and SQLite. Use double quotes for PostgreSQL-style identifier quoting.

## Error Handling

Arrow captures exceptions in internal state:

```php
$article(ROW_SAVE);

if ($error = $article(ROW_GET | ROW_ERROR)) {
    // $error is Throwable
}
```

## State Reset

```php
$article(ROW_RESET);
```

`ROW_RESET` clears the closure row state. The table and unique key remain part of the closure.

## Reusing Closures

```php
$article = row(db(), 'article');

foreach ($bulk_data as $data) {
    $article(ROW_RESET);
    $article(ROW_CREATE, $data);
}
```

## Return Values

- Most operations return the internal row state array.
- `ROW_GET` returns requested data.
- `ROW_GET | ROW_SCHEMA` returns schema.
- `ROW_GET | ROW_ERROR` returns the last captured error or `null`.
- `ROW_GET` with one requested field returns that field value.

## Flags

| State | Value | Purpose |
| --- | ---: | --- |
| `ROW_LOAD` | `1` | Loaded row from database |
| `ROW_SCHEMA` | `2` | Column definitions |
| `ROW_EDIT` | `4` | Valid alterations inside schema |
| `ROW_MORE` | `8` | Auxiliary data outside schema |
| `ROW_ERROR` | `128` | Error state |

| Operation | Value | Purpose |
| --- | ---: | --- |
| `ROW_LOAD` | `1` | Load row from database and set schema |
| `ROW_SAVE` | `16` | Persist changes to database |
| `ROW_SET` | `32` | Apply data to internal state |
| `ROW_GET` | `64` | Retrieve data from internal state |
| `ROW_RESET` | `256` | Clear internal state |

| Compound | Components | Purpose |
| --- | --- | --- |
| `ROW_CREATE` | `ROW_SCHEMA | ROW_SET | ROW_SAVE` | Create a row, populate schema, then save |
| `ROW_UPDATE` | `ROW_LOAD | ROW_SET | ROW_SAVE` | Load an existing row, apply changes, then save |
