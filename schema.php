<?php

namespace lareponse\arrow;

use InvalidArgumentException;
use PDO;

const SCHEMA_TABLES        = 1;
const SCHEMA_COLUMNS       = 2;
const SCHEMA_FOREIGN_KEYS  = 4;
const SCHEMA_LABEL_COLUMNS = 8;
const SCHEMA_OPTIONS       = 16;
const SCHEMA_REF_LABELS    = 32;

const SCHEMA_GET           = 64;
const SCHEMA_RESET         = 128;

const SCHEMA_CACHE_SET     = 1;
const SCHEMA_CACHE_GET     = -1;
const SCHEMA_CACHE_RESET   = 0;

function schema(PDO $pdo, array $blacklist = [], ?callable $cache = null): callable
{
    $state = schema_cache();

    if ($cache !== null) {
        $stored = $cache(SCHEMA_CACHE_GET);

        if (is_array($stored)) {
            $state = schema_cache_merge($state, $stored);
        }
    }

    return function (int $behave, array $boat = []) use ($pdo, $blacklist, $cache, &$state) {
        if ($behave & SCHEMA_RESET) {
            $state = schema_cache();
            $cache && $cache(SCHEMA_CACHE_RESET);
            $behave &= ~SCHEMA_RESET;
        }

        if (!($behave & SCHEMA_GET)) {
            return null;
        }

        if ($behave & SCHEMA_TABLES) {
            $tables = schema_tables_cached($pdo, $blacklist, $state);
            schema_cache_write($cache, $state);
            return $tables;
        }

        $table = schema_boat_identifier($boat, 'table');
        schema_assert_allowed_table($pdo, $blacklist, $state, $table);

        if (($behave & SCHEMA_COLUMNS) && ($behave & SCHEMA_FOREIGN_KEYS)) {
            $columns = schema_columns_with_foreign_keys_cached($pdo, $blacklist, $state, $table);
            schema_cache_write($cache, $state);
            return $columns;
        }

        if ($behave & SCHEMA_COLUMNS) {
            $columns = schema_columns_cached($pdo, $state, $table);
            schema_cache_write($cache, $state);
            return $columns;
        }

        if ($behave & SCHEMA_FOREIGN_KEYS) {
            $foreign_keys = schema_foreign_keys_cached($pdo, $state, $table);
            schema_cache_write($cache, $state);
            return $foreign_keys;
        }

        if ($behave & SCHEMA_LABEL_COLUMNS) {
            $column = schema_boat_identifier($boat, 'column');
            schema_assert_column($pdo, $state, $table, $column);
            $label_columns = schema_label_columns_cached($pdo, $state, $table, $column);
            schema_cache_write($cache, $state);
            return $label_columns;
        }

        if ($behave & SCHEMA_OPTIONS) {
            $column = schema_boat_identifier($boat, 'column');
            schema_assert_column($pdo, $state, $table, $column);
            $options = schema_options_cached($pdo, $state, $table, $column);
            schema_cache_write($cache, $state);
            return $options;
        }

        if ($behave & SCHEMA_REF_LABELS) {
            $column = schema_boat_identifier($boat, 'column');
            schema_assert_column($pdo, $state, $table, $column);
            schema_load_missing_ref_labels($pdo, $table, $column, $boat['values'] ?? [], $state);
            $labels = schema_ref_labels_cached($state, $table, $column, $boat['values'] ?? []);
            schema_cache_write($cache, $state);
            return $labels;
        }

        return null;
    };
}

function schema_cache(): array
{
    return [
        'tables'        => null,
        'columns'       => [],
        'foreign_keys'  => [],
        'label_columns' => [],
        'options'       => [],
        'ref_labels'    => [],
    ];
}

function schema_cache_merge(array $base, array $stored): array
{
    foreach (['tables', 'columns', 'foreign_keys', 'label_columns'] as $key) {
        if (array_key_exists($key, $stored) && is_array($stored[$key])) {
            $base[$key] = $stored[$key];
        }
    }

    return $base;
}

function schema_cache_export(array $cache): array
{
    return [
        'tables'        => $cache['tables'],
        'columns'       => $cache['columns'],
        'foreign_keys'  => $cache['foreign_keys'],
        'label_columns' => $cache['label_columns'],
    ];
}

function schema_cache_write(?callable $cache, array $state): void
{
    $cache && $cache(SCHEMA_CACHE_SET, schema_cache_export($state));
}

function schema_boat_identifier(array $boat, string $key): string
{
    $identifier = (string) ($boat[$key] ?? '');
    assert_sql_identifier($identifier, __FUNCTION__ . ':invalid_' . $key);
    return $identifier;
}

function schema_cache_key(string $table, string $value_column): string
{
    return $table . "\0" . $value_column;
}

function schema_tables_cached(PDO $pdo, array $blacklist, array &$cache): array
{
    if ($cache['tables'] === null) {
        $cache['tables'] = schema_load_tables($pdo, $blacklist);
    }

    return $cache['tables'];
}

function schema_columns_cached(PDO $pdo, array &$cache, string $table): array
{
    if (!isset($cache['columns'][$table])) {
        $cache['columns'][$table] = schema_load_columns($pdo, $table);
    }

    return $cache['columns'][$table];
}

function schema_foreign_keys_cached(PDO $pdo, array &$cache, string $table): array
{
    if (!isset($cache['foreign_keys'][$table])) {
        $cache['foreign_keys'][$table] = schema_load_foreign_keys($pdo, $table);
    }

    return $cache['foreign_keys'][$table];
}

function schema_label_columns_cached(PDO $pdo, array &$cache, string $table, string $value_column): array
{
    $key = schema_cache_key($table, $value_column);

    if (!isset($cache['label_columns'][$key])) {
        $cache['label_columns'][$key] = schema_guess_label_columns(
            schema_columns_cached($pdo, $cache, $table),
            $value_column
        );
    }

    return $cache['label_columns'][$key];
}

function schema_options_cached(PDO $pdo, array &$cache, string $table, string $value_column): array
{
    $key = schema_cache_key($table, $value_column);

    if (!isset($cache['options'][$key])) {
        $cache['options'][$key] = schema_load_options($pdo, $table, $value_column, $cache);
    }

    return $cache['options'][$key];
}

function schema_columns_with_foreign_keys_cached(PDO $pdo, array $blacklist, array &$cache, string $table): array
{
    $columns = schema_columns_cached($pdo, $cache, $table);
    $tables = schema_tables_cached($pdo, $blacklist, $cache);

    foreach (schema_foreign_keys_cached($pdo, $cache, $table) as $name => $foreign_key) {
        if (!isset($columns[$name])) {
            continue;
        }

        $columns[$name]['Foreign'] = $foreign_key;
        $columns[$name]['ForeignOptions'] = in_array($foreign_key['table'], $tables, true)
            ? schema_options_cached($pdo, $cache, $foreign_key['table'], $foreign_key['column'])
            : [];
    }

    return $columns;
}

function schema_ref_labels_cached(array $cache, string $table, string $value_column, array $values): array
{
    $key = schema_cache_key($table, $value_column);
    $labels = [];

    foreach (schema_ref_values($values) as $value) {
        if (array_key_exists($value, $cache['ref_labels'][$key] ?? []) && $cache['ref_labels'][$key][$value] !== null) {
            $labels[$value] = $cache['ref_labels'][$key][$value];
        }
    }

    return $labels;
}

function schema_assert_allowed_table(PDO $pdo, array $blacklist, array &$cache, string $table): void
{
    if (!in_array($table, schema_tables_cached($pdo, $blacklist, $cache), true)) {
        throw new InvalidArgumentException(__FUNCTION__ . ':unknown_table:' . $table);
    }
}

function schema_assert_column(PDO $pdo, array &$cache, string $table, string $column): void
{
    if (!isset(schema_columns_cached($pdo, $cache, $table)[$column])) {
        throw new InvalidArgumentException(__FUNCTION__ . ':unknown_column:' . $table . '.' . $column);
    }
}

function schema_ref_values(array $values): array
{
    $ref_values = [];

    foreach ($values as $value) {
        $value = (string) $value;

        if ($value !== '') {
            $ref_values[$value] = $value;
        }
    }

    return array_values($ref_values);
}

function schema_quote_columns(array $columns): string
{
    foreach ($columns as $column) {
        assert_sql_identifier((string) $column, __FUNCTION__ . ':invalid_column');
    }

    return implode(', ', array_map(__NAMESPACE__ . '\\qb_id', $columns));
}

function schema_load_tables(PDO $pdo, array $blacklist = []): array
{
    $rows = row_run(
        $pdo,
        "SELECT TABLE_NAME
         FROM INFORMATION_SCHEMA.TABLES
         WHERE TABLE_SCHEMA = DATABASE()
           AND TABLE_TYPE = 'BASE TABLE'
         ORDER BY TABLE_NAME",
        []
    )->fetchAll(PDO::FETCH_COLUMN);
    $tables = [];

    foreach ($rows as $table) {
        $table = (string) $table;

        if (!in_array($table, $blacklist, true)) {
            $tables[] = $table;
        }
    }

    return $tables;
}

function schema_load_columns(PDO $pdo, string $table): array
{
    assert_sql_identifier($table, __FUNCTION__ . ':invalid_table');
    $rows = row_run(
        $pdo,
        "SELECT
            COLUMN_NAME AS Field,
            COLUMN_TYPE AS Type,
            IS_NULLABLE AS `Null`,
            COLUMN_KEY AS `Key`,
            COLUMN_DEFAULT AS `Default`,
            EXTRA AS Extra
         FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE()
           AND TABLE_NAME = ?
         ORDER BY ORDINAL_POSITION",
        [$table]
    )->fetchAll(PDO::FETCH_ASSOC);
    $columns = [];

    foreach ($rows as $row) {
        $columns[(string) $row['Field']] = $row;
    }

    return $columns;
}

function schema_load_foreign_keys(PDO $pdo, string $table): array
{
    assert_sql_identifier($table, __FUNCTION__ . ':invalid_table');
    $rows = row_run(
        $pdo,
        "SELECT
            kcu.COLUMN_NAME AS column_name,
            kcu.REFERENCED_TABLE_NAME AS referenced_table,
            kcu.REFERENCED_COLUMN_NAME AS referenced_column
         FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
         INNER JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
            ON tc.CONSTRAINT_SCHEMA = kcu.CONSTRAINT_SCHEMA
           AND tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
           AND tc.TABLE_SCHEMA = kcu.TABLE_SCHEMA
           AND tc.TABLE_NAME = kcu.TABLE_NAME
         WHERE kcu.TABLE_SCHEMA = DATABASE()
           AND kcu.TABLE_NAME = ?
           AND tc.CONSTRAINT_TYPE = 'FOREIGN KEY'
           AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
           AND kcu.REFERENCED_COLUMN_NAME IS NOT NULL",
        [$table]
    )->fetchAll(PDO::FETCH_ASSOC);
    $foreign_keys = [];

    foreach ($rows as $row) {
        $foreign_keys[(string) $row['column_name']] = [
            'table' => (string) $row['referenced_table'],
            'column' => (string) $row['referenced_column'],
        ];
    }

    return $foreign_keys;
}

function schema_guess_label_columns(array $columns, string $value_column): array
{
    assert_sql_identifier($value_column, __FUNCTION__ . ':invalid_column');
    $preferred = ['label', 'name', 'title', 'slug', 'email', 'code'];
    $labels = [];

    foreach ($preferred as $column) {
        if ($column !== $value_column && isset($columns[$column])) {
            $labels[] = $column;
        }
    }

    if ($labels !== []) {
        return array_slice($labels, 0, 2);
    }

    foreach ($columns as $name => $column) {
        $type = strtolower((string) ($column['Type'] ?? ''));

        if ($name !== $value_column && preg_match('/char|text|enum|set/', $type)) {
            return [$name];
        }
    }

    return [];
}

function schema_load_options(PDO $pdo, string $table, string $value_column, array &$cache): array
{
    assert_sql_identifier($table, __FUNCTION__ . ':invalid_table');
    assert_sql_identifier($value_column, __FUNCTION__ . ':invalid_column');

    return schema_select_options(
        $pdo,
        $table,
        $value_column,
        schema_label_columns_cached($pdo, $cache, $table, $value_column)
    );
}

function schema_select_options(PDO $pdo, string $table, string $value_column, array $label_columns): array
{
    assert_sql_identifier($table, __FUNCTION__ . ':invalid_table');
    assert_sql_identifier($value_column, __FUNCTION__ . ':invalid_column');

    $select_columns = array_unique([$value_column, ...$label_columns]);
    $quoted_columns = schema_quote_columns($select_columns);
    $quoted_table = qb_id($table);
    $quoted_value = qb_id($value_column);
    $quoted_order = $label_columns !== []
        ? qb_id($label_columns[0]) . ', ' . $quoted_value
        : $quoted_value;
    $rows = row_run(
        $pdo,
        "SELECT {$quoted_columns}
         FROM {$quoted_table}
         ORDER BY {$quoted_order}
         LIMIT 500",
        []
    )->fetchAll(PDO::FETCH_ASSOC);

    return array_map(static function (array $row) use ($value_column, $label_columns): array {
        $value = (string) ($row[$value_column] ?? '');
        $parts = schema_label_parts($row, $label_columns);

        return [
            'value' => $value,
            'label' => $parts === [] ? $value : implode(' - ', $parts),
        ];
    }, $rows);
}

function schema_load_missing_ref_labels(PDO $pdo, string $table, string $value_column, array $values, array &$cache): void
{
    assert_sql_identifier($table, __FUNCTION__ . ':invalid_table');
    assert_sql_identifier($value_column, __FUNCTION__ . ':invalid_column');

    $key = schema_cache_key($table, $value_column);
    $cache['ref_labels'][$key] ??= [];
    $missing = [];

    foreach (schema_ref_values($values) as $value) {
        if (!array_key_exists($value, $cache['ref_labels'][$key])) {
            $missing[] = $value;
        }
    }

    if ($missing === []) {
        return;
    }

    $labels = schema_select_ref_labels(
        $pdo,
        $table,
        $value_column,
        $missing,
        schema_label_columns_cached($pdo, $cache, $table, $value_column)
    );

    foreach ($missing as $value) {
        $cache['ref_labels'][$key][$value] = $labels[$value] ?? null;
    }
}

function schema_select_ref_labels(PDO $pdo, string $table, string $value_column, array $values, array $label_columns): array
{
    assert_sql_identifier($table, __FUNCTION__ . ':invalid_table');
    assert_sql_identifier($value_column, __FUNCTION__ . ':invalid_column');

    $values = schema_ref_values($values);

    if ($values === []) {
        return [];
    }

    $select_columns = array_unique([$value_column, ...$label_columns]);
    $quoted_columns = schema_quote_columns($select_columns);
    $quoted_table = qb_id($table);
    $quoted_value = qb_id($value_column);
    $placeholders = implode(', ', array_fill(0, count($values), '?'));
    $rows = row_run(
        $pdo,
        "SELECT {$quoted_columns}
         FROM {$quoted_table}
         WHERE {$quoted_value} IN ({$placeholders})",
        $values
    )->fetchAll(PDO::FETCH_ASSOC);
    $labels = [];

    foreach ($rows as $row) {
        $value = (string) ($row[$value_column] ?? '');
        $parts = schema_label_parts($row, $label_columns);
        $labels[$value] = $parts === [] ? $value : implode(' - ', $parts);
    }

    return $labels;
}

function schema_label_parts(array $row, array $label_columns): array
{
    $parts = [];

    foreach ($label_columns as $column) {
        $part = trim((string) ($row[$column] ?? ''));

        if ($part !== '') {
            $parts[] = $part;
        }
    }

    return $parts;
}
