<?php
include_once "../../../conexion.php";
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(0);

function ok($data)
{
    echo json_encode(["ok" => true] + $data, JSON_UNESCAPED_UNICODE);
    exit;
}
function fail($msg)
{
    echo json_encode(["ok" => false, "msg" => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}

$top = intval($_GET["top"] ?? 20);
if ($top <= 0) $top = 20;
if ($top > 200) $top = 200;

$sql = "SELECT TOP ($top)
  IdCita,
  AliasCliente,
  NombreServicio,
  TRY_CONVERT(decimal(18,2), CostoServicio) AS Total,
  CONVERT(varchar(10), FechaRecerbacion, 23) AS Fecha,
  LEFT(CONVERT(varchar(16), HoraInicio, 114), 5) AS Hora
FROM dbo.TBL_CitasReserbaciones
WHERE EstatusAdmin='COMPLETADA'
ORDER BY FechaRecerbacion DESC, HoraInicio DESC;
";

$stmt = sqlsrv_query($conn, $sql);
if (!$stmt) {
    fail("No se pudo cargar la tabla");
}

$items = [];
while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $items[] = [
        "idCita" => intval($r["IdCita"]),
        "cliente" => (string)$r["AliasCliente"],
        "servicio" => (string)$r["NombreServicio"],
        "total" => floatval($r["Total"] ?? 0),
        "fecha" => (string)$r["Fecha"],
        "hora" => (string)$r["Hora"],
    ];
}
ok(["items" => $items]);
