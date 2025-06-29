<div class="container">
    <h1 class="text-center mt-xl mb-lg">Articles & Événements</h1>

    <!-- Section intro -->
    <section class="card p-xl text-center mb-xl">
        <h2 class="mb-md">Restez informé des actualités</h2>
        <p class="mb-0">Suivez les dernières évolutions législatives, participez à nos événements et
            enrichissez vos connaissances grâce à notre veille juridique spécialisée.</p>
    </section>
</div>

<!-- Filtres et options d'affichage -->
<div class="filters-container">
    <div class="filters" role="group" aria-label="Filtres de contenu">
        <!-- Progressive enhancement : liens par défaut, transformés en boutons par JS -->
        <a href="#" class="filter-btn active" data-type="all">Tout</a>
        <a href="#articles" class="filter-btn" data-type="article">Articles</a>
        <a href="#events" class="filter-btn" data-type="event">Événements</a>
        <a href="#webinars" class="filter-btn" data-type="webinar">Webinaires</a>
    </div>

    <!-- Controls cachés par défaut, montrés par JS -->
    <div class="view-controls" id="viewControls" style="display: none;">
        <button id="gridView" class="view-btn active" title="Vue grille" aria-label="Affichage en grille">
            <span>⊞</span>
        </button>
        <button id="listView" class="view-btn" title="Vue liste" aria-label="Affichage en liste">
            <span>☰</span>
        </button>
    </div>
</div>

<!-- Conteneur des articles -->
<section class="articles-section" aria-labelledby="articles-title">
    <h2 id="articles-title" class="sr-only">Liste des articles et événements</h2>

    <div class="grid-container" id="articlesContainer">
        <?php foreach($articles_events as $item): ?>
            <article class="card <?= $item['type'] ?>-card" data-type="<?= $item['type'] ?>" data-date="<?= $item['date'] ?>">
                <div class="article-badge <?= $item['badge'] ?>-badge"><?= $item['badge_label'] ?></div>
                <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>" loading="lazy">
                <div class="card-content">
                    <div class="article-meta">
                        <span class="article-category"><?= $item['category'] ?></span>
                        <time datetime="<?= $item['date'] ?>"><?= date('d F Y', strtotime($item['date'])) ?></time>
                    </div>
                    <h3><?= $item['title'] ?></h3>
                    <p><?= $item['summary'] ?></p>
                    <?php if (!empty($item['tags'])): ?>
                        <div class="article-tags">
                            <?php foreach ($item['tags'] as $tag): ?>
                                <span class="tag"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <a href="<?= $item['link'] ?>" class="cta">Lire l'article</a>
                </div>
            </article>
        <?php endforeach; ?>
        <!-- Article 1 - À la une -->
        <article class="card card-wide featured" data-type="article" data-date="2025-01-15">
            <div class="article-badge featured-badge">À la une</div>
            <img src="/static/assets/hero.jpeg" alt="Nouvelle réglementation copropriété 2025" loading="lazy">
            <div class="card-content">
                <div class="article-meta">
                    <span class="article-category">Juridique</span>
                    <time datetime="2025-01-15">15 janvier 2025</time>
                </div>
                <h3>Nouvelle réglementation sur les charges de copropriété</h3>
                <p>Découvrez les changements majeurs introduits par la nouvelle législation belge concernant
                    la
                    répartition et la gestion des charges en copropriété.</p>
                <div class="article-tags">
                    <span class="tag">Charges</span>
                    <span class="tag">Législation</span>
                    <span class="tag">2025</span>
                </div>
                <a href="article.detail.html?id=1" class="cta">Lire l'article complet</a>
            </div>
        </article>

        <!-- Événement 1 -->
        <article class="card card-tall" data-type="event" data-date="2025-02-20">
            <div class="article-badge event-badge">Événement</div>
            <img src="/static/assets/hero.jpeg" alt="Webinaire gestion énergétique copropriété" loading="lazy">
            <div class="card-content">
                <div class="article-meta">
                    <span class="article-category event">Webinaire</span>
                    <time datetime="2025-02-20">20 février 2025 - 14h00</time>
                </div>
                <h3>Webinaire : Gestion énergétique en copropriété</h3>
                <p>Rejoignez-nous pour un webinaire spécialisé sur l'optimisation énergétique des bâtiments.
                </p>
                <div class="event-details">
                    <p><strong>Durée :</strong> 2h</p>
                    <p><strong>Places :</strong> 50 participants</p>
                    <p><strong>Prix :</strong> Gratuit</p>
                </div>
                <a href="contact.html?sujet=evenement" class="cta">S'inscrire</a>
            </div>
        </article>

        <!-- Article 2 -->
        <article class="card card-small" data-type="article" data-date="2025-01-10">
            <img src="/static/assets/hero.jpeg" alt="Guide assemblée générale copropriété" loading="lazy">
            <div class="card-content">
                <div class="article-meta">
                    <span class="article-category">Guide pratique</span>
                    <time datetime="2025-01-10">10 janvier 2025</time>
                </div>
                <h3>Guide pratique : Organiser une assemblée générale efficace</h3>
                <p>Méthodologie complète pour préparer et animer une assemblée générale de copropriété
                    réussie.
                </p>
                <div class="article-tags">
                    <span class="tag">AG</span>
                    <span class="tag">Pratique</span>
                </div>
                <a href="article.detail.html?id=2" class="cta">Lire l'article</a>
            </div>
        </article>

        <!-- Événement 2 -->
        <article class="card card-medium" data-type="event" data-date="2025-03-15">
            <div class="article-badge event-badge">Conférence</div>
            <img src="/static/assets/hero.jpeg" alt="Conférence tendances immobilier 2025" loading="lazy">
            <div class="card-content">
                <div class="article-meta">
                    <span class="article-category event">Conférence</span>
                    <time datetime="2025-03-15">15 mars 2025 - 9h00</time>
                </div>
                <h3>Conférence : Tendances de l'immobilier belge</h3>
                <p>Analyse des évolutions du marché immobilier et impact sur la gestion de copropriétés.</p>
                <div class="event-details">
                    <p><strong>Lieu :</strong> Bruxelles</p>
                    <p><strong>Prix :</strong> 75€</p>
                </div>
                <a href="contact.html?sujet=evenement" class="cta">Réserver</a>
            </div>
        </article>

        <!-- Article 3 -->
        <article class="card card-small" data-type="article" data-date="2025-01-05">
            <img src="/static/assets/hero.jpeg" alt="Digitalisation gestion copropriété" loading="lazy" width="600"
                height="200">
            <div class="card-content">
                <div class="article-meta">
                    <span class="article-category">Innovation</span>
                    <time datetime="2025-01-05">5 janvier 2025</time>
                </div>
                <h3>La digitalisation au service de la gestion de copropriété</h3>
                <p>Comment les outils numériques transforment la gestion de copropriétés.</p>
                <div class="article-tags">
                    <span class="tag">Digital</span>
                    <span class="tag">Innovation</span>
                </div>
                <a href="article.detail.html?id=3" class="cta">Découvrir</a>
            </div>
        </article>

        <!-- Webinaire -->
        <article class="card card-medium" data-type="webinar" data-date="2025-02-28">
            <div class="article-badge webinar-badge">Webinaire</div>
            <img src="/static/assets/hero.jpeg" alt="Webinaire résolution conflits copropriété" loading="lazy"
                width="300" height="200">
            <div class="card-content">
                <div class="article-meta">
                    <span class="article-category webinar">Webinaire</span>
                    <time datetime="2025-02-28">28 février 2025 - 10h00</time>
                </div>
                <h3>Résolution de conflits en copropriété</h3>
                <p>Techniques de médiation et gestion des situations conflictuelles entre copropriétaires.
                </p>
                <div class="event-details">
                    <p><strong>Intervenant :</strong> Me. Sophie Dubois</p>
                    <p><strong>Durée :</strong> 1h30</p>
                </div>
                <a href="contact.html?sujet=evenement" class="cta">Participer</a>
            </div>
        </article>

        <!-- Article 4 -->
        <article class="card card-tall" data-type="article" data-date="2023-12-20">
            <img src="/static/assets/hero.jpeg" alt="Responsabilité syndic copropriété" loading="lazy">
            <div class="card-content">
                <div class="article-meta">
                    <span class="article-category">Responsabilité</span>
                    <time datetime="2023-12-20">20 décembre 2023</time>
                </div>
                <h3>Responsabilité du syndic : cadre légal et bonnes pratiques</h3>
                <p>Analyse complète des responsabilités civiles et pénales du syndic de copropriété.</p>
                <div class="article-summary">
                    <h4>Points clés :</h4>
                    <ul>
                        <li>Obligations légales du syndic</li>
                        <li>Assurance responsabilité civile</li>
                        <li>Cas de jurisprudence récents</li>
                    </ul>
                </div>
                <div class="article-tags">
                    <span class="tag">Syndic</span>
                    <span class="tag">Responsabilité</span>
                </div>
                <a href="article.detail.html?id=4" class="cta">Lire l'analyse</a>
            </div>
        </article>

        <!-- Article 5 -->
        <article class="card card-small" data-type="article" data-date="2023-12-15">
            <img src="/static/assets/hero.jpeg" alt="Comptabilité copropriété normes 2025" loading="lazy">
            <div class="card-content">
                <div class="article-meta">
                    <span class="article-category">Comptabilité</span>
                    <time datetime="2023-12-15">15 décembre 2023</time>
                </div>
                <h3>Nouvelles normes comptables pour les copropriétés</h3>
                <p>Mise à jour des règles comptables applicables aux copropriétés.</p>
                <div class="article-tags">
                    <span class="tag">Comptabilité</span>
                    <span class="tag">Normes</span>
                </div>
                <a href="article.detail.html?id=5" class="cta">En savoir plus</a>
            </div>
        </article>
    </div>
</section>

<!-- Bouton charger plus -->
<button class="load-more" id="loadMore" aria-label="Charger plus d'articles">
    <span class="load-text">Charger plus d'articles</span>
    <span class="load-loading" style="display: none;">Chargement...</span>
</button>

<!-- Newsletter -->
<section class="newsletter">
    <div class="container">
        <h2 class="newsletter__title">Restez informé</h2>
        <p class="newsletter__description">Recevez nos dernières actualités et annonces d'événements
            directement
            dans votre boîte mail</p>

        <form class="newsletter__form" id="newsletterForm">
            <div class="newsletter__input-group">
                <input type="email" placeholder="Votre adresse email" required class="newsletter__input"
                    aria-label="Adresse email pour newsletter">
                <button type="submit" class="btn btn--primary">S'abonner</button>
            </div>
            <small class="newsletter__help">Nous respectons votre vie privée. Pas de spam, désinscription à
                tout
                moment.</small>
        </form>
    </div>
</section>
<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/layout.php', ($args ?? []) +  ['main' => $this_html, 'css' => '/asset/css/alter.css'])[1];
};
