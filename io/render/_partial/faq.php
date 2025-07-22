<section class="bg-gray-50 py-3xl wide" aria-labelledby="faq-title">
    <div class="container">
        <h2 id="faq-title" class="text-center mb-2xl">Questions fréquentes</h2>

        <div style="max-width: 800px; margin: 0 auto;">
            <?php foreach ($faq as $item) : ?>
                <details class="faq-item">
                    <summary class="faq-summary"><?= $item['label'] ?? 'Question vide' ?></summary>
                    <div class="faq-content">
                        <p><?= $item['content'] ?? 'Reponse vide' ?></p>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>