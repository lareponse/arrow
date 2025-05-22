<?php

/**
 * Partial: articles list
 * Expects:
 *   $articles: array of associative arrays with keys:
 *     'id', 'title', 'slug', 'excerpt', 'image_url', 'created_at', 'author_name'
 */
?>
<section aria-labelledby="articles-list-heading">
    <h1 id="articles-list-heading">Articles</h1>

    <?php if (!empty($data['articles'])): ?>
        <?php foreach ($data['articles'] as $article): ?>
            <article id="article-<?php echo htmlspecialchars($article['id'], ENT_QUOTES, 'UTF-8'); ?>">

                <?php if (!empty($article['image_url'])): ?>
                    <figure>
                        <a href="/blog/article/<?php echo urlencode($article['slug']); ?>">
                            <img
                                src="<?php echo htmlspecialchars($article['image_url'], ENT_QUOTES, 'UTF-8'); ?>"
                                alt="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                        </a>
                        <figcaption>
                            Image for "<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>"
                        </figcaption>
                    </figure>
                <?php endif; ?>

                <header>
                    <h2>
                        <a href="/blog/article/<?php echo urlencode($article['slug']); ?>">
                            <?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </h2>
                    <p class="meta">
                        <time datetime="<?php echo htmlspecialchars($article['created_at'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo date('F j, Y', strtotime($article['created_at'])); ?>
                        </time>
                        <?php if (!empty($article['author_name'])): ?>
                            &nbsp;by <?php echo htmlspecialchars($article['author_name'], ENT_QUOTES, 'UTF-8'); ?>
                        <?php endif; ?>
                    </p>
                </header>

                <div class="excerpt">
                    <p>
                        <?php echo nl2br(htmlspecialchars($article['excerpt'], ENT_QUOTES, 'UTF-8')); ?>
                    </p>
                </div>

                <footer>
                    <a href="/blog/article/<?php echo urlencode($article['slug']); ?>" class="read-more">
                        Read more
                        <span class="visually-hidden">
                            about <?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </a>
                </footer>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No articles found.</p>
    <?php endif; ?>
</section>