<?php
require_once __DIR__.'/functions.php';
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>PawCare</title>

<!-- CSS NAVBAR BARU -->
<link rel="stylesheet" href="/pawcare/assets/css/navbar.css">

<!-- CSS UTAMA -->
<link rel="stylesheet" href="/pawcare/assets/css/style.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar">
    <div class="nav-container">

        <!-- LOGO -->
        <a href="/pawcare/index.php" class="nav-logo">
            <img src="/pawcare/assets/img/logo2.png" alt="Logo">
            <span>PawCare</span>
        </a>

        <!-- MENU TOGGLE (MOBILE) -->
        <div class="nav-toggle" id="navToggle">☰</div>

        <!-- MENU LIST -->
        <ul class="nav-menu" id="navMenu">

            <li><a href="/pawcare/index.php">Home</a></li>
            <li><a href="/pawcare/adoption.php">Adopsi</a></li>
            <li><a href="/pawcare/services.php">Layanan</a></li>
            <li><a href="/pawcare/pickup.php">Antar / Jemput</a></li>

            <?php if(is_logged()): ?>
                <li><a href="/pawcare/profile.php">Profil</a></li>
                <li><a href="/pawcare/notifications.php">
                    Notifikasi (<?= get_unread_count(uid()) ?>)
                </a></li>

                <?php if(is_admin()): ?>
                    <li><a href="/pawcare/admin/dashboard.php">Admin</a></li>
                <?php endif; ?>

                <!-- MOBILE LOGOUT BUTTON -->
                <li class="mobile-only">
                    <a class="btn-logout" href="/pawcare/logout.php">Logout</a>
                </li>

            <?php else: ?>
                <!-- MOBILE LOGIN REGISTER -->
                <li class="mobile-only"><a class="btn-login" href="/pawcare/login.php">Login</a></li>
                <li class="mobile-only"><a class="btn-register" href="/pawcare/register.php">Register</a></li>
            <?php endif; ?>

        </ul>

        <!-- BUTTON (DESKTOP) -->
        <div class="nav-buttons">

            <?php if(is_logged()): ?>
                <a class="btn-login" href="/pawcare/logout.php">Logout</a>
            <?php else: ?>
                <a class="btn-login" href="/pawcare/login.php">Login</a>
                <a class="btn-register" href="/pawcare/register.php">Register</a>
            <?php endif; ?>

        </div>
    </div>
</nav>

<!-- JS Toggle -->
<script>
document.getElementById("navToggle").onclick = function() {
    document.getElementById("navMenu").classList.toggle("active");
};
</script>

<main class="pc-container">
