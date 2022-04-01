<?php
include_once "nav_bar.php";
include_once "database.php";
?>
<style>
    #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 50%;
    }

    #customers td, #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #customers tr:nth-child(even){background-color: #f2f2f2;}

    #customers tr:hover {background-color: #ddd;}

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #1abc9c;
        color: white;
    }
</style>
<table id="customers">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Placa</th>
            <th scope="col">Modelo</th>
            <th scope="col">Nombre</th>
            <th scope="col">Color</th>
            <th scope="col">Precio</th>
            <th scope="col">Puertas</th>
            <th scope="col">Capacidad</th>
            <th scope="col">Descapotable</th>
            <th scope="col">Motor</th>
            <th scope="col">Precio_Semana</th>
            <th scope="col">Precio_dia</th>
            <th scope="col">Alquilar</th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach (available_vehicles() as $vehicle) {
        echo "
            <tr>
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
                <td><a href='/alquilar_auto.php?id=$vehicle->inventario_id' class='blue-button'>Alquilar</td>
            </tr>";
    }
    ?>

    </tbody>