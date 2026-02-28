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

$range = strtolower(trim($_GET["range"] ?? "30d")); // 30d | 12m
$tz = new DateTimeZone("America/Mexico_City");
$now = new DateTime("now", $tz);

if ($range === "12m") {
    $start = (clone $now);
    $start->modify("first day of this month");
    $start->modify("-11 months");
    $startStr = $start->format("Y-m-d");
    $end = (clone $now);
    $end->modify("first day of next month"); // exclusivo
    $endStr = $end->format("Y-m-d");

    $sql = "SELECT
    CONVERT(varchar(7), FechaRecerbacion, 23) AS ym,
    SUM(TRY_CONVERT(decimal(18,2), CostoServicio)) AS total
  FROM dbo.TBL_CitasReserbaciones
  WHERE EstatusAdmin='COMPLETADA'
    AND FechaRecerbacion >= ? AND FechaRecerbacion < ?
  GROUP BY CONVERT(varchar(7), FechaRecerbacion, 23)
  ORDER BY ym;";

    $stmt = sqlsrv_query($conn, $sql, [$startStr, $endStr]);
    if (!$stmt) {
        fail("No se pudo generar serie (12m)");
    }

    $items = [];
    while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $items[] = ["label" => (string)$r["ym"], "total" => floatval($r["total"] ?? 0)];
    }

    ok(["range" => $range, "items" => $items, "start" => $startStr, "end_exclusive" => $endStr]);
}

// default 30d
$days = intval(preg_replace('/\D+/', '', $range));
if ($days <= 0) $days = 30;

$start = (clone $now);
$start->modify("-" . ($days - 1) . " days");
$startStr = $start->format("Y-m-d");

$end = (clone $now);
$end->modify("+1 day"); // exclusivo (incluye hoy)
$endStr = $end->format("Y-m-d");

$sql = "SELECT
  CONVERT(varchar(10), FechaRecerbacion, 23) AS ymd,
  SUM(TRY_CONVERT(decimal(18,2), CostoServicio)) AS total
FROM dbo.TBL_CitasReserbaciones
WHERE EstatusAdmin='COMPLETADA'
  AND FechaRecerbacion >= ? AND FechaRecerbacion < ?
GROUP BY CONVERT(varchar(10), FechaRecerbacion, 23)
ORDER BY ymd;";
$stmt = sqlsrv_query($conn, $sql, [$startStr, $endStr]);
if (!$stmt) {
    fail("No se pudo generar serie (días)");
}

$map = [];
while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $map[(string)$r["ymd"]] = floatval($r["total"] ?? 0);
}

// rellenar días faltantes con 0 (para que la gráfica se vea continua)
$items = [];
$cursor = new DateTime($startStr, $tz);
$endDt = new DateTime($endStr, $tz);
while ($cursor < $endDt) {
    $k = $cursor->format("Y-m-d");
    $items[] = ["label" => $k, "total" => floatval($map[$k] ?? 0)];
    $cursor->modify("+1 day");
}

ok(["range" => $range, "items" => $items, "start" => $startStr, "end_exclusive" => $endStr]);
