<?php
include_once "conexion.php";

$fecha = $_POST['fecha'];
$dia = strtoupper(strftime('%A', strtotime($fecha)));

$horario = $conn->prepare("
SELECT * FROM TBL_HorariosServicio
WHERE DiaNombre = :dia AND Activo = 1
");
$horario->execute([':dia'=>$dia]);
$h = $horario->fetch(PDO::FETCH_ASSOC);

if(!$h){
    echo json_encode([]);
    exit;
}

$inicio = strtotime($h['HoraInicio']);
$fin = strtotime($h['HoraFin']);

$comida = $h['HoraComida']; // 14:30 - 16:00
list($cIni,$cFin) = $comida ? explode(' - ', $comida) : [null,null];

$cIni = $cIni ? strtotime($cIni) : null;
$cFin = $cFin ? strtotime($cFin) : null;

$ocupadas = $conn->prepare("
SELECT Hora FROM TBL_Citas WHERE Fecha = :fecha
");
$ocupadas->execute([':fecha'=>$fecha]);
$bloqueadas = $ocupadas->fetchAll(PDO::FETCH_COLUMN);

$horasDisponibles = [];

for($t=$inicio; $t<$fin; $t+=1800){ // cada 30 min
    if($cIni && $t >= $cIni && $t < $cFin) continue;
    if(in_array(date('H:i',$t), $bloqueadas)) continue;
    $horasDisponibles[] = date('H:i',$t);
}

echo json_encode($horasDisponibles);
