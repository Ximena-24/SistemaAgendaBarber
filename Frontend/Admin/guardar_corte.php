<?php
include_once "../../conexion.php"; // Ajusta la ruta a tu archivo de conexión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreCorte = $_POST['nombreCorte'];
    $foto = $_FILES['fotoCorte'];

    // 1. Definimos la ruta física (donde se guarda el archivo)
    // Usamos el separador de directorios correcto
    $directorioDestino = "../../Img/Galeria/";

    // 2. Validar que la carpeta exista, si no, crearla
    if (!is_dir($directorioDestino)) {
        mkdir($directorioDestino, 0777, true);
    }

    // 3. Limpiar el nombre del archivo para que sea corto (por el límite de 35 caracteres)
    $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
    // Usamos solo el timestamp para que sea único y corto
    $nombreArchivoFinal = time() . "." . $extension;
    $rutaCompleta = $directorioDestino . $nombreArchivoFinal;

    // 4. Intentar mover el archivo a la carpeta
    if (move_uploaded_file($foto['tmp_name'], $rutaCompleta)) {

        // 5. Insertar en la base de datos
        // Estatus = 1 (Activo)
        $sql = "INSERT INTO TBL_BarberGaleria (NombreCorte, RutaImagen, Estatus) VALUES (?, ?, ?)";
        $params = array($nombreCorte, $nombreArchivoFinal, 1);

        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt) {
            echo "<script>
                    alert('¡Corte guardado con éxito! ✂️');
                    window.location.href = 'http://localhost:8080/Agenda_Barber/Frontend/Admin/Galeria.php'; // Cambia a tu página principal
                  </script>";
        } else {
            // Si falla el insert, borramos la imagen que acabamos de subir para no dejar basura
            unlink($rutaCompleta);
            die(print_r(sqlsrv_errors(), true));
        }
    } else {
        echo "Error al subir la imagen al servidor.";
    }
}
