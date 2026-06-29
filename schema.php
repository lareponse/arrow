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
    $info = null;
    $memo = schema_memo();

    $information_schema = function () use ($pdo, $cache, &$info): array {
        if ($info !== null) {
            return $info;
        }

        $stored = $cache ? $cache(SCHEMA_CACHE_GET) : null;

        if (is_array($stored)) {
            return $info = $stored;
        }

        $info = schema_load_information_schema($pdo);
        $cache && $cache(SCHEMA_CACHE_SET, $info);

        return $info;
    };

    $tables = function () use ($blacklist, $information_schema, &$memo): array {
        return $memo['tables'] ??= schema_tables($information_schema(), $blacklist);
    };

    $columns = function (string $table) use ($information_schema, &$memo): array {
        return $memo['columns'][$table] ??= schema_columns($information_schema(), $table);
    };

    $foreign_keys = function (string $table) use ($information_schema, &$memo): array {
        return $memo['foreign_keys'][$table] ??= schema_foreign_keys($information_schema(), $table);
    };

    return function (int $behave, array $boat = []) use ($pdo, $blacklist, $cache, &$info, &$memo, $tables, $columns, $foreign_keys) {
        if ($behave & SCHEMA_RESET) {
            $info = null;
            $memo = schema_memo();
            $cache && $cache(SCHEMA_CACHE_RESET);
            $behave &= ~SCHEMA_RESET;
        }

        if (!($behave & SCHEMA_GET)) {
            return null;
        }

        if ($behave & SCHEMA_TABLES) {
            return $tables();
        }

        $table = schema_boat_identifier($boat, 'table');

        in_array($table, $tables(), true) || throw new InvalidArgumentException(__FUNCTION__ . ':unknown_table:' . $table);

        if (($behave & SCHEMA_COLUMNS) && ($behave & SCHEMA_FOREIGN_KEYS)) {
            return schema_columns_with_foreign_keys($pdo, $columns($table), $foreign_keys($table), $tables(), $columns, $memo);
        }

        if ($behave & SCHEMA_COLUMNS) {
            return $columns($table);
        }

        if ($behave & SCHEMA_FOREIGN_KEYS) {
            return $foreign_keys($table);
        }

        if ($behave & SCHEMA_LABEL_COLUMNS) {
            $column = schema_boat_identifier($boat, 'column');
            schema_assert_column($columns($table), $table, $column);
            return $memo['label_columns'][schema_cache_key($table, $column)] ??= schema_guess_label_columns($columns($table), $column);
        }

        if ($behave & SCHEMA_OPTIONS) {
            $column = schema_boat_identifier($boat, 'column');
            schema_assert_column($columns($table), $table, $column);
            return schema_options($pdo, $table, $column, $memo, $columns($table));
        }

        if ($behave & SCHEMA_REF_LABELS) {
            $column = schema_boat_identifier($boat, 'column');
            schema_assert_column($columns($table), $table, $column);
            schema_load_missing_ref_labels($pdo, $table, $column, $boat['values'] ?? [], $memo, $columns($table));
            return schema_ref_labels($memo, $table, $column, $boat['values'] ?? []);
        }

        return null;
    };
}

function schema_memo(): array
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

function schema_columns_with_foreign_keys(PDO $pdo, array $columns, array $foreign_keys, array $tables, callable $schema_columns, array &$memo): array
{
    foreach ($foreign_keys as $name => $foreign_key) {
        if (!isset($columns[$name])) {
            continue;
        }

        $columns[$name]['Foreign'] = $foreign_key;
        $columns[$name]['ForeignOptions'] = in_array($foreign_key['table'], $tables, true)
            ? schema_options(
                $pdo,
                $foreign_key['table'],
                $foreign_key['column'],
                $memo,
                $schema_columns($foreign_key['table'])
            )
            : [];
    }

    return $columns;
}

function schema_ref_labels(array $memo, string $table, string $value_column, array $values): array
{
    $key = schema_cache_key($table, $value_column);
    $labels = [];

    foreach (schema_ref_values($values) as $value) {
        if (array_key_exists($value, $memo['ref_labels'][$key] ?? []) && $memo['ref_labels'][$key][$value] !== null) {
            $labels[$value] = $memo['ref_labels'][$key][$value];
        }
    }

    return $labels;
}

function schema_assert_column(array $columns, string $table, string $column): void
{
    if (!isset($columns[$column])) {
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

function schema_load_information_schema(PDO $pdo): array
{
    return row_run($pdo, schema_information_schema_query(), [])->fetchAll(PDO::FETCH_ASSOC);
}

function schema_information_schema_query(): string
{
    return "SELECT
            t.TABLE_NAME,
            t.TABLE_TYPE,
            c.COLUMN_NAME,
            c.COLUMN_TYPE,
            c.IS_NULLABLE,
            c.COLUMN_KEY,
            c.COLUMN_DEFAULT,
            c.EXTRA,
            c.ORDINAL_POSITION,
            tc.CONSTRAINT_TYPE,
            kcu.REFERENCED_TABLE_NAME,
            kcu.REFERENCED_COLUMN_NAME
         FROM INFORMATION_SCHEMA.TABLES t
         INNER JOIN INFORMATION_SCHEMA.COLUMNS c
            ON c.TABLE_SCHEMA = t.TABLE_SCHEMA
           AND c.TABLE_NAME = t.TABLE_NAME
         LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
            ON kcu.TABLE_SCHEMA = c.TABLE_SCHEMA
           AND kcu.TABLE_NAME = c.TABLE_NAME
           AND kcu.COLUMN_NAME = c.COLUMN_NAME
           AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
           AND kcu.REFERENCED_COLUMN_NAME IS NOT NULL
         LEFT JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
            ON tc.CONSTRAINT_SCHEMA = kcu.CONSTRAINT_SCHEMA
           AND tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
           AND tc.TABLE_SCHEMA = kcu.TABLE_SCHEMA
           AND tc.TABLE_NAME = kcu.TABLE_NAME
           AND tc.CONSTRAINT_TYPE = 'FOREIGN KEY'
         WHERE t.TABLE_SCHEMA = DATABASE()
           AND t.TABLE_TYPE = 'BASE TABLE'
         ORDER BY t.TABLE_NAME, c.ORDINAL_POSITION";
}

function schema_tables(array $info, array $blacklist = []): array
{
    $tables = [];
    $seen = [];

    foreach ($info as $row) {
        $table = (string) ($row['TABLE_NAME'] ?? '');

        if ($table !== '' && !isset($seen[$table]) && !in_array($table, $blacklist, true)) {
            $tables[] = $table;
            $seen[$table] = true;
        }
    }

    return $tables;
}

function schema_columns(array $info, string $table): array
{
    $columns = [];

    foreach ($info as $row) {
        if (($row['TABLE_NAME'] ?? null) !== $table) {
            continue;
        }

        $columns[(string) $row['COLUMN_NAME']] = [
            'Field'   => $row['COLUMN_NAME'],
            'Type'    => $row['COLUMN_TYPE'],
            'Null'    => $row['IS_NULLABLE'],
            'Key'     => $row['COLUMN_KEY'],
            'Default' => $row['COLUMN_DEFAULT'],
            'Extra'   => $row['EXTRA'],
        ];
    }

    return $columns;
}

function schema_foreign_keys(array $info, string $table): array
{
    $foreign_keys = [];

    foreach ($info as $row) {
        if (($row['TABLE_NAME'] ?? null) !== $table || ($row['CONSTRAINT_TYPE'] ?? null) !== 'FOREIGN KEY') {
            continue;
        }

        $foreign_keys[(string) $row['COLUMN_NAME']] = [
            'table' => (string) $row['REFERENCED_TABLE_NAME'],
            'column' => (string) $row['REFERENCED_COLUMN_NAME'],
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

function schema_options(PDO $pdo, string $table, string $value_column, array &$memo, array $columns): array
{
    $key = schema_cache_key($table, $value_column);

    return $memo['options'][$key] ??= schema_select_options($pdo, $table, $value_column, schema_label_columns($memo, $table, $value_column, $columns));
}

function schema_label_columns(array &$memo, string $table, string $value_column, array $columns): array
{
    $key = schema_cache_key($table, $value_column);

    return $memo['label_columns'][$key] ??= schema_guess_label_columns($columns, $value_column);
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

function schema_load_missing_ref_labels(PDO $pdo, string $table, string $value_column, array $values, array &$memo, array $columns): void
{
    assert_sql_identifier($table, __FUNCTION__ . ':invalid_table');
    assert_sql_identifier($value_column, __FUNCTION__ . ':invalid_column');

    $key = schema_cache_key($table, $value_column);
    $memo['ref_labels'][$key] ??= [];
    $missing = [];

    foreach (schema_ref_values($values) as $value) {
        if (!array_key_exists($value, $memo['ref_labels'][$key])) {
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
        schema_label_columns($memo, $table, $value_column, $columns)
    );

    foreach ($missing as $value) {
        $memo['ref_labels'][$key][$value] = $labels[$value] ?? null;
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
