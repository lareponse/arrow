<?php
require_once 'add/arrow/arrow.php';
require_once 'app/mapper/taxonomy.php';
return function () {

    empty($_POST) && throw new BadMethodCallException('This route only handles POST requests', 400);

    $tracking = [
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        'referer' => $_SERVER['HTTP_REFERER'] ?? null,
        'language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? null,
        'session_id' => session_id() ?: null
    ];

    $clean = [
        'subject_id' => (int)tag_id_by_slug($_POST['sujet'], 'contact_demande-sujet'),
        'status_id' => (int)tag_id_by_slug('statut-en-attente', 'contact_demande-statut'),
        'consented_at' => isset($_POST['consent']) ? date('Y-m-d H:i:s') : null,
    ];

    $contact = row(db(), 'contact_request');
    $contact(ROW_SET | ROW_SCHEMA);
    $contact(ROW_SET | ROW_SAVE, $_POST + $clean + $tracking);
    if($contact(ROW_GET|ROW_ERROR) === null)
        header('Location: /contact/?message=sucess');
    else {
        $message = $contact(ROW_GET|ROW_ERROR);
        header('Location: /contact/?message='.urlencode($message));
    }
    exit;
};
