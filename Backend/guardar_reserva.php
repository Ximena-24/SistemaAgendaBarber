<?php
include_once "../conexion.php";

/**
 * CONFIGURACIÓN DEL ADMINISTRADOR
 * Asegúrate de que el número tenga el código de país (52 para México)
 */
$miTelefonoAdmin = "524651207469"; // TELEFONO CELULAR DE WICHO CAMBIAR EN DADO OCACION

// 1. CAPTURA DE DATOS
$idServicio = isset($_POST['idServicio']) ? (int)$_POST['idServicio'] : 0;
$fechaHora  = isset($_POST['fecha_hora']) ? trim($_POST['fecha_hora']) : '';


$paisCode = isset($_POST['PaisCode']) ? preg_replace('/\D+/', '', $_POST['PaisCode']) : '';
$telLocal = isset($_POST['TelefonoCliente']) ? preg_replace('/\D+/', '', $_POST['TelefonoCliente']) : '';

$tel = $telLocal;

// Si el usuario ya escribió con código (ej: 521234...), NO lo duplicamos.
// Si el usuario escribió solo local (ej: 4654567456), le agregamos el prefijo elegido.
if ($paisCode !== '' && $telLocal !== '') {
    if (strpos($telLocal, $paisCode) === 0) {
        $tel = $telLocal;             // ya trae prefijo
    } else {
        $tel = $paisCode . $telLocal; // lo armamos con prefijo
    }
}


$nom   = isset($_POST['NombreCliente']) ? trim($_POST['NombreCliente']) : '';
$alias = isset($_POST['AliasCliente']) ? trim($_POST['AliasCliente']) : '';
$nombreServicio = isset($_POST['nombreServicio']) ? trim($_POST['nombreServicio']) : '';
$precioServicio = isset($_POST['precioServicio']) ? trim($_POST['precioServicio']) : '';

$mail  = isset($_POST['CorreoCliente']) ? trim($_POST['CorreoCliente']) : null;

// 2. VALIDACIONES PREVIAS
if ($idServicio <= 0 || $fechaHora === '' || strlen($tel) < 8 || strlen($tel) > 15) {
    imprimirAlerta('error', 'Datos Incompletos', 'Por favor verifica el teléfono y el servicio seleccionado.');
}

// 3. PREPARACIÓN DE FECHA Y HORA
$fecha = substr($fechaHora, 0, 10);
$hora  = substr($fechaHora, 11, 8);
if (strlen($hora) === 5) $hora .= ":00";

// 4. EJECUCIÓN DEL PROCEDIMIENTO ALMACENADO
$sql = "EXEC SP_RESERVAR_CITA ?, ?, ?, ?, ?, ?, ?, ?";
$params = [$tel, $nom, $alias, $mail, $fecha, $hora, $idServicio, null];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    $msgError = str_replace("'", "", $errors[0]['message']);
    imprimirAlerta('error', 'Error en Base de Datos', $msgError);
}

$res = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$res) {
    imprimirAlerta('error', 'Error', 'No hubo respuesta del servidor al guardar la cita.');
}

// 5. LÓGICA DE RESPUESTA FINAL
if ((int)$res['Valido'] === 1) {

    // Armamos el mensaje para WhatsApp con PRECIO y NOMBRE CORRECTO
    $textMsg = "💈 *LA MEXICO BARBER STUDIO* 💈\n\n";
    $textMsg .= "¡Hola! Confirmo mi nueva cita:\n";
    $textMsg .= "👤 *Cliente:* " . $nom . " (" . $alias . ")\n";
    $textMsg .= "📅 *Fecha:* " . $fecha . "\n";
    $textMsg .= "⏰ *Hora:* " . $hora . "\n";
    $textMsg .= "✂️ *Servicio:* " . $nombreServicio . "\n";
    $textMsg .= "💰 *Precio:* $" . $precioServicio . ".00\n\n";
    $textMsg .= "_¡Nos vemos pronto!_";

    $urlWhatsApp = "https://wa.me/" . $miTelefonoAdmin . "?text=" . urlencode($textMsg);

    imprimirAlertaExito($res['Mensaje'], $urlWhatsApp);
} else {
    imprimirAlerta('warning', 'No disponible', $res['Mensaje']);
}

/**
 * FUNCIONES PARA MOSTRAR ALERTAS ELEGANTES
 */

function imprimirAlerta($tipo, $titulo, $mensaje)
{
    $color = ($tipo === 'warning') ? '#DAA520' : '#E63946';
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap' rel='stylesheet'>
        <style>body { font-family: 'Montserrat', sans-serif; background-color: #001D3D; }</style>
    </head>
    <body>
        <script>
            Swal.fire({
                title: '{$titulo}',
                text: '{$mensaje}',
                icon: '{$tipo}',
                confirmButtonColor: '{$color}',
                background: '#1a1a1a',
                color: '#fff'
            }).then(() => { window.history.back(); });
        </script>
    </body>
    </html>";
    exit;
}

function imprimirAlertaExito($mensaje, $urlWhatsApp)
{
    $urlIndex = "http://localhost:8080/Agenda_Barber/Frontend/index.php";
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap' rel='stylesheet'>
        <style>body { font-family: 'Montserrat', sans-serif; background-color: #001D3D; }</style>
    </head>
    <body>
        <script>
            Swal.fire({
                title: '¡Cita Confirmada!',
                html: '{$mensaje}<br><br><b>¿Deseas enviar tu confirmación al barbero por WhatsApp?</b>',
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: '📱 Enviar WhatsApp',
                cancelButtonText: 'Finalizar',
                confirmButtonColor: '#25D366',
                cancelButtonColor: '#0077B6',
                background: '#1a1a1a',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open('{$urlWhatsApp}', '_blank'); 
                }
                window.location.href = '{$urlIndex}';
            });
        </script>
    </body>
    </html>";
    exit;
}
