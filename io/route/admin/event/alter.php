<?php
// io/route/event/alter.php

require_once 'app/mapper/taxonomy.php';
require_once 'add/bad/dad/db_row.php';
require_once 'app/upload.php';


return function ($args) {
    $slug = $args[0] ?? null;
    $event = row(db(), 'event');

    if ($slug) {
        $event(ROW_LOAD, ['slug' => $slug]);
        if (!$event(ROW_GET)) {
            http_out(301, 'Event not found', ['Location' => "/admin/event"]);
        }
    }

    // Handle POST submission
    if (!empty($_POST)) {

        $clean = $_POST;
        $clean['category_id']       = tag_id_by_slug($_POST['category_slug'], 'evenement-categorie') ?: null;
        $clean['duration_minutes']  = (int)($_POST['duration_minutes'] ?? 0) ?: null;
        $clean['price_ht']          = ($_POST['price_ht'] ?? '') !== '' ? (float)$_POST['price_ht'] : null;
        $clean['places_max']        = ($_POST['places_max'] ?? '') !== '' ? (int)$_POST['places_max'] : null;
        $clean['online']            = (int)!empty($_POST['online']);
        $clean['speaker']           = trim($_POST['speaker'] ?? '') ?: null;
        $clean['location']          = trim($_POST['location'] ?? '') ?: null;

        if (!empty($_POST['published']) && empty($event(ROW_GET, ['enabled_at'])))
            $clean['enabled_at'] = date('Y-m-d H:i:s');

        if (!empty($_FILES))
            foreach ($_FILES as $name => $file)
                $clean[$name] = upload($file, $_SERVER['DOCUMENT_ROOT'] . '/asset/image/event/' . $name);

        $event(ROW_FIELDS);
        $event(ROW_SET, $clean);
        $event(ROW_SAVE);
        $event(ROW_LOAD);
        http_out(302, '', ['Location' => "/admin/event/alter/" . $event(ROW_GET, ['slug'])]);
    }

    $event_data = $event(ROW_GET);
    return [
        'title' => $slug ? "Modifier l'événement - {$event_data['label']}" : 'Nouvel événement',
        'event' => $event_data,
        'categories' => tag_by_parent('evenement-categorie'),
    ];
};

