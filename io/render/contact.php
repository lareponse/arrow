    <h1>Contactez-nous</h1>

    <!-- Section intro -->
    <section>
        <div class="card p-xl text-center mb-xl">

            <h2 class="mb-md">Besoin d'accompagnement ?</h2>
            <p class="mb-0">Notre équipe d'experts est à votre disposition pour répondre à toutes vos questions
                concernant nos formations, nos services ou pour un accompagnement personnalisé.</p>
        </div>
    </section>

    <!-- Informations de contact -->
    <section aria-labelledby="contact-info-title">
        <h2 id="contact-info-title" class="text-center mb-2xl">Nos coordonnées</h2>

        <div class="contact-cards">
            <div class="contact-card">
                <div class="contact-card__icon">📧</div>
                <h3 class="contact-card__title">Email</h3>
                <p><a href="mailto:<?= e($coproacademy, 'email') ?>"><?= e($coproacademy, 'email') ?></a></p>
                <p class="mb-0"><?= e($coproacademy, 'email-response-time') ?: 'Réponse sous 24h ouvrées' ?></p>
            </div>

            <div class="contact-card">
                <div class="contact-card__icon">📞</div>
                <h3 class="contact-card__title">Téléphone</h3>
                <p><a href="tel:<?= e($coproacademy, 'telephone') ?>"><?= e($coproacademy, 'telephone') ?: 'Cette information est indisponible' ?></a></p>
                <p class="mb-0"><?= e($coproacademy, 'telephone-hours') ?: 'Cette information est indisponible' ?></p>
            </div>

            <div class="contact-card">
                <div class="contact-card__icon">📍</div>
                <h3 class="contact-card__title">Adresse</h3>
                <p><?= nl2br(e($coproacademy, 'adresse') ?: 'Cette information est indisponible') ?></p>
                <p class="mb-0">Sur rendez-vous uniquement</p>
            </div>
        </div>
    </section>

    <!-- Formulaire de contact -->
    <section aria-labelledby="form-title">
        <div class="card">

            <form class="p-xl" style="width:100%; max-width: 800px; margin: 0 auto;">
                <h2 id="form-title" class="text-center mb-xl">Envoyez-nous un message</h2>


                <!--  Groupe : Informations personnelles -->
                <fieldset class="form-fieldset">
                    <legend class="form-legend">Informations personnelles</legend>

                    <div class="grid grid-cols-2 gap-lg mobile:grid-cols-1">
                        <div class="form-group">
                            <label for="nom" class="form-label">
                                Nom complet
                                <span class="required-indicator" aria-label="obligatoire">*</span>
                            </label>
                            <input type="text" id="nom" name="nom" required class="form-input"
                                aria-describedby="nom-error nom-help" autocomplete="name">
                            <span class="form-help" id="nom-help">Votre nom et prénom</span>
                            <span class="form-error" id="nom-error" role="alert" aria-live="polite"></span>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                Adresse email
                                <span class="required-indicator" aria-label="obligatoire">*</span>
                            </label>
                            <input type="email" id="email" name="email" required class="form-input"
                                aria-describedby="email-error email-help" autocomplete="email">
                            <span class="form-help" id="email-help">Format : exemple@domaine.com</span>
                            <span class="form-error" id="email-error" role="alert" aria-live="polite"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-lg mobile:grid-cols-1">
                        <div class="form-group">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="tel" id="telephone" name="telephone" class="form-input"
                                aria-describedby="telephone-help" autocomplete="tel">
                            <span class="form-help" id="telephone-help">Optionnel - pour un contact plus
                                rapide</span>
                        </div>

                        <div class="form-group">
                            <label for="entreprise" class="form-label">Entreprise/Organisation</label>
                            <input type="text" id="entreprise" name="entreprise" class="form-input"
                                autocomplete="organization">
                        </div>
                    </div>
                </fieldset>

                <!--  Groupe : Demande -->
                <fieldset class="form-fieldset">
                    <legend class="form-legend">Votre demande</legend>

                    <div class="form-group">
                        <label for="sujet" class="form-label">
                            Type de demande
                            <span class="required-indicator" aria-label="obligatoire">*</span>
                        </label>
                        <select id="sujet" name="sujet" required class="form-input"
                            aria-describedby="sujet-error">
                            <option value="">Sélectionnez un type de demande</option>
                            <?php foreach ($subjects as $slug => $label) : ?>
                                <option value="<?= e($slug) ?>"><?= e($label) ?></option>
                            <?php endforeach; ?>

                        </select>
                        <span class="form-error" id="sujet-error" role="alert" aria-live="polite"></span>
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">
                            Message
                            <span class="required-indicator" aria-label="obligatoire">*</span>
                        </label>
                        <textarea id="message" name="message" rows="6" required class="form-input"
                            aria-describedby="message-error message-help"
                            placeholder="Décrivez votre demande en détail..."></textarea>
                        <span class="form-help" id="message-help">Minimum 20 caractères</span>
                        <span class="form-error" id="message-error" role="alert" aria-live="polite"></span>
                    </div>
                </fieldset>

                <!--  Groupe : Consentement -->
                <fieldset class="form-fieldset">
                    <legend class="form-legend">Consentement</legend>

                    <div class="form-group">
                        <label class="checkbox">
                            <input type="checkbox" id="consent" name="consent" required class="checkbox__input"
                                aria-describedby="consent-error consent-help">
                            <span class="checkbox__mark" aria-hidden="true"></span>
                            <span class="checkbox__label">
                                J'accepte que mes données soient utilisées pour traiter ma demande
                                <span class="required-indicator" aria-label="obligatoire">*</span>
                            </span>
                        </label>
                        <span class="form-help" id="consent-help">
                            Vos données sont traitées conformément à notre
                            <a href="/page/politique-confidentialite">politique de confidentialité</a>
                        </span>
                        <span class="form-error" id="consent-error" role="alert" aria-live="polite"></span>
                    </div>
                </fieldset>

                <div class="text-center mt-xl">
                    <button type="submit" class="btn btn--primary btn--lg" aria-describedby="submit-help">
                        <span class="btn-text">📧 Envoyer le message</span>
                        <span class="btn-loading hidden" aria-hidden="true">⏳ Envoi en cours...</span>
                    </button>
                    <span id="submit-help" class="block mt-md text-gray-500">
                        Tous les champs marqués d'un * sont obligatoires
                    </span>
                </div>
            </form>
        </div>

    </section>

    <!-- FAQ rapide -->
    <section class="faq bg-gray-50 wide" aria-labelledby="faq-title">
            <h2 id="faq-title" class="text-center mb-2xl">Questions fréquentes</h2>
            <div class="tight">
                <?php foreach ($faq as $item) : ?>
                    <details class="faq-item">
                        <summary class="faq-summary"><?= e($item, 'label') ?: 'Question vide' ?></summary>
                        <div class="faq-content">
                            <p><?= e($item, 'content') ?: 'Reponse vide' ?></p>
                        </div>
                    </details>
                <?php endforeach; ?>
            </div>
    </section>

    <!-- CTA final -->
    <section class="newsletter wide">
        <div class="container">
            <h2 class="newsletter__title">Prêt à commencer ?</h2>
            <p class="newsletter__description">Contactez-nous dès maintenant pour discuter de vos besoins</p>
            <div class="flex gap-lg justify-center flex-wrap">
                <a href="tel:<?= e($coproacademy, 'telephone') ?>" class="btn btn--primary btn--lg">Appeler maintenant</a>
                <a href="mailto:<?= e($coproacademy, 'email') ?>" class="btn btn--secondary btn--lg">Envoyer un email</a>
            </div>
        </div>
    </section>

    <?php
    return function ($this_html, $args = []) {
        return ob_ret_get('app/io/render/layout.php', ($args ?? []) + ['main' => $this_html])[1];
    };
