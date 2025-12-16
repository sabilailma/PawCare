<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $booking_id = $_POST['booking_id'];
    $status     = $_POST['status'];

    // Ambil data booking + user
    $stmt = $pdo->prepare("
        SELECT 
            b.*, 
            u.email, 
            u.name
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        WHERE b.id = ?
    ");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch();

    if (!$booking) {
        header('Location: bookings.php');
        exit;
    }

    // Update status booking
    $pdo->prepare("
        UPDATE bookings 
        SET status = ?
        WHERE id = ?
    ")->execute([$status, $booking_id]);

    // =========================
    // NOTIFIKASI + EMAIL
    // =========================
    if ($status === 'confirmed') {

        // INSERT NOTIFICATION
        $stmtNotif = $pdo->prepare("
            INSERT INTO notifications (user_id, title, message, is_read)
            VALUES (?, ?, ?, 0)
        ");
        $stmtNotif->execute([
            $booking['user_id'],
            'Booking Dikonfirmasi',
            'Booking layanan PawCare Anda telah dikonfirmasi. Silakan datang sesuai jadwal.'
        ]);

        // EMAIL USER
        $to = $booking['email'];
        $subject = 'Booking PawCare Dikonfirmasi';
        $message = "
Halo {$booking['name']},

Booking layanan PawCare Anda telah DIKONFIRMASI ✅

Tanggal : {$booking['booking_date']}
Waktu   : {$booking['booking_time']}
Jenis   : {$booking['type']}

Terima kasih telah menggunakan PawCare 🐾
";
        $headers = "From: PawCare <no-reply@pawcare.test>";

        @mail($to, $subject, $message, $headers);
    }

    header('Location: bookings.php');
    exit;
}


<link rel="stylesheet" href="assets/css/admin.css">