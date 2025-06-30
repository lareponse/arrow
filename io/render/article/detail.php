<div class="page-detail">
    <!-- Breadcrumb -->
    <nav class="breadcrumb" aria-label="Fil d'Ariane">
        <ol>
            <li><a href="/">Accueil</a></li>
            <li><a href="/article">Articles</a></li>
            <li><span aria-current="page"><?= e($article, 'label') ?></span></li>
        </ol>
    </nav>

    <!-- En-tête d'article -->
    <header class="article-header">
        <div class="article-meta">
            <span class="article-category"><?= e($article, 'category_label') ?></span>
            <time datetime="2025-01-15"><?= e($article, 'enabled_at') ?></time>
            <span class="reading-time">📖 <?= e($article, 'reading_time') ?> min de lecture</span>
        </div>

        <h1><?= $article['label'] ?? '' ?></h1>

        <p class="article-summary">
            <?= e($article, 'summary') ?>
        </p>

        <div class="article-tags">
            <span class="tag">Charges</span>
            <span class="tag">Législation</span>
            <span class="tag">2025</span>
            <span class="tag">Copropriété</span>
        </div>

        <div class="article-actions">
            <button class="share-btn" onclick="shareArticle()" aria-label="Partager l'article">🔗 Partager</button>
            <button class="print-btn" onclick="window.print()" aria-label="Imprimer l'article">🖨️ Imprimer</button>
            <button class="bookmark-btn" onclick="bookmarkArticle()" aria-label="Ajouter aux favoris">🔖
                Sauvegarder</button>
        </div>
    </header>

    <!-- Image principale -->
    <figure class="article-hero-image">
        <img src="/static/assets/hero.webp" alt="Assemblée générale de copropriété discutant des nouvelles charges"
            width="800" height="400">
        <figcaption>Réunion d'information sur les nouvelles modalités de répartition des charges</figcaption>
    </figure>

    <!-- Contenu de l'article -->
    <article class="article-content">
        <div class="content-wrapper">
            <!-- Sommaire -->
            <aside class="table-of-contents">
                <h2>Sommaire</h2>
                <nav aria-label="Sommaire de l'article">
                    <ol>
                        <li><a href="#contexte">Contexte législatif</a></li>
                        <li><a href="#principales-modifications">Principales modifications</a></li>
                        <li><a href="#repartition-charges">Nouvelle répartition des charges</a></li>
                        <li><a href="#impact-pratique">Impact pratique</a></li>
                        <li><a href="#conseils">Conseils d'application</a></li>
                        <li><a href="#conclusion">Conclusion</a></li>
                    </ol>
                </nav>
            </aside>

            <!-- Corps de l'article -->
            <div class="article-body">
                <section id="contexte">
                    <h2>Contexte législatif</h2>
                    <p>
                        La réforme du droit de la copropriété en Belgique, entrée en vigueur le 1er janvier 2025,
                        apporte des modifications substantielles dans la gestion des charges communes. Cette
                        évolution
                        législative vise à moderniser le cadre juridique et à clarifier les responsabilités de
                        chaque acteur.
                    </p>

                    <div class="highlight-box">
                        <h3>📋 Points clés de la réforme</h3>
                        <ul>
                            <li>Simplification des procédures de répartition</li>
                            <li>Transparence accrue dans la gestion financière</li>
                            <li>Renforcement des droits des copropriétaires</li>
                            <li>Harmonisation avec les standards européens</li>
                        </ul>
                    </div>

                    <p>
                        Cette réforme s'inscrit dans une volonté de modernisation du secteur immobilier belge,
                        en tenant compte des évolutions technologiques et des attentes des citoyens en matière
                        de transparence et d'efficacité.
                    </p>
                </section>

                <section id="principales-modifications">
                    <h2>Principales modifications</h2>

                    <h3>Calcul automatisé des charges</h3>
                    <p>
                        La nouvelle réglementation introduit l'obligation d'utiliser des méthodes de calcul
                        standardisées pour la répartition des charges. Cette approche garantit une plus grande
                        équité et réduit les risques de contestation.
                    </p>

                    <div class="comparison-table">
                        <h4>Avant / Après la réforme</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th>Aspect</th>
                                    <th>Ancienne méthode</th>
                                    <th>Nouvelle méthode</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Calcul des charges</td>
                                    <td>Manuel, variable selon syndic</td>
                                    <td>Automatisé, méthode standardisée</td>
                                </tr>
                                <tr>
                                    <td>Transparence</td>
                                    <td>Information limitée</td>
                                    <td>Détail complet obligatoire</td>
                                </tr>
                                <tr>
                                    <td>Contestation</td>
                                    <td>Procédure complexe</td>
                                    <td>Mécanisme simplifié</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3>Fonds de réserve obligatoire</h3>
                    <p>
                        L'une des innovations majeures concerne l'instauration d'un fonds de réserve obligatoire
                        pour toutes les copropriétés de plus de 8 lots. Ce fonds doit représenter au minimum
                        5% du budget annuel de charges courantes.
                    </p>

                    <div class="info-box">
                        <h4>💡 Bon à savoir</h4>
                        <p>
                            Le fonds de réserve peut être constitué progressivement sur une période de 3 ans
                            maximum pour les copropriétés existantes.
                        </p>
                    </div>
                </section>

                <section id="repartition-charges">
                    <h2>Nouvelle répartition des charges</h2>

                    <p>
                        La réforme introduit une classification plus précise des différents types de charges
                        et établit des règles claires pour leur répartition entre copropriétaires.
                    </p>

                    <h3>Catégories de charges</h3>
                    <div class="charges-grid">
                        <div class="charge-category">
                            <h4>🏢 Charges générales</h4>
                            <p>Entretien parties communes, assurances, administration</p>
                            <span class="repartition">Répartition : Quote-part</span>
                        </div>

                        <div class="charge-category">
                            <h4>🔧 Charges spéciales</h4>
                            <p>Ascenseurs, chauffage collectif, espaces verts</p>
                            <span class="repartition">Répartition : Usage réel</span>
                        </div>

                        <div class="charge-category">
                            <h4>⚡ Charges individualisées</h4>
                            <p>Consommations personnelles, compteurs individuels</p>
                            <span class="repartition">Répartition : Consommation</span>
                        </div>
                    </div>

                    <h3>Modalités de calcul</h3>
                    <p>
                        Le nouveau système prévoit l'utilisation obligatoire de coefficients de répartition
                        basés sur des critères objectifs et mesurables. Cette approche élimine l'arbitraire
                        et garantit une répartition équitable.
                    </p>
                </section>

                <section id="impact-pratique">
                    <h2>Impact pratique pour les professionnels</h2>

                    <h3>Pour les syndics</h3>
                    <ul>
                        <li><strong>Formation obligatoire :</strong> Mise à niveau sur les nouvelles procédures</li>
                        <li><strong>Outils informatiques :</strong> Adaptation des logiciels de gestion</li>
                        <li><strong>Communication :</strong> Information renforcée aux copropriétaires</li>
                    </ul>

                    <h3>Pour les gestionnaires</h3>
                    <ul>
                        <li><strong>Reporting :</strong> Nouveaux formats de comptes-rendus</li>
                        <li><strong>Audit :</strong> Contrôles périodiques obligatoires</li>
                        <li><strong>Digitalisation :</strong> Dématérialisation des processus</li>
                    </ul>

                    <div class="warning-box">
                        <h4>⚠️ Attention</h4>
                        <p>
                            Les copropriétés ont jusqu'au 30 juin 2025 pour se conformer aux nouvelles
                            dispositions. Un accompagnement personnalisé est recommandé pour faciliter
                            cette transition.
                        </p>
                    </div>
                </section>

                <section id="conseils">
                    <h2>Conseils d'application</h2>

                    <h3>Plan d'action recommandé</h3>
                    <ol>
                        <li><strong>Audit des pratiques actuelles</strong> - Évaluation de l'existant</li>
                        <li><strong>Formation des équipes</strong> - Mise à niveau des compétences</li>
                        <li><strong>Mise à jour des outils</strong> - Adaptation logicielle</li>
                        <li><strong>Communication aux copropriétaires</strong> - Information et sensibilisation</li>
                        <li><strong>Test et validation</strong> - Phase pilote avant généralisation</li>
                    </ol>

                    <h3>Ressources utiles</h3>
                    <div class="resources">
                        <div class="resource-item">
                            <h4>📚 Guide officiel</h4>
                            <p>Documentation complète du ministère du Logement</p>
                            <a href="#" class="resource-link">Télécharger</a>
                        </div>

                        <div class="resource-item">
                            <h4>🎓 Formation Copro Academy</h4>
                            <p>Programme spécialisé sur la nouvelle réglementation</p>
                            <a href="/formation" class="resource-link">S'inscrire</a>
                        </div>

                        <div class="resource-item">
                            <h4>💬 Support technique</h4>
                            <p>Assistance personnalisée pour votre transition</p>
                            <a href="/contact" class="resource-link">Nous contacter</a>
                        </div>
                    </div>
                </section>

                <section id="conclusion">
                    <h2>Conclusion</h2>
                    <p>
                        La nouvelle réglementation sur les charges de copropriété marque une étape importante
                        dans la modernisation du secteur immobilier belge. Bien que cette transition demande
                        un effort d'adaptation, les bénéfices en termes de transparence, d'équité et d'efficacité
                        sont considérables.
                    </p>

                    <p>
                        Les professionnels qui anticipent ces changements et s'adaptent rapidement aux nouvelles
                        exigences bénéficieront d'un avantage concurrentiel significatif et renforceront la
                        confiance de leurs clients.
                    </p>

                    <div class="cta-box">
                        <h3>Besoin d'accompagnement ?</h3>
                        <p>
                            Copro Academy propose des formations spécialisées et un accompagnement personnalisé
                            pour vous aider dans cette transition.
                        </p>
                        <a href="/contact/conseil" class="cta">Demander un accompagnement</a>
                    </div>
                </section>
            </div>
        </div>
    </article>

    <!-- Articles similaires -->
    <section class="related-articles" aria-labelledby="related-title">
        <h2 id="related-title">Articles similaires</h2>
        <div class="related-grid">
            <?php foreach ($related_articles as $related): ?>
                <article class="related-card">
                    <img src="/static/assets/hero.webp" alt="Guide assemblée générale" width="200" height="120">
                    <div class="related-content">
                        <h3><?= e($related, 'label'); ?></h3>
                        <p><?= e($related, 'summary'); ?></p>
                        <a href="/article/detail/<?= e($related, 'slug'); ?>">Lire →</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>


<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
