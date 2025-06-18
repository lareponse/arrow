<?php

declare(strict_types=1);

require_once 'add/bad/db.php';
require_once 'add/bad/dad/qb.php';

/**
 * Schema-aware CRUD mapper for BADDAD
 * Expect success, let database handle constraints
 */

function map_schema(?string $table = null): array
{
    static $cache = [];

    if ($table && !isset($cache[$table])) {
        $cache[$table] = map_introspect($table);
    }

    return $table ? $cache[$table] : $cache;
}

function map_introspect(string $table): array
{
    $columns = dbq(db(), "DESCRIBE $table")->fetchAll();

    $schema = [
        'table' => $table,
        'primary' => [],
        'generated' => [],
        'fields' => []
    ];

    foreach ($columns as $col) {
        $field = $col['Field'];

        if ($col['Key'] === 'PRI') {
            $schema['primary'][] = $field;
        }

        // Any database-managed field is generated
        if (
            str_contains($col['Extra'], 'GENERATED')
            || str_contains($col['Extra'], 'auto_increment')
            || str_contains($col['Extra'], 'on update CURRENT_TIMESTAMP')
            || str_contains($col['Default'] ?? '', 'CURRENT_TIMESTAMP')
        ) {

            $schema['generated'][] = $field;
        } else {
            $schema['fields'][] = $field;
        }
    }

    return $schema;
}

function map_create(string $table, array $data): mixed
{
    $schema = map_schema($table);
    $data = array_intersect_key($data, array_flip($schema['fields']));

    [$sql, $binds] = qb_create($table, null, $data);
    dbq(db(), $sql, $binds);

    return count($schema['primary']) === 1 && $schema['primary'][0] === 'id'
        ? (int)db()->lastInsertId()
        : array_intersect_key($data, array_flip($schema['primary']));
}

function map_read(string $table, mixed $id, bool $include_revoked = false): array
{
    $schema = map_schema($table);

    $conditions = is_scalar($id)
        ? [$schema['primary'][0] => $id]
        : $id;

    if (!$include_revoked) {
        $conditions['revoked_at'] = null;
    }

    [$sql, $binds] = qb_read($table, $conditions);
    return dbq(db(), $sql, $binds)->fetch() ?: [];
}

function map_update(string $table, mixed $id, array $data): int
{
    $schema = map_schema($table);
    $allowed = array_diff($schema['fields'], $schema['primary']);
    $data = array_intersect_key($data, array_flip($allowed));

    $conditions = is_scalar($id)
        ? [$schema['primary'][0] => $id]
        : $id;

    [$sql, $binds] = qb_update($table, $data, $conditions);
    return dbq(db(), $sql, $binds)->rowCount();
}

function map_delete(string $table, mixed $id, bool $soft = true): int
{
    $schema = map_schema($table);
    $conditions = is_scalar($id)
        ? [$schema['primary'][0] => $id]
        : $id;

    if ($soft) {
        return map_update($table, $conditions, ['revoked_at' => date('Y-m-d H:i:s')]);
    }

    [$where_sql, $where_binds] = qb_where($conditions);
    return dbq(db(), "DELETE FROM $table $where_sql", $where_binds)->rowCount();
}

function map_list(string $table, array $conditions = [], array $options = []): array
{
    $options += ['limit' => null, 'offset' => 0, 'order' => 'created_at DESC', 'include_revoked' => false];

    if (!$options['include_revoked']) {
        $conditions['revoked_at'] = null;
    }

    [$where_sql, $where_binds] = qb_where($conditions);

    $sql = "SELECT * FROM $table $where_sql";
    if ($options['order']) $sql .= " ORDER BY {$options['order']}";
    if ($options['limit']) $sql .= " LIMIT {$options['limit']} OFFSET {$options['offset']}";

    return dbq(db(), $sql, $where_binds)->fetchAll();
}

function map_publish(string $table, mixed $id, ?string $when = null): int
{
    return map_update($table, $id, ['enabled_at' => $when ?: date('Y-m-d H:i:s')]);
}

function map_unpublish(string $table, mixed $id): int
{
    return map_update($table, $id, ['enabled_at' => null]);
}

function map_revoke(string $table, mixed $id): int
{
    return map_update($table, $id, ['revoked_at' => date('Y-m-d H:i:s')]);
}

function map_restore(string $table, mixed $id): int
{
    return map_update($table, $id, ['revoked_at' => null]);
}

function map_create_batch(string $table, array $rows): array
{
    $schema = map_schema($table);

    foreach ($rows as &$data) {
        $data = array_intersect_key($data, array_flip($schema['fields']));
    }

    [$sql, $binds] = qb_create($table, array_keys($rows[0]), ...$rows);
    dbq(db(), $sql, $binds);

    $first_id = (int)db()->lastInsertId();
    return range($first_id, $first_id + count($rows) - 1);
}

function map_with_taxonomy(string $table, array $taxonomy_fields = [], array $conditions = [], array $options = []): array
{
    $base_table = $table;
    $joins = [];
    $select_fields = ["$base_table.*"];

    foreach ($taxonomy_fields as $field => $alias) {
        $alias = is_numeric($field) ? $alias : $field;
        $field = is_numeric($field) ? $alias : $field;

        $table_alias = "t_$alias";
        $joins[] = "LEFT JOIN taxonomy $table_alias ON $base_table.$field = $table_alias.id";
        $select_fields[] = "$table_alias.label as {$alias}_label";
    }

    $options += ['include_revoked' => false];
    if (!$options['include_revoked']) {
        $conditions['revoked_at'] = null;
    }

    [$where_sql, $where_binds] = qb_where($conditions);

    $sql = "SELECT " . implode(', ', $select_fields) . " FROM $base_table " . implode(' ', $joins) . " $where_sql";

    if ($options['order'] ?? null) $sql .= " ORDER BY {$options['order']}";
    if ($options['limit'] ?? null) $sql .= " LIMIT {$options['limit']} OFFSET " . ($options['offset'] ?? 0);

    return dbq(db(), $sql, $where_binds)->fetchAll();
}
