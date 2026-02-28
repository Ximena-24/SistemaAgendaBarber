<?php
include_once "../../conexion.php";
header('Content-Type: application/json; charset=utf-8');

$idCita     = (int)($_POST['idCita'] ?? 0);
$fecha      = trim($_POST['fecha'] ?? '');
$horaInicio = trim($_POST['horaInicio'] ?? '');
$horaFin    = trim($_POST['horaFin'] ?? '');

if ($idCita <= 0 || $fecha === '' || $horaInicio === '' || $horaFin === '') {
    echo json_encode(["ok" => false, "msg" => "Datos incompletos"]);
    exit;
}

$sql = "UPDATE dbo.TBL_CitasReserbaciones
SET FechaRecerbacion = ?, HoraInicio = ?, HoraFin = ?
WHERE IdCita = ?
";
$stmt = sqlsrv_query($conn, $sql, [$fecha, $horaInicio, $horaFin, $idCita]);

if ($stmt === false) {
    echo json_encode(["ok" => false, "msg" => "No se pudo actualizar la cita"]);
    exit;
}

// Traemos datos para el WhatsApp del cliente
$stmt2 = sqlsrv_query($conn, "SELECT TOP 1 AliasCliente, TelefonoCliente, NombreServicio
  FROM dbo.TBL_CitasReserbaciones
  WHERE IdCita = ?
", [$idCita]);

$cliente = ["AliasCliente" => "", "TelefonoCliente" => "", "NombreServicio" => ""];
if ($stmt2 && ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC))) {
    $cliente["AliasCliente"] = (string)$row["AliasCliente"];
    $cliente["TelefonoCliente"] = (string)$row["TelefonoCliente"];
    $cliente["NombreServicio"] = (string)$row["NombreServicio"];
}

// Notificación 🔔
$mensajeNotif = "Se reprogramó la cita #{$idCita} a {$fecha} {$horaInicio} - {$horaFin}";
sqlsrv_query(
    $conn,
    "INSERT INTO dbo.TBL_Notificaciones (Tipo, Titulo, Mensaje, RefId, Leida, Fecha)
   VALUES (?, ?, ?, ?, 0, GETDATE())",
    ["CAMBIO_FECHA", "Cita reprogramada", $mensajeNotif, $idCita]
);

echo json_encode([
    "ok" => true,
    "data" => [
        "idCita" => $idCita,
        "alias" => $cliente["AliasCliente"],
        "telefono" => $cliente["TelefonoCliente"],
        "servicio" => $cliente["NombreServicio"],
        "fecha" => $fecha,
        "horaInicio" => $horaInicio,
        "horaFin" => $horaFin
    ]
], JSON_UNESCAPED_UNICODE);
