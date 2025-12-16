<?php
require_once '../includes/functions.php';
if(!is_admin()) exit;

$id = intval($_POST['id']);
$pet_id = intval($_POST['pet_id']);
$action = $_POST['action'];

if($action=='approve'){
  $pdo->prepare("
    UPDATE adoption_requests SET status='approved' WHERE id=?
  ")->execute([$id]);

  $pdo->prepare("
    UPDATE pets SET status='adopted' WHERE id=?
  ")->execute([$pet_id]);

  $uid = $pdo->query("
    SELECT user_id FROM adoption_requests WHERE id=$id
  ")->fetchColumn();

  notify($uid,'Adopsi Disetujui','Selamat! Pengajuan adopsimu disetujui 🐾');
}

if($action=='reject'){
  $pdo->prepare("
    UPDATE adoption_requests SET status='rejected' WHERE id=?
  ")->execute([$id]);

  $pdo->prepare("
    UPDATE pets SET status='available' WHERE id=?
  ")->execute([$pet_id]);
}

header('Location: adoptions.php');
