<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<head>
<meta charset="UTF-8">
<title>PawCare Admin</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="admin-content">

<style>
.chart-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

.chart-card {
    background: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
}

.chart-card h3 {
    text-align: center;
    margin-bottom: 15px;
    color: #444;
}
</style>

<style>
.btn-add {
    display: inline-block;
    background: #3A86FF;
    color: #fff;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    margin-bottom: 20px;
    transition: 0.2s;
}
.btn-add:hover { background:#2b6fd8; }

.pet-grid {
    margin-top: 20px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 22px;
}

.pet-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    transition: 0.3s;
}
.pet-card:hover {
    transform: translateY(-5px);
}

.pet-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.pet-info {
    padding: 15px;
}

.pet-info h3 {
    margin: 0;
    font-size: 20px;
}

.category {
    color: #777;
    margin-bottom: 10px;
}

.action-row {
    margin-top: 15px;
    display: flex;
    justify-content: space-between;
}

.btn-edit, .btn-delete {
    padding: 8px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
}

.btn-edit {
    background: #FFC300;
    color: #000;
}
.btn-delete {
    background: #FF3B3B;
    color: #fff;
}
</style>