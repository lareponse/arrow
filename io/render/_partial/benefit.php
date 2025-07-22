<section class="formation-benefits full-width-section" aria-labelledby="benefits-title">
    <div class="formation-benefits-content">
        <h2 id="benefits-title" class="section-title">Pourquoi choisir Copro Academy&nbsp;?</h2>

        <div class="benefits-grid">
            <?php foreach ($benefits as $benefit) : ?>
                <div class="benefit-card">
                    <div class="benefit-icon"><?= $benefit['icon'] ?? '📝' ?></div>
                    <h3><?= $benefit['title'] ?? 'Avantage' ?></h3>
                    <p><?= $benefit['description'] ?? 'Description de l\'avantage' ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>