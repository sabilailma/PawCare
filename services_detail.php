<?php
require_once 'includes/functions.php';
require_once 'includes/header.php';

// Jika tidak ada ID → kembali
if(!isset($_GET['id'])) {
    header("Location: services.php");
    exit;
}

$id = (int)$_GET['id'];

// Cari layanan berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch();

// Jika layanan tidak ditemukan
if(!$service) {
    echo "<h2>Layanan tidak ditemukan</h2>";
    require_once 'includes/footer.php';
    exit;
}
?>

<section class="page-header">
    <h1><?= htmlspecialchars($service['name']) ?></h1>
</section>

<div class="service-detail-container">
    <div class="service-detail-box">
        <h2>Deskripsi Layanan</h2>
        <p><?= nl2br(htmlspecialchars($service['description'])) ?></p>

        <h3>Harga</h3>
        <p class="price">Rp <?= number_format($service['price'], 0, ',', '.') ?></p>

        <div class="buttons">
            <a href="services.php" class="btn">← Kembali</a>

            <?php if(is_logged()): ?>
                <a class="btn-primary" href="booking.php?service_id=<?= $service['id'] ?>">
                    Booking Sekarang
                </a>
            <?php else: ?>
                <a class="btn-primary" href="login.php">Login untuk Booking</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// kirim data PHP ke JS
const testimonials = <?= json_encode($reviews, JSON_UNESCAPED_UNICODE) ?>;

let index = 0;
const card = document.getElementById("testimonialCard");

function loadTestimonial() {
    const t = testimonials[index];
    const stars = "★".repeat(t.rating);

    card.innerHTML = `
        <img src="${t.photo ?? 'assets/img/default_user.png'}" class="testi-photo">
        <div class="stars">${stars}</div>
        <p>"${t.review_text}"</p>
        <p><strong>- ${t.user_name}</strong></p>
    `;
}

document.getElementById("nextBtn").onclick = () => {
    index = (index + 1) % testimonials.length;
    loadTestimonial();
};

document.getElementById("prevBtn").onclick = () => {
    index = (index - 1 + testimonials.length) % testimonials.length;
    loadTestimonial();
};

setInterval(() => {
    index = (index + 1) % testimonials.length;
    loadTestimonial();
}, 4000);

loadTestimonial();
</script>

<?php require_once 'includes/footer.php'; ?>
