<?php
require_once 'includes/functions.php';
if(is_logged()) header('Location: index.php');
$err = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $pass = $_POST['password'];
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $err='Email tidak valid';
  else if(strlen($pass) < 6) $err='Password minimal 6 karakter';
  else {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $stmt->execute([$email]);
    if($stmt->fetch()) $err='Email sudah terdaftar';
    else {
      $hash = password_hash($pass, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
      $stmt->execute([$name,$email,$hash]);
      $uid = $pdo->lastInsertId();
      notify($uid,'Selamat datang','Akun Anda telah dibuat di PawCare');
      $_SESSION['user_id'] = $uid;
      $_SESSION['role'] = 'user';
      header('Location: index.php'); exit;
    }
  }
}
require_once 'includes/header.php';
?>
<div class="form">
  <h2>Register</h2>
  <?php if($err) echo "<div style='color:#b91c1c;margin-bottom:8px'>{$err}</div>"; ?>
  <form method="post">
    <label>Nama</label><input class="input" name="name" required>
    <label>Email</label><input class="input" name="email" type="email" required>
    <label>Password</label><input class="input" name="password" type="password" required>
    <button class="btn-primary">Daftar</button>
  </form>
</div>
<?php require_once 'includes/footer.php'; ?>
