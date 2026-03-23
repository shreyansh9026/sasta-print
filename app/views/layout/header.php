<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Professional printing services online. High-quality prints delivered fast.">
    <title><?= htmlspecialchars($title ?? APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
</head>
<body>
    <header class="navbar" id="navbar">
        <div class="container">
            <a href="<?= BASE_URL ?>" class="logo">
                <i class='bx bxs-printer'></i> <?= APP_NAME ?>
            </a>
            <nav class="nav-links" id="nav-links">
                <a href="<?= BASE_URL ?>/products">Products</a>
                <a href="<?= BASE_URL ?>/cart" class="cart-link" style="display:flex; align-items:center; gap:0.25rem;">
                    <i class='bx bx-shopping-bag' style="font-size:1.25rem;"></i> 
                    Cart <span class="badge badge-primary" id="cart-count"><?= count($_SESSION['cart'] ?? []) ?></span>
                </a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/dashboard">Dashboard</a>
                    <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a href="<?= BASE_URL ?>/admin">Admin Panel</a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/logout" class="btn btn-outline">Logout</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login">Login</a>
                    <a href="<?= BASE_URL ?>/register" class="btn btn-primary">Sign Up</a>
                <?php endif; ?>
            </nav>
            <div class="mobile-menuBtn" onclick="document.getElementById('nav-links').classList.toggle('active')">
                <i class='bx bx-menu'></i>
            </div>
        </div>
    </header>

    <script>
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if(window.scrollY > 20) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
    <main>
