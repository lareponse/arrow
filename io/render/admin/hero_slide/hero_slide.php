<header class="page-header">
    <h1>Hero Slides - Page d'accueil</h1>
</header>

<div class="hero-slide-gallery">
    <?php foreach ($slides as $slide) : ?>
        <div class="hero-slide">
            <img src="<?= str_replace($_SERVER['DOCUMENT_ROOT'], '', $slide)  ?>" alt="Hero Slide Image" class="hero-slide__image">
            <div class="hero-slide__actions">
                <a href="/admin/hero_slide/delete?file=<?= urlencode(basename($slide)) ?>" class="btn btn--danger">Supprimer</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="panel">
    <p><strong>Astuce :</strong> Les slides sont affichés sur la page d'accueil par ordre croissant du champ "Ordre".</p>
</div>


<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
