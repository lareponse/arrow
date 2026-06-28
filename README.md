# ARROW

Arrow is a tiny PHP row helper. It lets one database row emerge as a small state machine: first there is only a table, then a loaded row, then schema, edits, extra data, and finally a save.

It is not an ORM. It is a compact persistence primitive for developer-controlled code where you want to see exactly when data is loaded, changed, separated, and written.

## A Row Emerges

Start with a closure for one table:

```php
use function lareponse\arrow\row;
use const lareponse\arrow\ROW_LOAD;
use const lareponse\arrow\ROW_GET;
use const lareponse\arrow\ROW_SCHEMA;
use const lareponse\arrow\ROW_SET;
use const lareponse\arrow\ROW_MORE;
use const lareponse\arrow\ROW_SAVE;
use const lareponse\arrow\ROW_ERROR;

$article = row($pdo, 'article', 'slug', ['id']);
```

At this point, nothing has been read from the database. The closure only knows the table, the unique key, and that `id` is immutable.

Load one row:

```php
$article(ROW_LOAD, ['slug' => 'how-to-php']);
```

or any other unique key:

```php
$article(ROW_LOAD, ['id' => 42]);
```

The loaded row creates the first schema:

```php
$schema = $article(ROW_GET | ROW_SCHEMA);
```

Set new data:

```php
$article(ROW_SET, [
    'title' => 'How to PHP',
    'published_at' => date('Y-m-d H:i:s'),
    'tags' => ['php', 'web']
]);
```

Schema decides what can be saved. Fields found in schema become `ROW_EDIT`; unknown fields stay in `ROW_MORE`.

```php
$extra = $article(ROW_GET | ROW_MORE);
$article(ROW_SAVE);
```

That is the core Arrow model: row state emerges from the order of operations.

## Basic Row Work

### Read

```php
$article = row($pdo, 'article', 'slug');
$data = $article(ROW_GET | ROW_LOAD, ['slug' => 'how-to-php']);
```

### Create

```php
use const lareponse\arrow\ROW_CREATE;

$article = row($pdo, 'article');
$article(ROW_CREATE, [
    'slug' => 'new-title',
    'title' => 'New Title',
    'content' => 'Content'
]);
```

`ROW_CREATE` discovers schema, sorts incoming data, and saves schema fields.

### Update

```php
$article = row($pdo, 'article', 'slug');
$article(ROW_LOAD, ['slug' => 'how-to-php']);
$article(ROW_SET, ['title' => 'Updated Title']);
$article(ROW_SAVE);
```

Or use the compound update:

```php
use const lareponse\arrow\ROW_UPDATE;

row($pdo, 'article', 'slug')(ROW_UPDATE, [
    'slug' => 'how-to-php',
    'title' => 'Updated Title'
]);
```

Arrow does not model delete. It focuses on single-row create, read, update, state separation, and save.

## Schema Is The Boundary

Schema can appear in three ways:

- successful first load: Arrow derives schema from the loaded row columns
- introspection: `$article(ROW_SCHEMA | ROW_SET)`
- manual schema: `$article(ROW_SCHEMA | ROW_SET, array_flip([...]))`

Once schema exists, it becomes strict for SQL-facing row work:

- `ROW_LOAD` filters must exist in schema
- `ROW_SET` fields inside schema go to `ROW_EDIT`, unless they are immutable
- `ROW_SET` fields outside schema go to `ROW_MORE`
- `ROW_SAVE` requires schema and only persists `ROW_EDIT`

Before schema exists, the first `ROW_LOAD` can only use identifier-shaped filter names. This keeps the first discovery step flexible without letting arbitrary text become SQL structure.

## Use Cases

### Auto-Increment Id

When a table has an auto-increment id but the row is saved by another unique field, declare `id` immutable:

```php
$article = row($pdo, 'article', 'slug', ['id']);
$article(ROW_LOAD, ['id' => 42]);
$article(ROW_SET, $_POST);
$article(ROW_SAVE);
```

The row can be loaded by `id`, but `id` will not be added to `ROW_EDIT`.

### Edit Form With Extra Fields

HTML forms often contain fields that do not belong in the table: tokens, UI flags, tag lists, consent checkboxes, or relation data.

```php
$article = row($pdo, 'article', 'slug');
$article(ROW_LOAD, ['slug' => $_POST['slug']]);
$article(ROW_SET, $_POST);

$article_data = $article(ROW_GET);
$extra_data = $article(ROW_GET | ROW_MORE);

$article(ROW_SAVE);
```

Table fields are saved. Everything else remains available in `ROW_MORE` for custom handling.

### Create From Request Data

```php
$article = row($pdo, 'article');
$article(ROW_CREATE, $_POST);

if ($error = $article(ROW_GET | ROW_ERROR)) {
    // handle Throwable
}
```

`ROW_CREATE` is useful when you want Arrow to introspect the table before sorting request data.

### Custom Unique Key

```php
$article = row($pdo, 'article', 'slug');
$article(ROW_UPDATE, [
    'slug' => 'how-to-php',
    'title' => 'Updated Title'
]);
```

The third argument tells Arrow which field identifies the row for update flows.

### Batch Imports

```php
use const lareponse\arrow\ROW_RESET;

$article = row($pdo, 'article');

foreach ($rows as $data) {
    $article(ROW_RESET);
    $article(ROW_CREATE, $data);
}
```

`ROW_RESET` clears the closure state between rows while keeping the same table context.

## Reference

Detailed flags, return values, SQL generation, identifier quoting, and helper behavior live in [API.md](API.md).
