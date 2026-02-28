<?php
include_once "../../conexion.php";
header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT COUNT(1) AS Total FROM dbo.TBL_Notificaciones WHERE Leida = 0";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    echo json_encode(["ok" => false, "unread" => 0]);
    exit;
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$unread = (int)($row["Total"] ?? 0);

echo json_encode(["ok" => true, "unread" => $unread], JSON_UNESCAPED_UNICODE);
