<?php
require_once 'includes/functions.php';

if (!is_logged()) {
    header('Location: login.php');
    exit;
}

// ambil data layanan
$services = $pdo->query("SELECT * FROM services")->fetchAll();

// pets adopsi (boleh kosong / tidak dipakai)
$user_pets = $pdo->query("SELECT * FROM pets")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ambil & sanitasi data
    $service_id = $_POST['service_id'] ?? null;
    $type       = $_POST['type'] ?? null;
    $pet_id     = !empty($_POST['pet_id']) ? $_POST['pet_id'] : null;
    $date       = $_POST['date'] ?? null;
    $time       = $_POST['time'] ?? null;
    $notes      = $_POST['notes'] ?? '';

    // validasi minimal
    if ($service_id && $type && $date && $time) {

        $stmt = $pdo->prepare("
            INSERT INTO bookings
            (user_id, service_id, pet_id, type, booking_date, booking_time, status, notes)
            VALUES (?,?,?,?,?,?,?,?)
        ");

        $stmt->execute([
            uid(),
            $service_id,
            $pet_id,
            $type,
            $date,
            $time,
            'pending',
            $notes
        ]);

        notify(uid(), 'Booking dibuat', 'Booking Anda berhasil dibuat.');
        header('Location: profile.php');
        exit;

    } else {
        $error = "Mohon lengkapi semua data yang wajib diisi.";
    }
}

require_once 'includes/header.php';
?>

<div class="form">
    <h2>Booking Layanan</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">

        <label>Pilih Layanan</label>
        <select class="input" name="service_id" required>
            <option value="">- Pilih Layanan -</option>
            <?php foreach ($services as $s): ?>
                <option value="<?= $s['id'] ?>">
                    <?= htmlspecialchars($s['name']) ?> - Rp <?= number_format($s['price'], 0, ',', '.') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Type Hewan</label>
        <select class="input" name="type" required>
            <option value="">- Pilih Type -</option>
            <option>Dog</option>
            <option>Cat</option>
            <option>Rodent</option>
            <option>Bird</option>
            <option>Reptile</option>
            <option>Aquatic</option>
            <option>Other</option>
        </select>

        <label>Tanggal</label>
        <input class="input" type="date" name="date" required>

        <label>Waktu</label>
        <input class="input" type="time" name="time" required>

        <label>Catatan</label>
        <textarea class="input" name="notes" placeholder="Catatan tambahan (opsional)"></textarea>

        <button class="btn-primary">Buat Booking</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
