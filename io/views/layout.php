<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['title'] ?? 'copro.academy') ?></title>
    <meta name="description" content="<?= htmlspecialchars($data['description'] ?? 'Professional development and educational resources') ?>">

    <!-- Additional head content from slots -->
    <?= implode("\n    ", slot('head')) ?>


</head>

<body>
    <header>
        <div class="container">
            <nav>
                <a href="/" class="logo">copro.academy</a>

                <ul class="nav-links">
                    <li><a href="/">Home</a></li>
                    <li><a href="/blog">Blog</a></li>
                    <li><a href="/events">Events</a></li>
                    <li><a href="/resources">Resources</a></li>
                    <li><a href="/contact">Contact</a></li>
                    <?php if (auth_user_active()): ?>
                            <li><a href="/admin">Admin</a></li>
                        <li><a href="/logout">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/login">Login</a></li>
                        <li><a href="/register">Register</a></li>
                    <?php endif; ?>
                </ul>

                <form class="search-form" action="/search" method="GET">
                    <input type="search" name="q" placeholder="Search..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    <button type="submit">Search</button>
                </form>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <?= implode('', slot('main')) ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> copro.academy</p>
        </div>
    </footer>

    <!-- Scripts from slots -->
    <?= implode("\n    ", slot('scripts')) ?>
</body>

</html>