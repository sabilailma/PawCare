<?php
require_once 'includes/functions.php';
if(!is_logged()) header('Location: login.php');
require_once 'includes/header.php';
$uid = uid();
$user = $pdo->prepare("SELECT * FROM users WHERE id=?"); $user->execute([$uid]); $user = $user->fetch();

$adopts = $pdo->prepare("SELECT a.*, p.name AS pet_name FROM adoption_requests a LEFT JOIN pets p ON a.pet_id=p.id WHERE a.user_id=? ORDER BY a.created_at DESC"); $adopts->execute([$uid]); $adopts = $adopts->fetchAll();
$bookings = $pdo->prepare("SELECT b.*, s.name AS service_name FROM bookings b LEFT JOIN services s ON b.service_id = s.id WHERE b.user_id=? ORDER BY b.created_at DESC"); $bookings->execute([$uid]); $bookings = $bookings->fetchAll();
$pickups = $pdo->prepare("SELECT * FROM pickup_requests WHERE user_id=? ORDER BY created_at DESC"); $pickups->execute([$uid]); $pickups = $pickups->fetchAll();
?>
<div class="card">
  <h2><?= htmlspecialchars($user['name']) ?></h2>
  <p class="small"><?= htmlspecialchars($user['email']) ?></p>
</div>

<div class="form"><h3>Pengajuan Adopsi</h3>
<?php foreach($adopts as $a): ?>
  <div class="card"><strong><?= htmlspecialchars($a['pet_name']) ?></strong> • <?= $a['status'] ?><p class="small"><?= nl2br(htmlspecialchars($a['message'])) ?></p></div>
<?php endforeach; ?>
</div>

<div class="form"><h3>Bookings</h3>
<?php foreach($bookings as $b): ?>
  <div class="card"><?= htmlspecialchars($b['service_name']) ?> • <?= $b['booking_date'] ?> <?= $b['booking_time'] ?> • <?= $b['status'] ?></div>
<?php endforeach; ?>
</div>

<div class="form"><h3>Pickup Requests</h3>
<?php foreach($pickups as $p): ?>
  <div class="card"><?= htmlspecialchars($p['service_type']) ?> • <?= $p['pickup_date'] ?> <?= $p['pickup_time'] ?> • <?= $p['status'] ?></div>
<?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
