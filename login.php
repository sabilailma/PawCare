<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

if (is_logged()) {
    if (is_admin()) {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];

        notify($user['id'], 'Login berhasil', 'Selamat datang kembali!');

        if ($user['role'] === 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: index.php');
        }
        exit;
    } else {
        $err = 'Email atau password salah';
    }
}

require_once 'includes/header.php';
?>

<div class="form">
    <h2>Login</h2>

    <?php if ($err): ?>
        <div style="color:#b91c1c;margin-bottom:10px"><?= $err ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Email</label>
        <input class="input" name="email" type="email" required>

        <label>Password</label>
        <input class="input" name="password" type="password" required>

        <button class="btn-primary">Login</button>
    </form>

    <p class="small">Belum punya akun? <a href="register.php">Register</a></p>
</div>

<?php require_once 'includes/footer.php'; ?>