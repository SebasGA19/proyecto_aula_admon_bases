<?php
include_once "database.php";
if (isset($_SESSION["id"])) {
    if (!exists($_SESSION["id"])) {
        js_redirect("index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Responsive Navigation Bar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>


<div class="navbar">
    <div class="inner_navbar">
        <div class="logo">
            <a>Auto <span>Rental</span></a>
        </div>
        <div class="menu">
            <ul>
                <li><a id="button-dashboard" href="/dashboard.php">Home</a></li>
                <li><a id="button-vehicles" href="/vehiculos.php">Ver autos disponibles</a></li>
                <li><a id="button-rents" href="/historial-alquileres.php">Historial alquileres</a></li>
                <li><a id="button-logout" href="/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class="hamburger">
        <i class="fas fa-bars"></i>
    </div>
</div>


<script>
    var hamburger = document.querySelector(".hamburger");
    var menu = document.querySelector(".menu");

    hamburger.addEventListener("click", function () {
        menu.classList.toggle("active");
    })
    const uri = window.location.pathname;
    let button = document.getElementById("button-dashboard");
    switch (uri) {
        case "/dashboard.php":
            button = document.getElementById("button-dashboard");
            break;
        case "/vehiculos.php":
            button = document.getElementById("button-vehicles");
            break;
        case "/alquilar_auto.php":
            button = document.getElementById("button-vehicles");
            break;
        case "/historial-alquileres.php":
            button = document.getElementById("button-rents");
            break;
        case "/logout.php":
            button = document.getElementById("button-logout");
            break;
    }
    button.classList.add("active");
</script>

</body>
</html>