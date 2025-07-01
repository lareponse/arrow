<?php

declare(strict_types=1);

require_once 'add/bad/db.php';
require_once 'add/bad/dad/qb.php';

function map_list(string $table, array $conditions = [], array $options = []): array
{
    $options += ['limit' => null, 'offset' => 0, 'order' => 'created_at DESC'];

    [$where_sql, $where_binds] = qb_where($conditions);

    $sql = "SELECT * FROM $table $where_sql";
    if ($options['order']) $sql .= " ORDER BY {$options['order']}";
    if ($options['limit']) $sql .= " LIMIT {$options['limit']} OFFSET {$options['offset']}";

    return dbq(db(), ($sql), $where_binds)->fetchAll();
}

