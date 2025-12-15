<?php include 'includes/admin_header.php'; ?>
<?php require_once '../config/db.php'; ?>

<link rel="stylesheet" href="assets/css/admin.css">

<div class="admin-container">

    <h1>Dashboard Analytics</h1>
    <p class="welcome">Analisis aktivitas PawCare secara lengkap.</p>

    <?php
    $totalPets    = $pdo->query("SELECT COUNT(*) FROM pets")->fetchColumn();
    $totalUsers   = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $totalServices = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
    $totalReviews = $pdo->query("SELECT COUNT(*) FROM service_reviews")->fetchColumn();

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

    <!-- Stats Cards -->
    <div class="stats-grid">

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

    <!-- Charts -->
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
            borderColor: '#5b86e5',
            borderWidth: 1,
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
