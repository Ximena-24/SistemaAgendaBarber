<?php

$serverName = "XIMENA_SANTOS";

$connectionInfo = array(
    "Database" => "DB_AGENDA",
    "CharacterSet" => "UTF-8",
    "TrustServerCertificate" => true
);

$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn) {
    //echo "✅ Conexion a la BD exitosa";
} else {
   // echo "❌ No se conectó a la BD<br>";
    die(print_r(sqlsrv_errors(), true));
}

?>
