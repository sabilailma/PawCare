<?php
require_once 'includes/functions.php';
require_once 'includes/header.php';

// ID layanan
$id = intval($_GET['id'] ?? 0);
if(!$id) {
    header("Location: services.php");
    exit;
}

// Ambil data layanan
$stmt = $pdo->prepare("SELECT * FROM services WHERE id=?");
$stmt->execute([$id]);
$service = $stmt->fetch();
if(!$service) {
    echo "<h2>Layanan tidak ditemukan</h2>";
    require_once 'includes/footer.php';
    exit;
}

// Ambil review user
$stmt = $pdo->prepare("
    SELECT r.*, u.name AS user_name, u.photo AS user_photo
    FROM service_reviews r
    JOIN users u ON u.id = r.user_id
    WHERE r.service_id=?
    ORDER BY r.created_at DESC
");
$stmt->execute([$id]);
$reviews = $stmt->fetchAll();
?>

<section class="page-header">
    <h1><?= htmlspecialchars($service['name']) ?></h1>
</section>

<div class="service-detail-container">
    <div class="service-detail-box">
        <h2>Deskripsi Layanan</h2>
        <p><?= nl2br(htmlspecialchars($service['description'])) ?></p>

        <h3>Harga</h3>
        <p class="price">Rp <?= number_format($service['price'],0,',','.') ?></p>

        <div class="buttons">
            <a href="services.php" class="btn">← Kembali</a>
            <?php if(is_logged()): ?>
                <a class="btn-primary" href="booking.php?service_id=<?= $service['id'] ?>">Booking Sekarang</a>
            <?php else: ?>
                <a class="btn-primary" href="login.php">Login untuk Booking</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- FORM REVIEW -->
<?php if(is_logged()): ?>
<div class="review-form">
    <h3>Tinggalkan Review</h3>
    <form action="submit_review.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
        <label>Rating (1-5)</label>
        <input type="number" name="rating" min="1" max="5" required>
        <label>Review</label>
        <textarea name="review_text" required></textarea>
        <label>Upload Foto (opsional)</label>
        <input type="file" name="photo">
        <button type="submit">Kirim Review</button>
    </form>
</div>
<?php endif; ?>

<!-- TESTIMONIAL USER -->
<h3>Review Pengguna</h3>
<div class="testimonial-container">
    <button id="prevBtn" class="btn">❮</button>
    <div id="testimonialCard" class="testimonial-card"></div>
    <button id="nextBtn" class="btn">❯</button>
</div>

<script>
const testimonials = <?= json_encode($reviews, JSON_UNESCAPED_UNICODE) ?>;
let index = 0;
const card = document.getElementById("testimonialCard");

function loadTestimonial() {
    if(testimonials.length === 0){
        card.innerHTML = "<p>Belum ada review.</p>";
        return;
    }
    const t = testimonials[index];
    const stars = "★".repeat(parseInt(t.rating));
    const photo = t.user_photo ? "uploads/"+t.user_photo : "assets/img/default_user.png";

    card.innerHTML = `
        <img src="${photo}" class="testi-photo" width="80" style="border-radius:50%;">
        <div class="stars">${stars}</div>
        <p>"${t.review_text}"</p>
        <p><strong>- ${t.user_name}</strong></p>
        ${t.admin_reply ? `<div style="background:#f0f0f0;padding:6px;border-radius:6px;">
            <strong>Balasan Admin:</strong>
            <p>${t.admin_reply}</p>
        </div>` : ''}
    `;
}

document.getElementById("nextBtn").onclick = () => { index = (index+1)%testimonials.length; loadTestimonial(); };
document.getElementById("prevBtn").onclick = () => { index = (index-1+testimonials.length)%testimonials.length; loadTestimonial(); };

setInterval(() => { index = (index+1)%testimonials.length; loadTestimonial(); }, 4000);

loadTestimonial();
</script>

<?php require_once 'includes/footer.php'; ?>
