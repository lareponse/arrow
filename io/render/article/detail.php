<?php
// Collect up to 5 sections
$sections = [];
for ($i = 1; $i <= 5; $i++) {
    $lbl = $article["section{$i}_label"]   ?? null;
    $cnt = $article["section{$i}_content"] ?? null;
    if ($lbl && $cnt) {
        $sections[] = [
            'id'      => "section{$i}",
            'label'   => $lbl,
            'content' => $cnt,
        ];
    }
}
?>
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
            <time datetime="<?= e($article, 'enabled_at') ?>">
                <?= date('d F Y', strtotime($article['enabled_at'])) ?>
            </time>
            <span class="reading-time">📖 <?= e($article, 'reading_time') ?> min de lecture</span>
        </div>

        <h1><?= e($article, 'label') ?></h1>

        <?php if (!empty($article['summary'])): ?>
            <p class="article-summary">
                <?= e($article, 'summary') ?>
            </p>
        <?php endif; ?>

        <div class="article-tags">
            <?php foreach ($article['tags'] ?? [] as $tag): ?>
                <span class="tag"><?= e($tag) ?></span>
            <?php endforeach; ?>
        </div>

        <div class="article-actions">
            <button class="share-btn" onclick="shareArticle()" aria-label="Partager l'article">🔗 Partager</button>
            <button class="print-btn" onclick="window.print()" aria-label="Imprimer l'article">🖨️ Imprimer</button>
            <button class="bookmark-btn" onclick="bookmarkArticle()" aria-label="Ajouter aux favoris">🔖 Sauvegarder</button>
        </div>
    </header>

    <!-- Image principale -->
    <figure class="article-hero-image">
        <img
            src="/asset/image/article/avatar/<?= e($article, 'slug') ?>.webp"
            alt="<?= e($article, 'label') ?>"
            width="800" height="400">
        <figcaption><?= e($article, 'label') ?></figcaption>
    </figure>

    <!-- Contenu de l'article -->
    <article class="article-content">
        <div class="content-wrapper">

            <?php if (count($sections) > 0): ?>
                <!-- Sommaire -->
                <aside class="table-of-contents">
                    <h2>Sommaire</h2>
                    <nav aria-label="Sommaire de l'article">
                        <ol>
                            <?php foreach ($sections as $sec): ?>
                                <li><a href="#<?= e($sec, 'id') ?>"><?= e($sec, 'label') ?></a></li>
                            <?php endforeach; ?>
                        </ol>
                    </nav>
                </aside>
            <?php endif; ?>

            <!-- Corps de l'article -->
            <div class="article-body">
                <section>
                    <?= nl2br($article['content']) ?>
                </section>
                <?php foreach ($sections as $sec): ?>
                    <section id="<?= e($sec, 'id') ?>">
                        <h2><?= e($sec, 'label') ?></h2>
                        <?= nl2br($sec['content']) ?>
                    </section>
                <?php endforeach; ?>
            </div>
        </div>
    </article>

    <!-- Articles similaires -->
    <?php if (!empty($related_articles)): ?>
        <section class="related-articles" aria-labelledby="related-title">
            <h2 id="related-title">Articles similaires</h2>
            <div class="related-grid">
                <?php foreach ($related_articles as $related): ?>
                    <article class="related-card">
                        <img
                            src="/asset/image/article/avatar/<?= e($related, 'slug'); ?>"
                            alt="<?= e($related, 'label') ?>"
                            width="200" height="120">
                        <div class="related-content">
                            <h3><?= e($related, 'label') ?></h3>
                            <p><?= e($related, 'summary') ?></p>
                            <a href="/article/detail/<?= e($related, 'slug') ?>">Lire →</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>


<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/layout.php', ($args ?? []) + ['main' => $this_html, 'navbar__link__active' => 'article'])[1];
};
?>