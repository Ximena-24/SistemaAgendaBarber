<?php
include_once "../../conexion.php";
header('Content-Type: application/json; charset=utf-8');

$idsRaw = $_POST["ids"] ?? ""; // "1,2,3"
$all = isset($_POST["all"]) ? (int)$_POST["all"] : 0;

if ($all === 1) {
  $sql = "UPDATE dbo.TBL_Notificaciones SET Leida = 1 WHERE Leida = 0";
  $stmt = sqlsrv_query($conn, $sql);
  if ($stmt === false) {
    echo json_encode(["ok"=>false, "msg"=>"No se pudo marcar como leídas"]);
    exit;
  }
  echo json_encode(["ok"=>true], JSON_UNESCAPED_UNICODE);
  exit;
}

$ids = array_filter(array_map('trim', explode(',', $idsRaw)));
$clean = [];
foreach ($ids as $id) {
  if (ctype_digit($id)) $clean[] = (int)$id;
}

if (!count($clean)) {
  echo json_encode(["ok"=>false, "msg"=>"Sin IDs válidos"]);
  exit;
}

// armamos IN (?, ?, ?)
$placeholders = implode(',', array_fill(0, count($clean), '?'));
$sql = "UPDATE dbo.TBL_Notificaciones SET Leida = 1 WHERE IdNotif IN ($placeholders)";
$stmt = sqlsrv_query($conn, $sql, $clean);

if ($stmt === false) {
  echo json_encode(["ok"=>false, "msg"=>"No se pudo marcar como leídas"]);
  exit;
}

echo json_encode(["ok"=>true], JSON_UNESCAPED_UNICODE);
