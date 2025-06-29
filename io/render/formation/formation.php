<h1 class="text-center mt-xl mb-lg text-4xl font-bold">Nos Formations</h1>

<!-- Section Introduction -->
<section class="container mb-xl" aria-labelledby="intro-title">
    <div class="card p-xl text-center">
        <h2 id="intro-title" class="mb-md">Formation professionnelle en gestion de copropriétés</h2>
        <p class="mb-0">
            Copro Academy propose des formations certifiées et reconnues pour les professionnels de
            l'immobilier.
            Nos programmes sont conçus pour répondre aux exigences du secteur et aux évolutions législatives.
        </p>
    </div>
</section>

<!-- Liste des formations -->
<section class="container mb-xl" aria-labelledby="formations-title">
    <h2 id="formations-title" class="text-center mb-2xl">Catalogue de formations</h2>

    <!-- Filtres de formations -->
    <div class="filters mb-xl" role="group" aria-label="Filtres des formations">
        <button class="filter-btn active" data-type="all">Toutes les formations</button>
        <button class="filter-btn" data-type="beginner">Débutant</button>
        <button class="filter-btn" data-type="intermediate">Intermédiaire</button>
        <button class="filter-btn" data-type="advanced">Avancé</button>
    </div>

    <div class="grid grid-cols-auto gap-xl" id="formationsContainer">
        <?php foreach ($formation as $item): ?>
            <!-- Formation 1 -->
            <article class="card formation-card" data-type="beginner" data-level="Débutant">
                <img src="/static/assets/hero.jpeg" alt="Formation introduction à la gestion de copropriété" loading="lazy"
                    class="card__image">
                <div class="card__body">
                    <div class="badge badge--primary mb-md">Débutant</div>
                    <h3 class="card__title"><?= $item['label'] ?? 'Pas de titre' ?></h3>

                    <div class="bg-gray-50 p-md rounded mb-md">
                        <?php if (isset($item['duration_days']) && isset($item['duration_hours'])): ?>
                            <p class="text-sm mb-xs"><strong>Durée :</strong> <?= $item['duration_days'] ?> jours (<?= $item['duration_hours'] ?>h)</p>
                        <?php endif; ?>
                        <?php if (isset($item['start_date'])): ?>
                            <p class="text-sm mb-xs"><strong>Date :</strong> <?= $item['start_date'] ?></p>
                        <?php endif; ?>
                        <?php if (isset($item['price_ht'])): ?>
                            <p class="text-sm mb-0"><strong>Prix :</strong> <?= $item['price_ht'] ?>€ HT</p>
                        <?php endif; ?>
                    </div>

                    <p class="card__content"><?= $item['content'] ?? '' ?></p>

                    <div class="formation-objectives mb-lg">
                        <h4 class="text-primary mb-sm">Objectifs :</h4>
                        <?php $objectives = explode(';', $item['objectives'] ?? '') ?>
                        <ul class="text-sm">
                            <?php foreach ($objectives as $objective): ?>
                                <li><?= trim($objective) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <a href="/formation/detail/<?= $item['slug'] ?>" class="btn btn--primary w-full">Voir les détails</a>
                </div>
            </article>
        <?php endforeach; ?>
        <!-- Formation 1 -->
        <article class="card formation-card" data-type="beginner" data-level="Débutant">
            <img src="/static/assets/hero.jpeg" alt="Formation introduction à la gestion de copropriété" loading="lazy"
                class="card__image">
            <div class="card__body">
                <div class="badge badge--primary mb-md">Débutant</div>
                <h3 class="card__title">Introduction à la gestion de copropriété</h3>

                <div class="bg-gray-50 p-md rounded mb-md">
                    <p class="text-sm mb-xs"><strong>Durée :</strong> 2 jours (14h)</p>
                    <p class="text-sm mb-xs"><strong>Date :</strong> 15 septembre 2025</p>
                    <p class="text-sm mb-0"><strong>Prix :</strong> 450€ HT</p>
                </div>

                <p class="card__content">Formation de base pour comprendre les enjeux de la gestion collective
                    et acquérir les
                    fondamentaux juridiques et pratiques.</p>

                <div class="formation-objectives mb-lg">
                    <h4 class="text-primary mb-sm">Objectifs :</h4>
                    <ul class="text-sm">
                        <li>Maîtriser le cadre légal belge</li>
                        <li>Comprendre les rôles et responsabilités</li>
                        <li>Gérer les assemblées générales</li>
                    </ul>
                </div>

                <a href="formation.detail.html?id=1" class="btn btn--primary w-full">Voir les détails</a>
            </div>
        </article>

        <!-- Formation 2 -->
        <article class="card formation-card" data-type="intermediate" data-level="Intermédiaire">
            <img src="/static/assets/hero.jpeg" alt="Formation gestion financière de copropriété" loading="lazy"
                class="card__image">
            <div class="card__body">
                <div class="badge badge--warning mb-md">Intermédiaire</div>
                <h3 class="card__title">Gestion financière de copropriété</h3>

                <div class="bg-gray-50 p-md rounded mb-md">
                    <p class="text-sm mb-xs"><strong>Durée :</strong> 3 jours (21h)</p>
                    <p class="text-sm mb-xs"><strong>Date :</strong> 22 octobre 2025</p>
                    <p class="text-sm mb-0"><strong>Prix :</strong> 650€ HT</p>
                </div>

                <p class="card__content">Approfondissement des aspects financiers : budgets, comptes,
                    provisions, et gestion des
                    impayés.</p>

                <div class="formation-objectives mb-lg">
                    <h4 class="text-primary mb-sm">Objectifs :</h4>
                    <ul class="text-sm">
                        <li>Élaborer et suivre un budget</li>
                        <li>Gérer les provisions et fonds</li>
                        <li>Traiter les impayés efficacement</li>
                    </ul>
                </div>

                <a href="formation.detail.html?id=2" class="btn btn--primary w-full">Voir les détails</a>
            </div>
        </article>

        <!-- Formation 3 -->
        <article class="card formation-card" data-type="advanced" data-level="Avancé">
            <img src="/static/assets/hero.jpeg" alt="Formation contentieux et médiation" loading="lazy"
                class="card__image">
            <div class="card__body">
                <div class="badge badge--error mb-md">Avancé</div>
                <h3 class="card__title">Contentieux et médiation en copropriété</h3>

                <div class="bg-gray-50 p-md rounded mb-md">
                    <p class="text-sm mb-xs"><strong>Durée :</strong> 2 jours (14h)</p>
                    <p class="text-sm mb-xs"><strong>Date :</strong> 18 novembre 2025</p>
                    <p class="text-sm mb-0"><strong>Prix :</strong> 550€ HT</p>
                </div>

                <p class="card__content">Gestion des conflits, procédures contentieuses et techniques de
                    médiation adaptées aux
                    copropriétés.</p>

                <div class="formation-objectives mb-lg">
                    <h4 class="text-primary mb-sm">Objectifs :</h4>
                    <ul class="text-sm">
                        <li>Prévenir et gérer les conflits</li>
                        <li>Maîtriser les procédures judiciaires</li>
                        <li>Utiliser la médiation efficacement</li>
                    </ul>
                </div>

                <a href="formation.detail.html?id=3" class="btn btn--primary w-full">Voir les détails</a>
            </div>
        </article>

        <!-- Formation 4 -->
        <article class="card formation-card" data-type="beginner" data-level="Débutant">
            <img src="/static/assets/hero.jpeg" alt="Formation réglementation énergétique" loading="lazy"
                class="card__image">
            <div class="card__body">
                <div class="badge badge--primary mb-md">Débutant</div>
                <h3 class="card__title">Réglementation énergétique et travaux</h3>

                <div class="bg-gray-50 p-md rounded mb-md">
                    <p class="text-sm mb-xs"><strong>Durée :</strong> 1 jour (7h)</p>
                    <p class="text-sm mb-xs"><strong>Date :</strong> 12 décembre 2025</p>
                    <p class="text-sm mb-0"><strong>Prix :</strong> 320€ HT</p>
                </div>

                <p class="card__content">Comprendre les obligations énergétiques et la gestion des travaux
                    d'amélioration dans les
                    copropriétés.</p>

                <div class="formation-objectives mb-lg">
                    <h4 class="text-primary mb-sm">Objectifs :</h4>
                    <ul class="text-sm">
                        <li>Connaître la réglementation PEB</li>
                        <li>Organiser les travaux énergétiques</li>
                        <li>Optimiser les aides et subventions</li>
                    </ul>
                </div>

                <a href="formation.detail.html?id=4" class="btn btn--primary w-full">Voir les détails</a>
            </div>
        </article>
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


<!-- Section CTA -->
<section class="newsletter">
    <div class="container text-center">
        <h2 class="newsletter__title">Prêt à vous former ?</h2>
        <p class="newsletter__description">Rejoignez nos formations et développez votre expertise en gestion de
            copropriétés</p>
        <a href="contact.html?sujet=formation" class="btn btn--primary btn--lg">Demander des informations</a>
    </div>
</section>


<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
