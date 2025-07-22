<?php
require_once 'app/mapper/taxonomy.php';

return function ($args = null) {

    $db = db();
    if (!empty($_POST)) {
        $res = (require_once __DIR__.'/contact_post.php')();
    }
    
    // Initialize variables
    $data = ($_POST ?? []) + [
        'nom'        => '',
        'email'      => '',
        'telephone'  => '',
        'entreprise' => '',
        'sujet'      => '',
        'content'    => '',
        'consent'    => 0
    ];

    $data = ['title' => 'Contactez-nous', 'description' => 'Contactez-nous pour toute question sur nos formations, événements ou services. Notre équipe est là pour vous aider.'];

    $sql = "SELECT * FROM `faq` ORDER BY `sort_order`;";
    ($_ = $db->query($sql)) && ($_ = $_->fetchAll(PDO::FETCH_ASSOC))      && $data['faq'] = $_;

    $data['subjects'] = tag_by_parent('contact_demande-sujet');
    if(isset($_GET['sujet'])) {
        $data['subject'] = $_GET['sujet'];
    }
    return $data;
};