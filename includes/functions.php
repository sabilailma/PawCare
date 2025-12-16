<?php
// includes/functions.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';


function is_logged() { return isset($_SESSION['user_id']); }
function is_admin() { return isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; }
function uid() { return $_SESSION['user_id'] ?? null; }
function flash($k,$v=null){ if($v===null){ $val = $_SESSION['flash'][$k] ?? null; unset($_SESSION['flash'][$k]); return $val; } $_SESSION['flash'][$k]=$v; }
function upload_image($f){
  $target_dir = __DIR__ . '/../assets/uploads/';
  if(!isset($f['tmp_name']) || !$f['tmp_name']) return null;
  $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
  if(!in_array($f['type'],$allowed)) return null;
  if($f['size'] > 5*1024*1024) return null;
  if(!is_dir($target_dir)) mkdir($target_dir,0755,true);
  $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
  $name = uniqid('img_',true).".".$ext;
  if(move_uploaded_file($f['tmp_name'],$target_dir.$name)) return "assets/uploads/".$name;
  return null;
}
function notify($user_id, $title, $message){
  global $pdo;
  $stmt = $pdo->prepare("INSERT INTO notifications (user_id,title,message) VALUES (?,?,?)");
  $stmt->execute([$user_id,$title,$message]);
}
function get_unread_count($user_id){
  global $pdo;
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id=? AND is_read=0");
  $stmt->execute([$user_id]);
  return $stmt->fetchColumn();
}
function mark_read($id){
  global $pdo;
  $stmt = $pdo->prepare("UPDATE notifications SET is_read=1 WHERE id=?");
  $stmt->execute([$id]);
}

function render_stars($rating) {
    $full = floor($rating);
    $half = ($rating - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;

    $html = '<div class="stars" style="margin:6px 0">';
    for ($i=0; $i<$full; $i++) $html .= '<span style="color:#ffbf00;font-size:18px;">★</span>';
    if ($half) $html .= '<span style="color:#ffbf00;font-size:18px;">☆</span>';
    for ($i=0; $i<$empty; $i++) $html .= '<span style="color:#ccc;font-size:18px;">☆</span>';
    $html .= '</div>';

    return $html;
}

