<section class="newsletter wide">
    <div class="container">
        <h2 class="newsletter__title"><?= l('newsletter.title') ?></h2>
        <p class="newsletter__description"><?= l('newsletter.description') ?></p>

        <form class="newsletter__form" id="newsletterForm">
            <div class="newsletter__input-group">
                <input type="email" placeholder="<?= l('newsletter.email.placeholder') ?>" required class="newsletter__input"
                    aria-label="<?= l('newsletter.email.aria') ?>">
                <button type="submit" class="btn btn--primary"><?= l('newsletter.subscribe') ?></button>
            </div>
            <small class="newsletter__help"><?= l('newsletter.privacy_notice') ?></small>
        </form>
    </div>
</section>