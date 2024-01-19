<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['loggedin'])) {
    header('HTTP/1.1 404 Unauthorized');
    exit;
}
if ($_SESSION['name'] != 'martin') {
    // Configura el código de estado HTTP 403
    header("HTTP/1.1 404 Unauthorized'");
    exit(); // Asegura que no se ejecute más código después de mostrar la página de error
}

// Ruta al archivo
$archivo = '/var/www/html/datos.xlsx'; // Actualiza la ruta según tu ubicación

// Verifica si el archivo existe
if (!file_exists($archivo)) {
    // Envía una solicitud HTTP para generar el archivo Excel
    $url_exportar_excel = 'http://34.27.15.81:1880/exportar-excel'; // Actualiza la URL según tu configuración
    $ch = curl_init($url_exportar_excel);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Verifica si la solicitud fue exitosa (código 200)
    if ($http_code != 200) {
        header('HTTP/1.1 ' . $http_code . ' Error');
        exit;
    }

    // Verifica nuevamente si el archivo ahora existe
    if (!file_exists($archivo)) {
        header('HTTP/1.1 404 Not Found');
        exit;
    }
}

// Configura los encabezados para la descarga
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="datos.xlsx"');

// Lee y envía el contenido del archivo
readfile($archivo);
?>
