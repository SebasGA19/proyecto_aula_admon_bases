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
				<a href="#">Auto <span>Rental</span></a>
			</div>
			<div class="menu">
				<ul>
					<li><a href="/dashboard.php" class="active">Home</a></li>
					<li><a href="/vehiculos.php">Ver autos disponibles</a></li>
					<li><a href="/historial-alquileres.php">Historial alquileres</a></li>
					<li><a href="/logout.php">Logout</a></li>
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

	hamburger.addEventListener("click", function(){
		menu.classList.toggle("active");
	})
</script>

</body>
</html>