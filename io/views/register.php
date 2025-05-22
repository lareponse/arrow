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

<style>
    .register-section {
        padding: 4rem 0;
        background-color: #f5f5f5;
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
    }

    .auth-box {
        max-width: 500px;
        margin: 0 auto;
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .auth-header h1 {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
    }

    .auth-header p {
        color: #666;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
    }

    .alert-error {
        background-color: rgba(231, 76, 60, 0.1);
        border: 1px solid #e74c3c;
        color: #e74c3c;
    }

    .auth-form {
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
    }

    .form-control.is-invalid {
        border-color: #e74c3c;
    }

    .form-error {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .form-hint {
        color: #666;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .form-check {
        display: flex;
        align-items: center;
    }

    .form-check-input {
        margin-right: 0.5rem;
    }

    .form-check-label {
        font-size: 0.9rem;
        color: #333;
    }

    .form-check-label a {
        color: #3498db;
        text-decoration: none;
    }

    .form-check-label a:hover {
        text-decoration: underline;
    }

    .form-actions {
        margin-top: 2rem;
    }

    .btn {
        display: inline-block;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        font-weight: 500;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        border: none;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    .btn-primary {
        background-color: #3498db;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    .btn-block {
        display: block;
        width: 100%;
    }

    .auth-footer {
        text-align: center;
        border-top: 1px solid #eee;
        padding-top: 1.5rem;
        font-size: 0.9rem;
    }

    .auth-footer p {
        margin-bottom: 0.5rem;
    }

    .auth-footer a {
        color: #3498db;
        text-decoration: none;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }

    @media (max-width: 550px) {
        .auth-box {
            padding: 1.5rem;
        }

        .register-section {
            padding: 2rem 0;
        }
    }
</style>