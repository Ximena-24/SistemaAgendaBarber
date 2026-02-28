<?php
include "../conexion.php";
session_start();

// Recoger datos del formulario
$idUsuario = isset($_POST['Usua_Log']) ? trim($_POST['Usua_Log']) : '';
$Password  = isset($_POST['Pass_Log']) ? trim($_POST['Pass_Log']) : '';

// Validaciones básicas
if ($idUsuario === '' || $Password === '') {
    header("Location: login.php?vacio=1");
    exit;
}

// Consulta para SQL Server usando tus campos reales
$query = "SELECT TOP 1 idUser, Usuario, [Contraseña], RollUser, Status 
          FROM TBL_UsuariosBarber 
          WHERE idUser = ? AND [Contraseña] = ? AND Status = 1";

$params = array($idUsuario, $Password);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$userData = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt);

if ($userData) {
    // Guardar en sesión
    $_SESSION['idUser']   = $userData['idUser'];
    $_SESSION['UserName'] = $userData['Usuario'];
    $_SESSION['Roll']     = $userData['RollUser'];
    $_SESSION['Autenticado'] = "SI";

    // Redirigir según el RollUser (Ajusta los números según tus roles)
    // Supongamos: 1 = Admin, 2 = Barbero, 3 = Recepción
    switch ($userData['RollUser']) {
        case 1:
            header("Location: ../Frontend/Admin/panel.php");
            exit;
        case 2:
            header("Location: ../Frontend/index.php");
            exit;
        default:
            header("Location: login.php?error_rol=1");
            exit;
    }
} else {
    // Si no coincide la contraseña, regresamos con una variable en la URL
    header("Location: login.php?error=1");
    exit;
}
