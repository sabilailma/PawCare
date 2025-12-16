
<?php
require_once 'includes/functions.php';
if (!is_logged()) header('Location: login.php');
require_once 'includes/header.php';

$uid = uid();

/* ===============================
   USER
================================ */
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$uid]);
$user = $stmt->fetch();

/* ===============================
   ADOPTION REQUESTS
================================ */
$stmt = $pdo->prepare("
    SELECT a.*, p.name AS pet_name 
    FROM adoption_requests a
    LEFT JOIN pets p ON a.pet_id = p.id
    WHERE a.user_id=?
    ORDER BY a.created_at DESC
");
$stmt->execute([$uid]);
$adopts = $stmt->fetchAll();

/* ===============================
   BOOKINGS
================================ */
$stmt = $pdo->prepare("
    SELECT b.*, s.name AS service_name 
    FROM bookings b 
    LEFT JOIN services s ON b.service_id = s.id 
    WHERE b.user_id=?
    ORDER BY b.created_at DESC
");
$stmt->execute([$uid]);
$bookings = $stmt->fetchAll();

/* ===============================
   PICKUP REQUESTS
================================ */
$stmt = $pdo->prepare("
    SELECT * 
    FROM pickup_requests 
    WHERE user_id=? 
    ORDER BY created_at DESC
");
$stmt->execute([$uid]);
$pickups = $stmt->fetchAll();

/* ===============================
   HELPER BADGE
================================ */
function badge($status) {
    $colors = [
        'pending'   => '#f59e0b',
        'approved'  => '#22c55e',
        'confirmed' => '#22c55e',
        'completed' => '#3b82f6',
        'rejected'  => '#ef4444',
        'cancelled' => '#ef4444'
    ];
    $color = $colors[$status] ?? '#6b7280';

    return "<span style='
        padding:4px 10px;
        border-radius:999px;
        font-size:12px;
        background:$color;
        color:white'>
        ".ucfirst($status)."
    </span>";
}
?>

<!-- USER CARD -->
<div class="card">
  <h2><?= htmlspecialchars($user['name']) ?></h2>
  <p class="small"><?= htmlspecialchars($user['email']) ?></p>
</div>

<!-- ADOPTION -->
<div class="form">
<h3>🐾 Pengajuan Adopsi</h3>

<?php if(!$adopts): ?>
  <p class="small">Belum ada pengajuan adopsi.</p>
<?php endif; ?>

<?php foreach($adopts as $a): ?>
  <div class="card">
    <strong><?= htmlspecialchars($a['pet_name'] ?? '-') ?></strong>
    <?= badge($a['status']) ?>

    <p class="small" style="margin-top:8px">
      <?= nl2br(htmlspecialchars($a['message'] ?? '')) ?>
    </p>

    <?php if($a['status'] === 'pending'): ?>
      <form method="post" action="cancel_adoption.php" style="margin-top:10px">
        <input type="hidden" name="id" value="<?= $a['id'] ?>">
        <button class="btn" onclick="return confirm('Yakin batalkan pengajuan adopsi?')">
          ❌ Batalkan Adopsi
        </button>
      </form>
    <?php endif; ?>

  </div>
<?php endforeach; ?>
</div>


<!-- BOOKINGS -->
<div class="form">
<h3>📅 Bookings</h3>

<?php if(!$bookings): ?>
  <p class="small">Belum ada booking layanan.</p>
<?php endif; ?>

<?php foreach($bookings as $b): ?>
  <div class="card">
    <strong><?= htmlspecialchars($b['service_name'] ?? '-') ?></strong><br>
    <span class="small">
      <?= htmlspecialchars($b['booking_date']) ?> 
      <?= htmlspecialchars($b['booking_time']) ?>
    </span>
    <div style="margin-top:6px">
      <?= badge($b['status']) ?>
    </div>
  </div>
<?php endforeach; ?>
</div>

<!-- PICKUP -->
<div class="form">
<h3>🚚 Pickup Requests</h3>

<?php if(!$pickups): ?>
  <p class="small">Belum ada permintaan pickup.</p>
<?php endif; ?>

<?php foreach($pickups as $p): ?>
  <div class="card">
    <strong><?= htmlspecialchars($p['service_type'] ?? '-') ?></strong><br>
    <span class="small">
      <?= htmlspecialchars($p['pickup_date']) ?> 
      <?= htmlspecialchars($p['pickup_time']) ?>
    </span>
    <div style="margin-top:6px">
      <?= badge($p['status']) ?>
    </div>
  </div>
<?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>

