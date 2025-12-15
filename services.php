<?php
require_once 'includes/functions.php';
require_once 'includes/header.php';

// Ambil semua layanan
$stmt = $pdo->query("SELECT * FROM services ORDER BY id ASC");
$services = $stmt->fetchAll();
?>
<section class="page-header">
    <h1>Layanan Pet Care</h1>
    <p>Kami menyediakan berbagai layanan terbaik untuk menjaga hewan kesayanganmu tetap sehat dan happy! 🐾</p>
</section>

<div class="cards">
<?php foreach($services as $s): ?>
  <div class="card">
    <h3><?= htmlspecialchars($s['name']) ?></h3>

    <!-- Potongan deskripsi -->
    <p class="small">
      <?= htmlspecialchars(substr($s['description'], 0, 150)) ?>...
    </p>

    <!-- Tombol -->
    <div style="margin-top:auto">
      <a class="btn" href="service_detail.php?id=<?= $s['id'] ?>">Detail</a>

      <?php if(is_logged()): ?>
        <a class="btn-primary" href="booking.php?service_id=<?= $s['id'] ?>">
            Booking – Rp <?= number_format($s['price'], 0, ',', '.') ?>
        </a>
      <?php else: ?>
        <a class="btn-primary" href="login.php">Login to book</a>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
