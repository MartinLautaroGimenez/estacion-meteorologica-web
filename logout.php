<?php
session_start();

// Cerrar la sesión
session_destroy();

// Redirigir a la página de inicio con un mensaje
header("Location: index.php?msg=logout_success");
exit;
?>
