<?php
include_once "../../conexion.php";
header('Content-Type: application/json; charset=utf-8');

$start = $_GET['start'] ?? null; // FullCalendar manda ISO (YYYY-MM-DDTHH:mm:ssZ)
$end   = $_GET['end'] ?? null;

if (!$start || !$end) {
    echo json_encode([]);
    exit;
}

// Normalizamos a solo fecha (YYYY-MM-DD)
$startDate = substr($start, 0, 10);
$endDate   = substr($end, 0, 10);

// Traemos citas por rango de fechas
$sql = "SELECT
    IdCita,
    IDCli,
    AliasCliente,
    TelefonoCliente,
    FechaRecerbacion,
    HoraInicio,
    HoraFin,
    IdServicio,
    NombreServicio,
    TiempoServicio,
    CostoServicio,
    EstatusAdmin,
    Comentarios,
    FechaCreacion
FROM dbo.TBL_CitasReserbaciones
WHERE FechaRecerbacion >= ? AND FechaRecerbacion < ?
ORDER BY FechaRecerbacion, HoraInicio
";

// OJO: FullCalendar manda end como exclusivo; aquí lo mantenemos exclusivo sumando 1 día si quieres.
// Para mantenerlo simple: le pasamos endDate tal cual, pero mejor hacer end exclusivo:
$endExclusive = $endDate; // si tu consulta no trae el último día, lo ajustamos desde JS (en panel.php lo ajusto)

$stmt = sqlsrv_query($conn, $sql, [$startDate, $endExclusive]);

if ($stmt === false) {
    echo json_encode([]);
    exit;
}

function fmtDate($v)
{
    if (is_object($v)) return $v->format('Y-m-d');
    $s = (string)$v;
    return substr($s, 0, 10);
}
function fmtTime($v)
{
    if (is_object($v)) return $v->format('H:i:s');
    $s = (string)$v;
    // a veces viene "HH:MM:SS.0000000"
    return substr($s, 0, 8);
}

$events = [];

while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

    $fecha = fmtDate($r['FechaRecerbacion']);
    $hIni  = fmtTime($r['HoraInicio']);
    $hFin  = fmtTime($r['HoraFin']);

    $status = strtoupper(trim((string)($r['EstatusAdmin'] ?? '')));
    if ($status === '') $status = 'PENDIENTE';

    // Colores por estatus (puedes cambiarlos después)
    $color = '#29dbec'; // PENDIENTE
    if ($status === 'COMPLETADA') $color = '#02ff0a';
    if ($status === 'CANCELADA')  $color = '#ff0707';

    $title = trim(($r['AliasCliente'] ?? '') . " • " . ($r['NombreServicio'] ?? ''));

    $events[] = [
        "id"    => (string)$r['IdCita'],
        "title" => $title,
        "start" => $fecha . "T" . $hIni,
        "end"   => $fecha . "T" . $hFin,
        "color" => $color,
        "extendedProps" => [
            "IdCita"          => (int)$r['IdCita'],
            "IDCli"           => $r['IDCli'],
            "AliasCliente"    => $r['AliasCliente'],
            "TelefonoCliente" => $r['TelefonoCliente'],
            "Fecha"           => $fecha,
            "HoraInicio"      => $hIni,
            "HoraFin"         => $hFin,
            "IdServicio"      => $r['IdServicio'],
            "NombreServicio"  => $r['NombreServicio'],
            "TiempoServicio"  => $r['TiempoServicio'],
            "CostoServicio"   => $r['CostoServicio'],
            "EstatusAdmin"    => $status,
            "Comentarios"     => $r['Comentarios'],
        ]
    ];
}

echo json_encode($events, JSON_UNESCAPED_UNICODE);
