    <h1>Articles & Événements</h1>

    <!-- Section intro -->
    <section>
        <div class="card p-xl text-center mb-xl">
            <h2 class="mb-md">Restez informé des actualités</h2>
            <p class="mb-0">Suivez les dernières évolutions législatives, participez à nos événements et
                enrichissez vos connaissances grâce à notre veille juridique spécialisée.</p>
        </div>
    </section>

    <!-- Filtres et options d'affichage -->
    <div class="filters-container">
        <div class="filters" role="group" aria-label="Filtres de contenu">
            <!-- Progressive enhancement : liens par défaut, transformés en boutons par JS -->
            <a href="#" class="filter-btn active" data-type="all">Tout</a>
            <a href="#articles" class="filter-btn" data-type="article">Articles</a>
            <a href="#events" class="filter-btn" data-type="event">Événements</a>
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

        <div class="masonry" id="articlesContainer">
            <!-- <div class="grid-container" id="articlesContainer"> -->
            <?php foreach ($articles_events as $i => $item): ?>
                <?php
                // vd($item); 
                ?>
                <article class="card <?= ($i === 0) ? 'card-wide featured' : '' ?>" data-type="<?= e($item, 'type') ?>" data-date="2025-01-15">
                    <?php if ($item['featured']): ?>
                        <div class="article-badge featured-badge">À la une</div>
                    <?php elseif ($item['type'] === 'event'): ?>
                        <div class="article-badge event-badge"><?= htmlspecialchars($item['type']) ?></div>
                    <?php elseif ($item['type'] === 'webinar'): ?>
                        <div class="article-badge webinar-badge">Webinaire</div>
                    <?php endif; ?>

                    <img src="<?= $item['avatar'] ?? '/static/assets/hero.jpeg' ?>" alt="<?= e($item, 'title') ?>" loading="lazy">
                    <div class="card-content">
                        <div class="article-meta">
                            <span class="article-category"><?= e($item, 'category_label') ?></span>
                            <time datetime="<?= e($item, 'date') ?>"><?= e($item, 'date') ?></time>
                        </div>
                        <h3><?= e($item, 'label') ?></h3>
                        <p><?= e($item, 'content') ?></p>

                        <?php if ($item['type'] === 'event' || $item['type'] === 'webinar'): ?>
                            <div class="event-details">
                                <?php if (isset($item['event_date'])): ?>
                                    <p><strong>Date :</strong> <?= e($item, 'event_date') ?></p>
                                <?php endif; ?>
                                <?php if (isset($item['duration_minutes'])): ?>
                                    <p><strong>Durée :</strong> <?= e($item, 'duration_minutes') ?> minutes</p>
                                <?php endif; ?>
                                <?php if (isset($item['places_max'])): ?>
                                    <p><strong>Places :</strong> <?= e($item, 'places_max') ?></p>
                                <?php endif; ?>
                                <?php if (isset($item['price_ht'])): ?>
                                    <p><strong>Prix :</strong> <?= e($item, 'price_ht') ?> €</p>
                                <?php endif; ?>
                            </div>
                            <a href="/article/detail/<?= e($item, 'slug'); ?>" class="cta">Voir l'evenement complet</a>
                        <?php else: ?>
                            <div class="article-tags">
                                <span class="tag">Charges</span>
                                <span class="tag">Législation</span>
                                <span class="tag">2025</span>
                            </div>
                            <a href="/article/detail/<?= e($item, 'slug'); ?>" class="cta">Lire l'article complet</a>

                        <?php endif; ?>

                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Bouton charger plus -->
    <button class="load-more" id="loadMore" aria-label="Charger plus d'articles">
        <span class="load-text">Charger plus d'articles</span>
        <span class="load-loading" style="display: none;">Chargement...</span>
    </button>

    <!-- Newsletter -->
    <section class="newsletter wide">
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
