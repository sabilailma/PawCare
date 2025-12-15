<?php
require '../config/db.php';

if (!isset($_GET['id'])) {
  header("Location: manage_services.php");
  exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
$stmt->execute([$id]);

header("Location: manage_services.php");
exit;
