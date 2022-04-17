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
            <th scope="col">Ciudad</th>
            <th scope="col">Modelo</th>
            <th scope="col">Tipo</th>
            <th scope="col">Color</th>
            <th scope="col">Precio dia</th>
            <th scope="col">Precio semana</th>
            <th scope="col">Puertas</th>
            <th scope="col">Capacidad</th>
            <th scope="col">Descapotable</th>
            <th scope="col">Motor</th>
            <th scope="col">Alquilar</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $vehicles = available_vehicles();
    if (count($vehicles) > 0) {
        foreach ($vehicles as $vehicle) {
            echo "
            <tr>
                <td>$vehicle->plate</td>
                <td>$vehicle->city</td>
                <td>$vehicle->model</td>
                <td>$vehicle->type</td>
                <td>$vehicle->color</td>
                <td>$vehicle->day_price</td>
                <td>$vehicle->week_price</td>
                <td>$vehicle->doors</td>
                <td>$vehicle->capacity</td>
                <td>$vehicle->convertible</td>
                <td>$vehicle->motor</td>
                <td><a href='/alquilar_auto.php?id=$vehicle->inventory_id' class='blue-button'>Alquilar</td>
            </tr>";
        }
    }
    ?>

    </tbody>
</table>