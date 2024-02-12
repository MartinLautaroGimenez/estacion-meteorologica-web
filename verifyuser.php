<?php
session_start();

// Resto de tu código...

if ($_SESSION['name'] == 'pepito'){
    header("Location: index.php");

}
if ($_SESSION['name'] == 'martin'){
    header("Location: /test/inicio.php");
    
}

if ($_SESSION['name'] == 'caro'){
    header("Location: /test/inicio.php");
    
}
