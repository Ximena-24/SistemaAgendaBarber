<?php
include_once "../conexion.php";

$fecha = $_GET['fecha'];

$diaSemana = strtoupper(strftime('%A', strtotime($fecha)));

$dias = [
    "MONDAY" => "LUNES",
    "TUESDAY" => "MARTES",
    "WEDNESDAY" => "MIERCOLES",
    "THURSDAY" => "JUEVES",
    "FRIDAY" => "VIERNES",
    "SATURDAY" => "SABADO",
    "SUNDAY" => "DOMINGO"
];

$diaSemana = $dias[$diaSemana];


$sqlHorario = "SELECT HoraInicio, HoraFin, HorarioComida
FROM TBL_HorariosServicio
WHERE DiaSemana = ?
AND Status = 1
";

$stmt = sqlsrv_query($conn, $sqlHorario, [$diaSemana]);
$horario = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

$eventos = [];

if ($horario) {

    // BLOQUE COMIDA
    if ($horario['HorarioComida']) {

        $horas = explode("-", $horario['HorarioComida']);

        $eventos[] = [
            "start" => $fecha . "T" . trim($horas[0]),
            "end" => $fecha . "T" . trim($horas[1]),
            "display" => "background",
            "color" => "#000000"
        ];
    }

    // CITAS EXISTENTES
    $sqlCitas = "SELECT HoraInicio, HoraFin
    FROM TBL_CitasReserbaciones
    WHERE FechaRecerbacion = ?
    AND Estado <> 'CANCELADA'
    ";

    $stmtCitas = sqlsrv_query($conn, $sqlCitas, [$fecha]);

    while ($row = sqlsrv_fetch_array($stmtCitas, SQLSRV_FETCH_ASSOC)) {

        $eventos[] = [
            "start" => $fecha . "T" . $row['HoraInicio']->format('H:i:s'),
            "end" => $fecha . "T" . $row['HoraFin']->format('H:i:s'),
            "color" => "#E63946"
        ];
    }
}

echo json_encode([
    "horario" => $horario,
    "eventos" => $eventos
]);
