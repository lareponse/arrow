<section class="register-section">
    <div class="container">
        <div class="auth-box">
            <div class="auth-header">
                <h1>Create an Account</h1>
                <p>Join copro.academy to access our resources and content.</p>
            </div>

            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($errors['general']) ?>
                </div>
            <?php endif; ?>

            <form action="/register" method="post" class="auth-form">
                <?= csrf_form() ?>

                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                    <?php if (isset($errors['username'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['username']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['email']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>
                    <?php if (isset($errors['full_name'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['full_name']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" required>
                    <?php if (isset($errors['password'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['password']) ?></div>
                    <?php endif; ?>
                    <div class="form-hint">Password must be at least 8 characters long.</div>
                </div>

                <div class="form-group">
                    <label for="password_confirm" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" class="form-control <?= isset($errors['password_confirm']) ? 'is-invalid' : '' ?>" required>
                    <?php if (isset($errors['password_confirm'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['password_confirm']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" id="terms" name="terms" class="form-check-input" required>
                        <label for="terms" class="form-check-label">I agree to the <a href="/terms" target="_blank">Terms of Service</a> and <a href="/privacy" target="_blank">Privacy Policy</a></label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-block">Create Account</button>
                </div>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="/login">Login here</a></p>
            </div>
        </div>
    </div>
</section>