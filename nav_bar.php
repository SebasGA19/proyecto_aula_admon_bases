<?php
if (isset($_SESSION["id"])) {
    js_redirect("dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="topnav">
    <a class="active" href="/dashboard.php">Home</a>
    <a href="/alquilar_auto.php">Alquilar autos</a>
    <a href="/vehiculos.php">Ver autos disponibles</a>
    <a href="#Historial de alquileres">Historial de alquileres</a>
    <a href="/logout.php">Logout</a>
</div>


</body>
</html>