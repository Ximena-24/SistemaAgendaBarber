<?php
include_once "../conexion.php";

$fecha = $_GET['fecha'];
$idServicio = $_GET['servicio'];

$sql = "
SELECT HoraInicio, HoraFin
FROM TBL_CitasReserbaciones
WHERE FechaRecerbacion = ?
AND Estado <> 'CANCELADA'
";

$params = [$fecha];

$stmt = sqlsrv_query($conn, $sql, $params);

$eventos = [];

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

    $eventos[] = [
        "start" => $fecha . "T" . $row['HoraInicio']->format('H:i:s'),
        "end"   => $fecha . "T" . $row['HoraFin']->format('H:i:s'),
        "color" => "#E63946"
    ];
}

echo json_encode($eventos);
