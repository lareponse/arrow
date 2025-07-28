<h1>Créer un compte</h1>

<section class="register">
    <?php if (isset($errors['general'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($errors['general']) ?>
        </div>
    <?php endif; ?>

    <form action="/register" method="post" class="auth-form">
        <?= csrf_field() ?>


        <div class="form-group">
            <label for="full_name" class="form-label">Nom complet :</label>
            <input type="text" id="full_name" name="full_name" class="form-control<?= isset($errors['full_name']) ? ' is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>
            <?php if (isset($errors['full_name'])): ?>
                <div class="form-error"><?= htmlspecialchars($errors['full_name']) ?></div>
            <?php endif; ?>
        </div>


        <div class="form-group">
            <label for="email" class="form-label">E-mail :</label>
            <input type="email" id="email" name="username" class="form-control<?= isset($errors['username']) ? ' is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            <?php if (isset($errors['email'])): ?>
                <div class="form-error"><?= htmlspecialchars($errors['username']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Mot de passe :</label>
            <input type="password" id="password" name="password" placeholder="8 caractères minimum" class="form-control<?= isset($errors['password']) ? ' is-invalid' : '' ?>" required>
            <?php if (isset($errors['password'])): ?>
                <div class="form-error"><?= htmlspecialchars($errors['password']) ?></div>
            <?php endif; ?>
            <div class="form-hint"></div>
        </div>

        <div class="form-group">
            <label for="password_confirm" class="form-label">Confirmer :</label>
            <input type="password" id="password_confirm" name="password_confirm" placeholder="8 caractères minimum" class="form-control<?= isset($errors['password_confirm']) ? ' is-invalid' : '' ?>" required>
            <?php if (isset($errors['password_confirm'])): ?>
                <div class="form-error"><?= htmlspecialchars($errors['password_confirm']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-check">
            <input type="checkbox" id="terms" name="terms" class="form-check-input" required>
            <label for="terms" class="form-check-label">J'accepte les <a href="/terms">conditions</a> et la <a href="/privacy">confidentialité</a></label>
        </div>

        <div class="form-actions">
            <button type="submit" class="cta">Créer le compte</button>
        </div>
    </form>

    <p><a href="/login">J'ai déjà un compte</a></p>
</section>