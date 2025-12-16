<?php
require_once 'includes/functions.php';
if(!is_logged()) header('Location: login.php');
require_once 'includes/header.php';

$stmt = $pdo->prepare("
  SELECT * FROM notifications
  WHERE user_id=?
  ORDER BY created_at DESC
");
$stmt->execute([uid()]);
$notifs = $stmt->fetchAll();
?>

<h2>🔔 Notifikasi</h2>

<?php if(!$notifs): ?>
<p class="small">Belum ada notifikasi.</p>
<?php endif; ?>

<?php foreach($notifs as $n): ?>
<div class="card" style="<?= $n['is_read'] ? '' : 'border-left:5px solid #22c55e' ?>">
  <strong><?= htmlspecialchars($n['title']) ?></strong>
  <p class="small"><?= htmlspecialchars($n['message']) ?></p>
  <span class="small"><?= $n['created_at'] ?></span>
</div>
<?php endforeach; ?>

<?php require_once 'includes/footer.php'; ?>
