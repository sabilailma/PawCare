<?php
require_once 'includes/functions.php';
$id = intval($_GET['id'] ?? 0);
if(!$id){ header('Location: adoption.php'); exit; }
$stmt = $pdo->prepare("SELECT * FROM pets WHERE id=?");
$stmt->execute([$id]); $pet = $stmt->fetch();
if(!$pet) { header('Location: adoption.php'); exit; }
require_once 'includes/header.php';
?>
<div class="card">
  <img src="<?= $pet['image'] ?: 'assets/img/pet_placeholder.jpg' ?>" alt="">
  <h2><?= htmlspecialchars($pet['name']) ?> <small class="small"><?= htmlspecialchars($pet['type']) ?></small></h2>
  <p class="small">Age: <?= htmlspecialchars($pet['age']) ?> • Health: <?= htmlspecialchars($pet['health_status']) ?></p>
  <p><?= nl2br(htmlspecialchars($pet['description'])) ?></p>
  <div style="margin-top:12px">
    <?php if(is_logged()): ?>
      <a class="btn-primary" href="adoption.php?pet_id=<?= $pet['id'] ?>">Ajukan Adopsi</a>
    <?php else: ?>
      <a class="btn" href="login.php">Login untuk Ajukan</a>
    <?php endif; ?>
  </div>
</div>
<?php require_once 'includes/footer.php'; ?>
