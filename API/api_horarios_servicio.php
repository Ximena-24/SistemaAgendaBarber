<?php
include_once "../conexion.php";

$sql = "SELECT DiaSemana, HoraInicio, HoraFin, Status 
        FROM TBL_HorariosServicio
        WHERE Status = 1";

$stmt = sqlsrv_query($conn,$sql);

$dias = [
    "DOMINGO" => 0,
    "LUNES" => 1,
    "MARTES" => 2,
    "MIERCOLES" => 3,
    "JUEVES" => 4,
    "VIERNES" => 5,
    "SABADO" => 6
];

$business = [];
$hidden = [];

while($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){

    $dia = $dias[$row['DiaSemana']];

    if($row['Status']==1){

        $business[]=[
            "daysOfWeek"=>[$dia],
            "startTime"=>$row['HoraInicio']->format('H:i'),
            "endTime"=>$row['HoraFin']->format('H:i')
        ];

    }else{
        $hidden[]=$dia;
    }
}

echo json_encode([
    "business"=>$business,
    "hidden"=>$hidden
]);
