<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../conexion.php";

$tel = isset($_GET['telefono']) ? trim($_GET['telefono']) : '';
$tel = preg_replace('/\D+/', '', $tel);

$pais = isset($_GET['pais']) ? preg_replace('/\D+/', '', $_GET['pais']) : '';
// MX/USA usan 10 dígitos nacionales. Ajusta si quieres por país.
$len = 10;

if ($tel === '') {
    echo json_encode(["ok" => 0, "msg" => "Sin teléfono"]);
    exit;
}

// Si el usuario escribió más de 10, nos quedamos con los últimos 10
if (strlen($tel) > $len) {
    $tel = substr($tel, -$len);
}

$sql = "SELECT TOP 1 IdCliente, NombreCliente, AliasCliente, TelefonoCliente, CorreoCliente
        FROM TBL_DirectorioClientes
        WHERE Status=1
          AND RIGHT(TelefonoCliente, ?) = ?
        ORDER BY IdCliente DESC";

$stmt = sqlsrv_query($conn, $sql, [$len, $tel]);

if ($stmt === false) {
    echo json_encode(["ok" => 0, "msg" => "Error SQL", "err" => sqlsrv_errors()]);
    exit;
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$row) {
    echo json_encode(["ok" => 1, "found" => 0]);
    exit;
}

echo json_encode([
    "ok" => 1,
    "found" => 1,
    "cliente" => [
        "IdCliente" => $row["IdCliente"],
        "NombreCliente" => $row["NombreCliente"],
        "AliasCliente" => $row["AliasCliente"],
        "TelefonoCliente" => $row["TelefonoCliente"],
        "CorreoCliente" => $row["CorreoCliente"]
    ]
]);
