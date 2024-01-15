<?php
session_start();
// Credenciales de acceso a la base de datos
$DATABASE_HOST = '34.27.15.81';
$DATABASE_USER = 'authentication_system';
$DATABASE_PASS = 't0ZpZwXx5ICJzL7';
$DATABASE_NAME = 'Usuarios';
// Conexión a la base de datos
$conexion = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
// Verifica si hay un error en la conexión
if (mysqli_connect_error()) {
    exit('Fallo en la conexión de MySQL:' . mysqli_connect_error());
}

// Se valida si se ha enviado información con la función isset()
if (!isset($_POST['username'], $_POST['password'])) {
    // Si no hay datos, muestra un error y redirecciona con un mensaje
    header('Location: index.php?error=missing_data');
    exit;
}

// Evitar inyección SQL
if ($stmt = $conexion->prepare('SELECT Usuario, Contraseña FROM Clientes WHERE Usuario = ?')) {
    // Parámetros de enlace de la cadena s
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
}

// Se valida si lo ingresado coincide con la base de datos
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $password);
    $stmt->fetch();

    // Se confirma que la cuenta existe, ahora validamos la contraseña
    if ($_POST['password'] === $password) {
        // La conexión sería exitosa, se crea la sesión
        session_regenerate_id();
        $_SESSION['loggedin'] = true;
        $_SESSION['name'] = $_POST['username'];
        $_SESSION['id'] = $id;
        header('Location: test/inicio.php');
    } else {
        // Contraseña incorrecta
        header('Location: index.php?error=incorrect_credentials');
    }
} else {
    // Usuario incorrecto
    header('Location: index.php?error=incorrect_credentials');
}

$stmt->close();
?>