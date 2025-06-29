<!-- Hero Section avec Carousel -->
<section class="hero" aria-label="Présentation principale">
    <img src="/static/assets/collegues-de-taille-moyenne-apprenant.jpg" class="hero__image hero__image--active"
        alt="Formation en gestion de copropriété" loading="eager">
    <img src="/static/assets/agent-immobilier-masculin-faisant-des-affaires-et-montrant-la-maison-a-un-couple-d-acheteurs-potentiels.jpg"
        class="hero__image" alt="Agent immobilier" loading="lazy">
    <img src="/static/assets/tenir-la-cle-a-la-main-a-l-exterieur.jpg" class="hero__image" alt="Clés de propriété"
        loading="lazy">

    <div class="hero__overlay" aria-hidden="true"></div>

    <div class="hero__content">
        <h1 class="hero__title">Bienvenue chez Copro Academy</h1>
        <h2 class="hero__subtitle">Votre partenaire en gestion de copropriétés</h2>
        <p class="hero__description">Formations professionnelles, actualités juridiques et accompagnement
            spécialisé pour les experts de l'immobilier</p>

        <div class="hero__actions">
            <a href="/formation" class="btn btn--primary btn--lg">Découvrir nos formations</a>
            <a href="/articles" class="btn btn--secondary btn--lg">Actualités & Événements</a>
        </div>
    </div>
</section>


<!-- Section Services -->
<section class="container mt-3xl" aria-labelledby="services-title">
    <h2 id="services-title" class="text-center mb-2xl">Nos Services</h2>

    <div class="grid grid-cols-auto gap-xl">
        <?php foreach ($service as $item) : ?>
            <article class="card">
                <img src="<?= $item['image'] ?? '/static/assets/hero.jpeg' ?>" alt="<?= $item['label'] ?? 'Service' ?>"
                    loading="lazy" class="card__image">
                <div class="card__body">
                    <h3 class="card__title"><?= $item['label'] ?? 'Service' ?></h3>
                    <p class="card__content"><?= $item['content'] ?? 'Description du service' ?></p>
                    <a href="<?= $item['link'] ?? 'contact.html' ?>" class="btn btn--primary">En savoir plus</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- Articles récents -->
<section class="container mt-3xl" aria-labelledby="recent-articles">
    <h2 id="recent-articles" class="text-center mb-2xl">Articles & Événements récents</h2>

    <div class="grid grid-cols-auto gap-xl">
        <?php foreach ($recent_articles as $article) : ?>
            <article class="card">
                <?php if ($article['featured'] ?? false) : ?>
                    <div class="badge badge--error" style="position: absolute; top: 1rem; right: 1rem; z-index: 1;">À la
                        une</div>
                <?php endif; ?>
                <img src="<?= $article['avatar'] ?: '/static/assets/hero.jpeg' ?>" alt="Nouvelle réglementation copropriété 2025" loading="lazy"
                    class="card__image">
                <div class="card__body">
                    <div class="article-meta">
                        <span class="article-category"><?= $article['category_label'] ?? '' ?></span>
                        <time class="article-date" datetime="<?= $article['enabled_at'] ?? '' ?>"><?= $article['enabled_at'] ?? '' ?></time>
                    </div>
                    <h3 class="card__title"><?= $article['label'] ?? 'Titre manquant' ?></h3>
                    <p class="card__content"><?= $article['summary'] ?? 'Titre manquant' ?></p>
                    <div class="flex flex-wrap gap-sm mb-lg">
                        <span class="tag">Charges</span>
                        <span class="tag">Législation</span>
                        <span class="tag">2025</span>
                    </div>
                    <a href="/article/<?= $article['slug'] ?>" class="btn btn--primary">Lire l'article complet</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- FAQ rapide -->
<section class="full-width bg-gray-50 py-3xl" aria-labelledby="faq-title">
    <div class="container">
        <h2 id="faq-title" class="text-center mb-2xl">Questions fréquentes</h2>

        <div style="max-width: 800px; margin: 0 auto;">
            <?php foreach ($faq as $item) : ?>
                <details class="faq-item">
                    <summary class="faq-summary"><?= $item['label'] ?? 'Question vide' ?></summary>
                    <div class="faq-content">
                        <p><?= $item['content'] ?? 'Reponse vide' ?></p>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="formation-benefits full-width-section" aria-labelledby="benefits-title">
    <div class="formation-benefits-content">
        <h2 id="benefits-title" class="section-title">Pourquoi choisir Copro Academy&nbsp;?</h2>

        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">🎓</div>
                <h3>Formations certifiées</h3>
                <p>Nos formations sont reconnues et donnent droit à des certificats de formation continue.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">⚖️</div>
                <h3>Expertise juridique</h3>
                <p>Nos formateurs sont des experts reconnus en droit immobilier et gestion de copropriétés.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🔄</div>
                <h3>Mise à jour constante</h3>
                <p>Nos contenus sont régulièrement actualisés selon les évolutions législatives.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🤝</div>
                <h3>Accompagnement personnalisé</h3>
                <p>Support continu et conseils adaptés à vos besoins spécifiques.</p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="newsletter">
    <div class="container">
        <h2 class="newsletter__title">Restez informé</h2>
        <p class="newsletter__description">Recevez nos dernières actualités et annonces d'événements directement
            dans votre boîte mail</p>

        <form class="newsletter__form" id="newsletterForm">
            <div class="newsletter__input-group">
                <input type="email" placeholder="Votre adresse email" required class="newsletter__input"
                    aria-label="Adresse email pour newsletter">
                <button type="submit" class="btn btn--primary">S'abonner</button>
            </div>
            <small class="newsletter__help">Nous respectons votre vie privée. Pas de spam, désinscription à tout
                moment.</small>
        </form>
    </div>
</section>
<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/layout.php', ($args ?? []) +  ['main' => $this_html, 'css' => '/asset/css/alter.css'])[1];
};
