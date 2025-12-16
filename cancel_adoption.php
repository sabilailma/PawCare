<?php
require_once 'includes/functions.php';
if(!is_logged()) exit;

$id = intval($_POST['id']);

$stmt = $pdo->prepare("
  UPDATE adoption_requests
  SET status='cancelled'
  WHERE id=? AND user_id=? AND status='pending'
");
$stmt->execute([$id, uid()]);

notify(uid(), 'Adopsi Dibatalkan', 'Pengajuan adopsi telah dibatalkan.');

header('Location: profile.php');
