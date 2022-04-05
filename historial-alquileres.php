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

    #customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #customers tr:hover {
        background-color: #ddd;
    }

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #1abc9c;
        color: white;
    }
</style>
<h2>Alquileres actuales</h2>
<table id="customers">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Sucursal de salida</th>
        <th scope="col">Nombre del vehiculo</th>
        <th scope="col">Fecha de salida</th>
        <th scope="col">Dias alquilado</th>
        <th scope="col">Semanas alquilado</th>
        <th scope="col">Precio cotizado</th>
        <th scope="col">Placa</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $rents = current_rents($_SESSION["id"]);
    if (count($rents) > 0) {
        foreach ($rents as $rent) {
            $salida = get_sucursal_city($rent->sucursal_salida);
            echo "
            <tr>
                <td>$salida</td>
                <td>$rent->nombre_vehiculo</td>
                <td>$rent->fecha_salida</td>
                <td>$rent->dias_alquilado</td>
                <td>$rent->semanas_alquilado</td>
                <td>$rent->valor_cotizado</td>
                <td>$rent->placa</td>
            </tr>";
        }
    }
    ?>

    </tbody>
</table>

<h2>Alquileres completados</h2>
<table id="customers">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Sucursal de salida</th>
        <th scope="col">Sucursal de llegada</th>
        <th scope="col">Nombre del vehiculo</th>
        <th scope="col">Fecha de salida</th>
        <th scope="col">Fecha de llegada</th>
        <th scope="col">Dias alquilado</th>
        <th scope="col">Semanas alquilado</th>
        <th scope="col">Precio total pagado</th>
        <th scope="col">Placa</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $rents = old_rents($_SESSION["id"]);
    if (count($rents) > 0) {
        foreach ($rents as $rent) {
            $salida = get_sucursal_city($rent->sucursal_salida);
            $entrega = get_sucursal_city($rent->sucursal_entrega);
            echo "
            <tr>
                <td>$salida</td>
                <td>$entrega</td>
                <td>$rent->nombre_vehiculo</td>
                <td>$rent->fecha_salida</td>
                <td>$rent->fecha_llegada</td>
                <td>$rent->dias_alquilado</td>
                <td>$rent->semanas_alquilado</td>
                <td>$rent->valor_pagado</td>
                <td>$rent->placa</td>
            </tr>";
        }
    }
    ?>

    </tbody>
</table>