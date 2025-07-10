<?php
// io/route/admin/faq/list.php
return function ($args) {
    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = 20;
    $offset = ($page - 1) * $limit;

    $search = $_GET['search'] ?? '';
    $where = $params = [];

    if ($search) {
        $where[] = "(label LIKE ? OR content LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $faqs = dbq(db(), "
        SELECT id, slug, label, LEFT(content, 100) as preview, created_at
        FROM faq 
        $where_clause
        ORDER BY created_at DESC 
        LIMIT $limit OFFSET $offset
    ", $params)->fetchAll();

    $total = dbq(db(), "
        SELECT COUNT(*) 
        FROM faq 
        $where_clause
    ", $params)->fetchColumn();

    $total_pages = ceil($total / $limit);

    return [
        'title' => 'FAQ - Questions fréquentes',
        'faqs' => $faqs,
        'search' => $search,
        'pagination' => compact('page', 'total_pages'),
    ];
};
