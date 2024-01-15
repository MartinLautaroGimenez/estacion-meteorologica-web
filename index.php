<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio De Sesion</title>
    <link rel="stylesheet" href="styles_login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="assets/Hilets estaciones sin fondop.png" type="image/x-icon">
    <script>rel='script.js'</script>
</head>

<body>
    <div class="login">
        <img src="assets/Hilets estaciones sin fondop.png" alt="logo" id="logo">
        <h1>Ingrese para ver sus estaciones</h1>
        <form action="autenticacion.php" method="post">
            <label for="username">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" name="username" placeholder="Usuario" id="username" required>
            <label for="password">
                <i class="fas fa-lock"></i>
            </label>
            <input type="password" name="password" placeholder="Contraseña" id="password" required>
            <div id="error-message">
                <?php
                if (isset($_GET['msg'])) {
                    $mensaje = $_GET['msg'];
                    if ($mensaje === 'missing_data') {
                        echo '<div class="error-message">Faltan datos. Por favor, complete todos los campos.</div>';
                    } elseif ($mensaje === 'incorrect_credentials') {
                        echo '<div class="error-message">Credenciales incorrectas. Por favor, inténtelo de nuevo.</div>';
                    }
                    if ($mensaje === 'logout_success') {
                        echo '<div class="sucess-message">Ha cerrado sesión correctamente</div>';
                    }
                }
                ?>
            </div>
            <input type="submit" value="Acceder">
        </form>
    </div>
</body>
</html>