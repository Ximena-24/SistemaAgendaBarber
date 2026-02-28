<?php
include_once "../conexion.php";

$idServicio = isset($_GET['servicio']) ? (int)$_GET['servicio'] : 0;
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

// 1) Duración del servicio en minutos (TimpoServicio: "30 MIN" / "1 HRS")
$sql = "SELECT TimpoServicio FROM TBL_BarberServicios WHERE IdServ = ?";
$stmt = sqlsrv_query($conn, $sql, [$idServicio]);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

$texto = strtoupper(trim($row['TimpoServicio'] ?? '30 MIN'));
$duracion = 30;
if (strpos($texto, 'MIN') !== false) $duracion = (int)preg_replace('/\D+/', '', $texto);
if (strpos($texto, 'HRS') !== false) $duracion = (int)preg_replace('/\D+/', '', $texto) * 60;

// 2) Sacar horario del día desde tu tabla TBL_HorariosServicio
$dowEng = strtoupper(date('l', strtotime($fecha)));
$map = [
    "MONDAY" => "LUNES",
    "TUESDAY" => "MARTES",
    "WEDNESDAY" => "MIERCOLES",
    "THURSDAY" => "JUEVES",
    "FRIDAY" => "VIERNES",
    "SATURDAY" => "SABADO",
    "SUNDAY" => "DOMINGO"
];
$dia = $map[$dowEng];

$sqlH = "SELECT HoraInicio, HoraFin, Status FROM TBL_HorariosServicio WHERE DiaSemana = ?";
$stmtH = sqlsrv_query($conn, $sqlH, [$dia]);
$h = sqlsrv_fetch_array($stmtH, SQLSRV_FETCH_ASSOC);

if (!$h || (int)$h['Status'] === 0) {
    echo json_encode(["duracion" => $duracion, "horas" => []]);
    exit;
}

$horaInicio = $h['HoraInicio']->format('H:i:s');
$horaFin = $h['HoraFin']->format('H:i:s');

// 3) Generar slots base (Booksy style): 15 o 30 (recomendado 15 para servicios 10/20/45)
$base = 15; // 👈 cambia a 30 si lo quieres más “cuadrado”
$startTs = strtotime("$fecha $horaInicio");
$endTs   = strtotime("$fecha $horaFin");

// 4) Probar cada slot llamando tu SP_VALIDAR_CITA (fecha, horainicio, horafin)
$horasDisponibles = [];

for ($t = $startTs; $t + ($duracion * 60) <= $endTs; $t += ($base * 60)) {

    $hi = date('H:i:s', $t);
    $hf = date('H:i:s', $t + ($duracion * 60));

    $sqlVal = "EXEC SP_VALIDAR_CITA ?, ?, ?";
    $val = sqlsrv_query($conn, $sqlVal, [$fecha, $hi, $hf]);
    $r = sqlsrv_fetch_array($val, SQLSRV_FETCH_ASSOC);

    if ($r && (int)$r['Valido'] === 1) {
        $horasDisponibles[] = [
            "hora" => substr($hi, 0, 5),
            "horaFin" => substr($hf, 0, 5)
        ];
    }
}

echo json_encode([
    "duracion" => $duracion,
    "base" => $base,
    "horas" => $horasDisponibles
]);
