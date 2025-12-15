<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

require_once '../config/db.php';

$id = $_GET['id'];

$pdo->prepare("DELETE FROM pets WHERE id=?")->execute([$id]);

header("Location: manage_pets.php");
exit;
?>
