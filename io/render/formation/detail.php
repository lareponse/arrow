<main class="formation-detail" id="main-content">
    <!-- Breadcrumb -->
    <nav class="breadcrumb" aria-label="Fil d'Ariane">
        <ol>
            <li><a href="index.html">Accueil</a></li>
            <li><a href="formation.html">Formations</a></li>
            <li><span aria-current="page">Introduction à la gestion de copropriété</span></li>
        </ol>
    </nav>

    <!-- En-tête de formation -->
    <header class="formation-header">
        <div class="formation-badge">Débutant</div>
        <h1><?= $training['label'] ?? 'Titre de formation'; ?></h1>
        <p class="formation-subtitle"><?= $training['subtitle'] ?? 'Titre de formation'; ?></p>

        <div class="formation-key-info">
            <div class="info-item">
                <span class="icon">📅</span>
                <div>
                    <strong>Date</strong>
                    <p><?= $training['start_date'] ?></p>
                </div>
            </div>
            <div class="info-item">
                <span class="icon">⏱️</span>
                <div>
                    <strong>Durée</strong>
                    <p><?= $training['duration_days'] ?> jours (<?= $training['duration_hours'] ?>h)</p>
                </div>
            </div>
            <div class="info-item">
                <span class="icon">💰</span>
                <div>
                    <strong>Prix</strong>
                    <p><?= $training['price_ht'] ?>€ HT</p>
                </div>
            </div>
            <div class="info-item">
                <span class="icon">👥</span>
                <div>
                    <strong>Places</strong>
                    <p><?= $training['places_max'] ?> participants max</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Image principale -->
    <figure class="formation-hero-image">
        <img src="/static/assets/hero.jpeg" alt="Formation en gestion de copropriété" width="1200" height="400">
        <figcaption>Formation pratique en gestion de copropriété</figcaption>
    </figure>

    <!-- Contenu de la formation -->
    <div class="formation-content">
        <div class="content-main">
            <!-- Description -->
            <section class="formation-description">
                <h2>Description de la formation</h2>
                <?php
                $content = explode(PHP_EOL, $training['content'] ?? '');
                foreach ($content as $paragraph) {
                    if (trim($paragraph)) {
                        echo '<p>' . htmlspecialchars($paragraph) . '</p>';
                    }
                }
                ?>
            </section>

            <!-- Objectifs -->
            <section class="formation-objectives">
                <h2>Objectifs pédagogiques</h2>
                <div class="objectives-grid">
                    <div class="objective-item">
                        <span class="objective-icon">🎯</span>
                        <h3>Maîtriser le cadre légal</h3>
                        <p>Comprendre la législation belge en matière de copropriété et ses évolutions récentes</p>
                    </div>
                    <div class="objective-item">
                        <span class="objective-icon">⚖️</span>
                        <h3>Identifier les responsabilités</h3>
                        <p>Connaître les rôles et obligations de chaque acteur (syndic, conseil de copropriété,
                            copropriétaires)</p>
                    </div>
                    <div class="objective-item">
                        <span class="objective-icon">📋</span>
                        <h3>Organiser les assemblées</h3>
                        <p>Maîtriser la préparation, l'animation et le suivi des assemblées générales</p>
                    </div>
                    <div class="objective-item">
                        <span class="objective-icon">💼</span>
                        <h3>Gérer au quotidien</h3>
                        <p>Acquérir les outils pratiques pour une gestion efficace des parties communes</p>
                    </div>
                </div>
            </section>

            <!-- Programme détaillé -->
            <section class="formation-program">
                <h2>Programme détaillé</h2>
                <?php foreach ($training_sessions_by_day as $dayNum => $items): ?>
                    <div class="program-day">
                        <h3>Jour <?= (int)$dayNum ?> : Fondamentaux juridiques et organisationnels</h3>
                        <div class="program-timeline">
                            <?php foreach ($items as $it): ?>
                                <div class="timeline-item">
                                    <span class="time"><?= date('G\hi', strtotime($it['time_start'])) ?> - <?= date('G\hi', strtotime($it['time_end'])) ?></span>
                                    <div class="content">
                                        <h4><?= htmlspecialchars($it['label'] ?? '') ?></h4>
                                        <ul>
                                            <li>Évolution de la législation belge</li>
                                            <li>Code civil et dispositions spécifiques</li>
                                            <li>Jurisprudence récente</li>
                                        </ul>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>

                    <div class="program-day">
                        <h3>Jour 2 : Gestion pratique et assemblées</h3>
                        <div class="program-timeline">
                            <div class="timeline-item">
                                <span class="time">9h00 - 10h30</span>
                                <div class="content">
                                    <h4>Gestion financière</h4>
                                    <ul>
                                        <li>Budget prévisionnel</li>
                                        <li>Répartition des charges</li>
                                        <li>Fonds de réserve</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <span class="time">11h00 - 12h30</span>
                                <div class="content">
                                    <h4>Assemblées générales</h4>
                                    <ul>
                                        <li>Préparation et convocation</li>
                                        <li>Animation et prise de décisions</li>
                                        <li>Procès-verbal et suivi</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <span class="time">14h00 - 15h30</span>
                                <div class="content">
                                    <h4>Gestion des travaux</h4>
                                    <ul>
                                        <li>Entretien et maintenance</li>
                                        <li>Marchés publics et devis</li>
                                        <li>Suivi des interventions</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <span class="time">16h00 - 17h00</span>
                                <div class="content">
                                    <h4>Synthèse et évaluation</h4>
                                    <ul>
                                        <li>Questions-réponses</li>
                                        <li>Remise des attestations</li>
                                        <li>Ressources complémentaires</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>

            <!-- Public cible 
            <section class="formation-audience">
                <h2>Public cible</h2>
                <div class="audience-grid">
                    <div class="audience-item">
                        <span class="audience-icon">🏢</span>
                        <h3>Syndics débutants</h3>
                        <p>Nouveaux professionnels souhaitant maîtriser les fondamentaux</p>
                    </div>
                    <div class="audience-item">
                        <span class="audience-icon">👔</span>
                        <h3>Gestionnaires immobiliers</h3>
                        <p>Professionnels élargissant leur champ de compétences</p>
                    </div>
                    <div class="audience-item">
                        <span class="audience-icon">⚖️</span>
                        <h3>Juristes</h3>
                        <p>Avocats et notaires spécialisés en droit immobilier</p>
                    </div>
                    <div class="audience-item">
                        <span class="audience-icon">🎓</span>
                        <h3>Étudiants</h3>
                        <p>Formation initiale ou continue dans l'immobilier</p>
                    </div>
                </div>
            </section>
-->
            <!-- Prérequis 
            <section class="formation-prerequisites">
                <h2>Prérequis</h2>
                <div class="prerequisites-content">
                    <div class="prerequisite-item">
                        <span class="icon">✅</span>
                        <p><strong>Aucun prérequis technique</strong> - Formation accessible à tous</p>
                    </div>
                    <div class="prerequisite-item">
                        <span class="icon">📚</span>
                        <p><strong>Connaissances de base</strong> en droit ou immobilier recommandées</p>
                    </div>
                    <div class="prerequisite-item">
                        <span class="icon">💻</span>
                        <p><strong>Matériel fourni</strong> - Support de cours et documentation</p>
                    </div>
                </div>
            </section>
            -->
        </div>

        <!-- Sidebar -->
        <aside class="formation-sidebar">
            <!-- Inscription -->
            <div class="inscription-card">
                <h3>Inscription</h3>
                <div class="price-info">
                    <span class="price"><?= $training['price_ht'] ?>€</span>
                    <span class="price-detail">HT (TVA 21%)</span>
                </div>
                <div class="total-price">
                    <strong>Total TTC : 544,50€</strong>
                </div>
                <a href="contact.html?sujet=formation" class="cta">S'inscrire maintenant</a>

                <div class="inscription-info">
                    <h4>Informations pratiques</h4>
                    <ul>
                        <li>📍 <strong>Lieu :</strong> Bruxelles (Centre)</li>
                        <li>🕘 <strong>Horaires :</strong> 9h00 - 17h00</li>
                        <li>☕ <strong>Pauses :</strong> <?= $training['pause'] ?></li>
                        <li>🅿️ <strong>Parking :</strong> <?= $training['parking'] ?></li>
                    </ul>
                </div>
            </div>

            <!-- Formateur -->
            <div class="trainer-card">
                <h3>Votre formateur</h3>
                <div class="trainer-info">
                    <img src="/static/assets/trainer/<?= $trainer['avatar']; ?>." alt="Formateur expert" class="trainer-photo" width="60" height="60">
                    <div class="trainer-details">
                        <h4><?= $trainer['label'] ?? ''; ?></h4>
                        <p class="trainer-title"><?= $trainer['title'] ?? ''; ?></p>
                        <p class="trainer-experience">15 ans d'expérience</p>
                    </div>
                </div>
                <p><?= $trainer['bio']; ?></p>
            </div>

            <?php if (!empty($training['certification'])): ?>
                <!-- Certificat -->
                <div class="certificate-card">
                    <h3>Certification</h3>
                    <div class="certificate-info">
                        <span class="certificate-icon">🏆</span>
                        <div>
                            <h4>Attestation officielle</h4>
                            <p>Formation certifiée et reconnue dans le cadre de la formation professionnelle continue
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Contact -->
            <div class="contact-card">
                <h3>Questions ?</h3>
                <p>Notre équipe est disponible pour répondre à toutes vos questions</p>
                <div class="contact-methods">
                    <a href="tel:+32510080001" class="contact-method">
                        <span class="icon">📞</span>
                        +32 510 08 00 01
                    </a>
                    <a href="mailto:CoproAcademy@contact.be" class="contact-method">
                        <span class="icon">✉️</span>
                        CoproAcademy@contact.be
                    </a>
                </div>
            </div>
        </aside>
    </div>


    <?php
    return function ($this_html, $args = []) {
        return ob_ret_get('app/io/render/layout.php', ($args ?? []) + ['main' => $this_html])[1];
    };
