<?php
include 'includes/admin_header.php';
require_once '../config/db.php';
?>

<link rel="stylesheet" href="assets/css/admin.css">

<?php
/* =========================
   DATA DASHBOARD
========================= */

// booking
$totalBookings     = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$pendingBookings   = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status='pending'")->fetchColumn();
$confirmedBookings = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status='confirmed'")->fetchColumn();
$completedBookings = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status='completed'")->fetchColumn();

// lama (tetap dipakai chart)
$totalPets      = $pdo->query("SELECT COUNT(*) FROM pets")->fetchColumn();
$totalUsers     = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalServices  = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
$totalReviews   = $pdo->query("SELECT COUNT(*) FROM service_reviews")->fetchColumn();

$totalPendingAdoptions = $pdo->query("
    SELECT COUNT(*) 
    FROM adoption_requests 
    WHERE status='pending'
")->fetchColumn();


$petData = $pdo->query("
    SELECT type, COUNT(*) AS total
    FROM pets
    GROUP BY type
")->fetchAll(PDO::FETCH_ASSOC);

$avgRating = $pdo->query("
    SELECT ROUND(AVG(rating),1)
    FROM service_reviews
")->fetchColumn() ?? 0;
?>

<div class="admin-container">

    <h1>Dashboard PawCare</h1>
    <p class="welcome">Ringkasan aktivitas PawCare.</p>

    <!-- =========================
         STAT CARDS
    ========================= -->
    <div class="stats-grid">

        <!-- BOOKING (SATU KOTAK) -->
        <div class="stats-card booking-card">
            <div class="stats-title">Booking</div>
            <div class="stats-value"><?= $totalBookings ?></div>

            <div class="booking-status">
                <span class="pending">Pending: <?= $pendingBookings ?></span>
                <span class="confirmed">Confirmed: <?= $confirmedBookings ?></span>
                <span class="completed">Completed: <?= $completedBookings ?></span>
            </div>
        </div>

        <a href="adoptions.php?status=pending" style="text-decoration:none">
             <div class="stats-card">
                <div class="stats-title">Pending Adopsi</div>
                <div class="stats-value"><?= $totalPendingAdoptions ?></div>
             </div>
        </a>


        <div class="stats-card">
            <div class="stats-title">Total Hewan</div>
            <div class="stats-value"><?= $totalPets ?></div>
        </div>

        <div class="stats-card">
            <div class="stats-title">Total Layanan</div>
            <div class="stats-value"><?= $totalServices ?></div>
        </div>

        <div class="stats-card">
            <div class="stats-title">Total Pengguna</div>
            <div class="stats-value"><?= $totalUsers ?></div>
        </div>

        <div class="stats-card">
            <div class="stats-title">Total Review</div>
            <div class="stats-value"><?= $totalReviews ?></div>
        </div>

        <div class="stats-card">
            <div class="stats-title">Rating Rata-Rata</div>
            <div class="stats-value"><?= $avgRating ?></div>
        </div>

    </div>

    <!-- =========================
         CHART (LAMA – TETAP)
    ========================= -->
    <div class="chart-grid">

        <div class="chart-card">
            <h3>Hewan per Jenis</h3>
            <canvas id="petChart"></canvas>
        </div>

        <div class="chart-card">
            <h3>Tren Rating</h3>
            <canvas id="reviewChart"></canvas>
        </div>

    </div>

</div>

<script>
const petLabels = <?= json_encode(array_column($petData, 'type')) ?>;
const petValues = <?= json_encode(array_column($petData, 'total')) ?>;
const avgRating = <?= $avgRating ?>;

new Chart(document.getElementById('petChart'), {
    type: 'bar',
    data: {
        labels: petLabels,
        datasets: [{
            label: 'Total Hewan',
            data: petValues,
            backgroundColor: '#36d1dc',
            borderRadius: 8
        }]
    }
});

new Chart(document.getElementById('reviewChart'), {
    type: 'doughnut',
    data: {
        labels: ['Rating', 'Sisa'],
        datasets: [{
            data: [avgRating, 5 - avgRating],
            backgroundColor: ['#5b86e5', '#e9ecef']
        }]
    }
});
</script>

<?php include 'includes/admin_footer.php'; ?>
