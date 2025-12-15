<?php
require_once 'includes/functions.php';
if(!is_logged()) header('Location: login.php');

// mark read if asked
if(isset($_GET['mark_read'])){ mark_read(intval($_GET['mark_read'])); header('Location: notifications.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id=? ORDER BY created_at DESC");
$stmt->execute([uid()]); $notes = $stmt->fetchAll();
require_once 'includes/header.php';
?>
<div class="form"><h2>Notifikasi</h2>
<?php foreach($notes as $n): ?>
  <div class="card" style="display:flex;justify-content:space-between;align-items:center">
    <div>
      <strong><?= htmlspecialchars($n['title']) ?></strong>
      <div class="small"><?= nl2br(htmlspecialchars($n['message'])) ?></div>
      <div class="small"><?= $n['created_at'] ?> <?= $n['is_read'] ? '(dibaca)':'' ?></div>
    </div>
    <?php if(!$n['is_read']): ?>
      <div><button class="btn" onclick="markRead(<?= $n['id'] ?>)">Tandai dibaca</button></div>
    <?php endif; ?>
  </div>
<?php endforeach; ?>
</div>
<?php require_once 'includes/footer.php'; ?>
