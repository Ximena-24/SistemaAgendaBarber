<?php
include_once "../../conexion.php";
header('Content-Type: application/json; charset=utf-8');

$soloNoLeidas = isset($_GET['unread']) ? (int)$_GET['unread'] : 0;
$top = isset($_GET['top']) ? (int)$_GET['top'] : 50;
if ($top <= 0 || $top > 200) $top = 50;

$where = $soloNoLeidas ? "WHERE Leida = 0" : "";

$sql = "SELECT TOP ($top) IdNotif, Tipo, Titulo, Mensaje, RefId, Leida, Fecha
FROM dbo.TBL_Notificaciones
$where ORDER BY Fecha DESC, IdNotif DESC";

$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
  echo json_encode(["ok"=>false, "msg"=>"No se pudieron cargar notificaciones"]);
  exit;
}

$rows = [];
$unreadCount = 0;

while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  $leida = (int)$r["Leida"];
  if ($leida === 0) $unreadCount++;

  $fecha = $r["Fecha"];
  $fechaTxt = "";
  if (is_object($fecha)) $fechaTxt = $fecha->format("Y-m-d H:i");
  else $fechaTxt = substr((string)$fecha, 0, 16);

  $rows[] = [
    "IdNotif" => (int)$r["IdNotif"],
    "Tipo" => (string)$r["Tipo"],
    "Titulo" => (string)$r["Titulo"],
    "Mensaje" => (string)$r["Mensaje"],
    "RefId" => $r["RefId"] === null ? null : (string)$r["RefId"],
    "Leida" => $leida,
    "Fecha" => $fechaTxt
  ];
}

echo json_encode([
  "ok" => true,
  "unread" => $unreadCount,
  "items" => $rows
], JSON_UNESCAPED_UNICODE);
