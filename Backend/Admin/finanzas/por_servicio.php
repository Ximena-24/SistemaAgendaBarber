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

$from = trim($_GET["from"] ?? "");
$to   = trim($_GET["to"] ?? ""); // exclusivo ideal

if ($from === "" || $to === "") {
    fail("Parámetros from/to requeridos");
}

$sql = "SELECT
  NombreServicio AS servicio,
  COUNT(*) AS cantidad,
  SUM(TRY_CONVERT(decimal(18,2), CostoServicio)) AS total
FROM dbo.TBL_CitasReserbaciones
WHERE EstatusAdmin='COMPLETADA'
  AND FechaRecerbacion >= ? AND FechaRecerbacion < ?
GROUP BY NombreServicio
ORDER BY total DESC;";

$stmt = sqlsrv_query($conn, $sql, [$from, $to]);
if (!$stmt) {
    fail("No se pudo agrupar por servicio");
}

$items = [];
while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $items[] = [
        "servicio" => (string)$r["servicio"],
        "cantidad" => intval($r["cantidad"] ?? 0),
        "total" => floatval($r["total"] ?? 0),
    ];
}

ok(["from" => $from, "to_exclusive" => $to, "items" => $items]);
