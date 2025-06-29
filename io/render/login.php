<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form action="/login<?= !empty($redirect) ? '?redirect=' . htmlspecialchars($redirect) : '' ?>" method="post" class="auth-form">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="username" class="form-label">Username</label>
        <input type="text" id="username" name="username" class="form-control" required autofocus value="admin@domain.com">
    </div>

    <div class=" form-group">
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

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
