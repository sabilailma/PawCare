<?php
require_once 'includes/functions.php';
require_once 'includes/header.php';

// Ambil data pets
$stmt = $pdo->query("SELECT * FROM pets ORDER BY created_at DESC LIMIT 8");
$pets = $stmt->fetchAll();

// Ambil layanan
$stmt2 = $pdo->query("SELECT * FROM services ORDER BY id ASC LIMIT 6");
$services = $stmt2->fetchAll();

// Ambil testimoni layanan
$reviews = $pdo->query("
    SELECT service_reviews.*, users.name AS user_name, photo AS user_photo
    FROM service_reviews
    JOIN users ON users.id = service_reviews.user_id
    ORDER BY created_at DESC
    LIMIT 10
")->fetchAll();
?>

<!-- HERO -->
<section class="hero">
  <div class="hero-left">
   <h1>Find your new fluffy bestie at PawCare! 🐾</h1>
   <p>Yuk ketemuin calon teman kecil yang super lucu dan siap kasih kamu happy vibes every day. 
   Dari adopsi sampai grooming, PawCare selalu ada buat your furry darling!</p>
   <a class="btn-primary" href="adoption.php">Lihat Hewan Siap Adopsi</a>
  </div>

  <div class="hero-right">
    <img src="assets/img/hewan2.jpg" alt="hero" style="max-width:320px;border-radius:12px">
  </div>
</section>

<!-- PET ADOPTION SECTION -->
<div class="section-title">
  <h2>Fluffy Friends Ready for Adoption</h2>
  <a href="adoption.php">Lihat semua</a>
</div>

<div class="cards">
<?php foreach($pets as $p): ?>
  <div class="card">
    <img src="<?= $p['image'] ? htmlspecialchars($p['image']) : 'assets/img/pet_placeholder.jpg' ?>" alt="">
    <h3><?= htmlspecialchars($p['name']) ?> <span class="small">(<?= htmlspecialchars($p['type']) ?>)</span></h3>
    <p class="small">Age: <?= htmlspecialchars($p['age']) ?> • Health: <?= htmlspecialchars($p['health_status']) ?></p>
    <p class="small"><?= htmlspecialchars(substr($p['description'],0,120)) ?>... 💛</p>
    
    <div class="card-actions">
      <a class="btn-primary" href="detail_pet.php?id=<?= $p['id'] ?>">Detail</a>
      <?php if(is_logged()): ?>
        <a class="btn" href="adoption.php?pet_id=<?= $p['id'] ?>">Ajukan Adopsi</a>
      <?php else: ?>
        <a class="btn" href="login.php">Login</a>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>
</div>

<!-- SERVICES SECTION -->
<div class="section-title" style="margin-top:34px">
  <h2>Layanan Section</h2>
  <a href="services.php">Lihat semua layanan</a>
</div>

<div class="cards">
<?php foreach($services as $s): ?>
  <div class="card">
    <h3><?= htmlspecialchars($s['name']) ?></h3>
    <p class="small"><?= htmlspecialchars(substr($s['description'],0,140)) ?></p>
    <div style="margin-top:auto">
      <a class="btn-primary" href="booking.php?service_id=<?= $s['id'] ?>">Booking - Rp <?= number_format($s['price'] ?? 0,0,',','.') ?></a>
    </div>
  </div>
<?php endforeach; ?>
</div>


<!-- ⭐ TESTIMONI SECTION -->
<div style="text-align:center; margin-top:60px;">
    <h2 style="font-size:32px; font-weight:700; margin-bottom:6px;">
        What Pet Parents Say 🐶💬
    </h2>
    <p style="font-size:16px; color:#666; margin-top:0;">
        Trusted by thousands of happy pet owners 🐾✨
    </p>
</div>

<div class="testimonial-container">
    <button class="ts-btn left" onclick="moveSlide(-1)">❮</button>

    <div class="testimonial-slider" id="ts-slider">

        <?php foreach($reviews as $r): ?>
        <div class="testimonial-card">

            <img src="<?= $r['user_photo'] ? 'uploads/'.$r['user_photo'] : 'assets/img/user_placeholder.png' ?>" 
                 class="review-photo">

            <div class="stars"><?= str_repeat("★", intval($r['rating'])) ?></div>
            
            <p>"<?= htmlspecialchars($r['review_text']) ?>"</p>
            <strong>- <?= htmlspecialchars($r['user_name']) ?></strong>
        </div>
        <?php endforeach; ?>

    </div>

    <button class="ts-btn right" onclick="moveSlide(1)">❯</button>
</div>


<!-- ⭐ AUTO SLIDER SCRIPT -->
<script>
let index = 0;
const slider = document.getElementById("ts-slider");
const total = slider.children.length;

function moveSlide(dir) {
    index = (index + dir + total) % total;
    slider.style.transform = `translateX(-${index * 100}%)`;
}

setInterval(() => moveSlide(1), 4000);
</script>

<?php require_once 'includes/footer.php'; ?>
