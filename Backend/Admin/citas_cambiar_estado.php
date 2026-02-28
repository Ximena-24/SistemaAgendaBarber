<?php
include_once "../../conexion.php";

// Para que NO salgan warnings en HTML:
ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: application/json; charset=utf-8');

$idCita = (int)($_POST['idCita'] ?? 0);
$estado = strtoupper(trim($_POST['estado'] ?? ''));

$validos = ["PENDIENTE", "COMPLETADA", "CANCELADA"];
if ($idCita <= 0 || !in_array($estado, $validos, true)) {
  echo json_encode(["ok" => false, "msg" => "Datos inválidos"], JSON_UNESCAPED_UNICODE);
  exit;
}

// Actualiza estatus admin
$stmt = sqlsrv_query(
  $conn,
  "UPDATE dbo.TBL_CitasReserbaciones SET EstatusAdmin = ? WHERE IdCita = ?",
  [$estado, $idCita]
);

if ($stmt === false) {
  echo json_encode(["ok" => false, "msg" => "No se pudo actualizar el estatus"], JSON_UNESCAPED_UNICODE);
  exit;
}

// Traer datos para respuesta (sin problemas con date/time)
$stmt2 = sqlsrv_query($conn, "SELECT TOP 1
    AliasCliente,
    TelefonoCliente,
    NombreServicio,
    CONVERT(varchar(10), FechaRecerbacion, 23) AS Fecha,
    LEFT(CONVERT(varchar(16), HoraInicio, 114), 8) AS HoraInicio,
    LEFT(CONVERT(varchar(16), HoraFin, 114), 8) AS HoraFin
  FROM dbo.TBL_CitasReserbaciones
  WHERE IdCita = ?
", [$idCita]);

$row = ($stmt2) ? sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC) : null;

$alias    = $row['AliasCliente'] ?? '';
$telefono = $row['TelefonoCliente'] ?? '';
$servicio = $row['NombreServicio'] ?? '';
$fecha    = $row['Fecha'] ?? '';
$hi       = $row['HoraInicio'] ?? '';
$hf       = $row['HoraFin'] ?? '';

// Notificación
$titulo = ($estado === "COMPLETADA") ? "Cita completada" : (($estado === "CANCELADA") ? "Cita cancelada" : "Cita pendiente");
$tipo   = $estado;

$msg = "Cita #{$idCita} → {$estado} | {$alias} | {$servicio} | {$fecha} {$hi}-{$hf}";

sqlsrv_query(
  $conn,
  "INSERT INTO dbo.TBL_Notificaciones (Tipo, Titulo, Mensaje, RefId, Leida, Fecha)
   VALUES (?, ?, ?, ?, 0, GETDATE())",
  [$tipo, $titulo, $msg, $idCita]
);

echo json_encode([
  "ok" => true,
  "data" => [
    "idCita" => $idCita,
    "estado" => $estado,
    "alias" => $alias,
    "telefono" => $telefono,
    "servicio" => $servicio,
    "fecha" => $fecha,
    "horaInicio" => $hi,
    "horaFin" => $hf
  ]
], JSON_UNESCAPED_UNICODE);
exit;
