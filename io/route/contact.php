<?php
require_once 'app/mapper/taxonomy.php';

return function ($args = null) {

    $db = db();
    // Initialize variables
    $data = [
        'nom'        => '',
        'email'      => '',
        'telephone'  => '',
        'entreprise' => '',
        'sujet'      => '',
        'message'    => '',
        'consent'    => ''
    ];

    $data = ['title' => 'Contactez-nous', 'description' => 'Contactez-nous pour toute question sur nos formations, événements ou services. Notre équipe est là pour vous aider.'];

    $sql = "SELECT slug, label FROM `coproacademy`;";
    ($_ = dbq($db, $sql)) && ($_ = $_->fetchAll(PDO::FETCH_KEY_PAIR))   && $data['coproacademy'] = $_;

    $sql = "SELECT * FROM `faq` ORDER BY `sort_order`;";
    ($_ = dbq($db, $sql)) && ($_ = $_->fetchAll(PDO::FETCH_ASSOC))      && $data['faq'] = $_;

    $data['subjects'] = tag_by_parent('contact_demande-sujet');

    return $data;
};