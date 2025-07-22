<?php
l(null, require 'app/lang/fr.php'); // Load French translations

$coproacademy = viewport('coproacademy');

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Accueil' ?> - Copro Academy | Formations en gestion de copropriétés</title>
    <meta name="description"
        content="<?= $description ?? "Copro Academy - Expert en formations et accompagnement pour la gestion de copropriétés. Formations certifiées, actualités juridiques et support professionnel."  ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">

    <link rel="icon" type="image/png" href="/static/assets/base/icon/base_icon_transparent_background.png">

    <!-- Modular CSS -->
    <?= $prepend_css ?? '' ?>
    <link rel="stylesheet" href="/static/css/00-reset.css">
    <link rel="stylesheet" href="/static/css/01-variables.css">
    <link rel="stylesheet" href="/static/css/02-base.css">
    <link rel="stylesheet" href="/static/css/03-layout.css">
    <link rel="stylesheet" href="/static/css/04-components.css">
    <link rel="stylesheet" href="/static/css/05-utilities.css">
    <link rel="stylesheet" href="/static/css/06-pages.css">
    
    <?= $append_css ?? '' ?>
</head>

<body>
    <div class="skip-links">
        <a href="#main-content" class="skip-link">Aller au contenu principal</a>
        <a href="#main-nav" class="skip-link">Aller à la navigation</a>
    </div>

    <!-- Navigation Header -->
    <header class="navbar" role="banner">
        <div class="navbar__container">
            <a href="/" class="navbar__logo-link" aria-label="Retour à l'accueil">

                <img src="<?= e($coproacademy, 'logo') ?>" alt="<?= e($coproacademy, 'label') ?> Logo"
                    class="navbar__logo">
            </a>

            <button class="navbar__burger" aria-label="Menu de navigation" aria-expanded="false">
                &#9776;
            </button>
            <nav class="navbar__nav" role="navigation" aria-label="Navigation principale" id="main-nav">
                <a href="/" class="navbar__link <?= empty($navbar__link__active) ? 'navbar__link--active' : '' ?>">Accueil</a>
                <a href="/article" class="navbar__link <?= $navbar__link__active === 'article' ? 'navbar__link--active' : '' ?>">Articles & Événements</a>
                <a href="/formation" class="navbar__link <?= $navbar__link__active === 'formation' ? 'navbar__link--active' : '' ?>">Formation</a>
                <a href="/contact" class="navbar__link <?= $navbar__link__active === 'contact' ? 'navbar__link--active' : '' ?>">Contact</a>
            </nav>
        </div>
    </header>

    <main id="main-content">
        <?= $main
            ?? '<section class="placeholder">
                <h2>Bienvenue chez Copro Academy</h2>
                <p>Le contenu de cette page est en cours de préparation. Revenez bientôt pour découvrir nos actualités et nos formations !</p>
             </section>'
        ?>
    </main>


    <footer class="footer" role="contentinfo">
        <div class="footer__content">
            <div class="footer__section">
                <h3>Navigation</h3>
                <ul class="footer__links">
                    <li><a href="/">Accueil</a></li>
                    <li><a href="/article">Articles & Événements</a></li>
                    <li><a href="/formation">Formation</a></li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </div>

            <div class="footer__section">
                <h3>Informations légales</h3>
                <ul class="footer__links">
                    <li><a href="/legal/conditions-generales">Conditions générales</a></li>
                    <li><a href="/legal/politique-confidentialite">Politique de confidentialité</a></li>
                    <li><a href="/legal/mentions-legales">Mentions légales</a></li>
                </ul>
            </div>

            <div class="footer__section">
                <h3>Suivez-nous</h3>
                <div class="footer__social">
                    <a href="#" aria-label="Facebook">
                        <img src="/static/assets/logo_réseaux_sociaux/facebook-svgrepo-com-3.svg" alt="Facebook">
                    </a>
                    <a href="#" aria-label="Instagram">
                        <img src="/static/assets/logo_réseaux_sociaux/instagram-svgrepo-com-2.svg" alt="Instagram">
                    </a>
                    <a href="#" aria-label="LinkedIn">
                        <img src="/static/assets/logo_réseaux_sociaux/linkedin-svgrepo-com.svg" alt="LinkedIn">
                    </a>
                </div>
            </div>

            <div class="footer__section text-center">
                <img src="/static/assets/color1/full/white_logo_color1_background.png" alt="Logo Copro Academy"
                    style="max-height: 80px; width: auto;">
            </div>
        </div>

        <div class="footer__separator"></div>

        <div class="footer__copyright">
            <div class="flex justify-center gap-lg flex-wrap mt-sm">
                <span>Email : <a href="mailto:CoproAcademy@contact.be"><?= e($coproacademy, 'email') ?></a></span>
                <span>Téléphone : <a href="tel:<?= e($coproacademy, 'telephone') ?>"><?= e($coproacademy, 'telephone') ?></a></span>
                <span>Adresse : <?= nl2br(e($coproacademy, 'adresse')) ?></span>

            </div>
            <p>&copy; <?= date('Y') ?> <?= e($coproacademy, 'label') ?> - Tous droits réservés</p>
        </div>
    </footer>

    <?= $prepend_js ?? '' ?>
    <script src="/static/script.js"></script>
    <?= $append_js ?? '' ?>
</body>

</html>