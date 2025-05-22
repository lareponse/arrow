<section class="login-section">
    <div class="container">
        <div class="auth-box">
            <div class="auth-header">
                <h1>Login to copro.academy</h1>
                <p>Welcome back! Please login to your account.</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="/login<?= !empty($redirect) ? '?redirect=' . htmlspecialchars($redirect) : '' ?>" method="post" class="auth-form">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">Remember me</label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </div>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="/register">Register here</a></p>
                <p><a href="/reset-password">Forgot your password?</a></p>
            </div>
        </div>
    </div>
</section>

<style>
    .login-section {
        padding: 4rem 0;
        background-color: #f5f5f5;
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .auth-box {
        max-width: 450px;
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

    .form-check {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .form-check-input {
        margin-right: 0.5rem;
    }

    .form-check-label {
        font-size: 0.9rem;
        color: #666;
    }

    .form-actions {
        margin-bottom: 1rem;
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

    @media (max-width: 500px) {
        .auth-box {
            padding: 1.5rem;
        }

        .login-section {
            padding: 2rem 0;
        }
    }
</style>