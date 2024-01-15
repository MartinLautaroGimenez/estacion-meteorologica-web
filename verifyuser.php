<?php
if ($_SESSION['name'] == '1234'){
    header("Location: logout.php");

}
if ($_SESSION['name'] == 'martin'){
    header("Location: /test/inicio.php");

}
