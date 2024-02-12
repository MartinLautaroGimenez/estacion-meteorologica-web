<?php
session_start();
include('obtencion_datos.php');
include('graficos.php');

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.php');
    exit;
}
if ($_SESSION['name'] != 'martin' && $_SESSION['name'] != 'caro') {
    // Configura el código de estado HTTP 403
    header("HTTP/1.1 403 Unauthorized");
    exit(); // Asegura que no se ejecute más código después de mostrar la página de error
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Estacion_test</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="sidebar">
    <div class="logo-details">
      <img class= 'Imagen_logo' src='../assets/Hilets estaciones sin fondop.png'></img>
      <span class="logo_name">Hilets</span>
    </div>
    <ul class="nav-links">
        <li>
            <a href="#" class="active">
                <i class='bx bxs-grid-alt' ></i>
                <span class="links_name">Inicio</span>
            </a>
        </li>
        <li>
            <a href="datos.php">
                <i class='bx bx-list-ul' ></i>
                <span class="links_name">Datos</span>
            </a>
        </li>
        <li>
            <a href="analiticas.php">
                <i class='bx bxs-pie-chart-alt-2' ></i>
                <span class="links_name">Analíticas</span>
            </a>
        </li>
        <li>
            <a href="mi-cuenta.php">
                <i class='bx bxs-user-circle' ></i>
                <span class="links_name">Mi cuenta</span>
            </a>
        </li>
        <li>
            <a href="feedback.php">
                <i class='bx bxs-happy' ></i>
                <span class="links_name">Feedback</span>
            </a>
        </li>
        <li>
            <a href="solicitudes.php">
                <i class='bx bxs-check-circle' ></i>
                <span class="links_name">Solicitudes</span>
            </a>
        </li>
        <li class="log_out">
            <a href="../logout.php">
                <i class='bx bxs-log-out'></i>
                <span class="links_name">Salir</span>
            </a>
        </li>
    </ul>
</div>

<section class="home-section">
    <nav>
    <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard">Inicio</span>
      </div>
        <div class="profile-details">
            <td>Bienvenido/a <?=  $_SESSION['name'] ?> ☕</td>
        </div>
    </nav>

    <div class="home-content">
        <div class="overview-boxes">
            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Temperatura</div>
                    <?php echo $temp_html; ?>
                </div>
                <i class='bx bxs-thermometer dates temp'></i>
            </div>
            <div class="box">
                <div class="right-side">
                    <div class="box-topic">P. Atmosférica</div>
                    <?php echo $bp_html; ?>
                </div>
                <i class='bx bxs-cloud dates pressure' ></i>
            </div>
            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Altitud</div>
                    <?php echo $altitud_html; ?>
                </div>
                <i class='bx bxs-upvote dates altiud' ></i>
            </div>
            <div class="box">
                <div class="right-side">
                    <div class="box-topic">H. Relativa</div>
                    <?php echo $humedad_relativa_html; ?>
                </div>
                <i class='bx bxs-droplet dates humidity' ></i>
            </div>
        </div>

        <div class="sales-boxes">
            <div class="recent-sales box" style="width: 100% !important;">
                <div class="sales-details">
                    <ul class="details">
                    <?php echo generarGraficoTemperaturassemanales() ?>
                    </ul>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
<script>
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".sidebarBtn");
    sidebarBtn.onclick = function() {
    sidebar.classList.toggle("active");
    if(sidebar.classList.contains("active")){
    sidebarBtn.classList.replace("bx-menu" ,"bx-menu-alt-right");
    }else
    sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
    }
</script>
</body>
</html>