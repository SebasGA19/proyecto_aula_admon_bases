<?php
include_once "nav_bar.php";
include_once "database.php";
?>
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Placa</th>
            <th scope="col">Modelo</th>
            <th scope="col">Nombre</th>
            <th scope="col">Color</th>
            <th scope="col">Precio</th>
            <th scope="col">Puertas</th>
            <th scope="col">Capacidad</th>
            <th scope="col">Descapotable</th><th scope="col">Motor</th><th scope="col">Precio_Semana</th><th scope="col">Precio_dia</th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach (available_vehicles() as $vehicle) {
        echo "
            <tr>
                <th scope=\"row\"></th>
                <td>$vehicle->placa</td>
                <td>$vehicle->modelo_id</td>
                <td>$vehicle->nombre</td>
                <td>$vehicle->color_id</td>
                <td>$vehicle->precio_referencia</td>
                <td>$vehicle->puertas</td>
                <td>$vehicle->capacidad</td>
                <td>$vehicle->descapotable</td>
                <td>$vehicle->motor</td>
                <td>$vehicle->precio_semana</td>
                <td>$vehicle->precio_dia</td>
            </tr>";
    }
    ?>

    </tbody>