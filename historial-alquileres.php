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
        <th scope="col">Placa</th>
        <th scope="col">Tipo vehiculo</th>
        <th scope="col">Modelo vehiculo</th>
        <th scope="col">Fecha de salida</th>
        <th scope="col">Dias alquilado</th>
        <th scope="col">Semanas alquilado</th>
        <th scope="col">Precio cotizado</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $rents = current_rents($_SESSION["id"]);
    if (count($rents) > 0) {
        foreach ($rents as $rent) {
            $out_office = get_office_city($rent->out_office);
            $out_date = $rent->out_date->format("Y-m-d H:i:s");
            $plate = $rent->vehicle->plate;
            $model = $rent->vehicle->model;
            echo "
            <tr>
                <td>$out_office</td>
                <td>$plate</td>
                <td>$rent->type</td>
                <td>$model</td>
                <td>$out_date</td>
                <td>$rent->days_rented</td>
                <td>$rent->weeks_rented</td>
                <td>$rent->expected_price</td>
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
        <th scope="col">Placa</th>
        <th scope="col">Tipo vehiculo</th>
        <th scope="col">Modelo vehiculo</th>
        <th scope="col">Fecha de salida</th>
        <th scope="col">Fecha de llegada</th>
        <th scope="col">Dias alquilado</th>
        <th scope="col">Semanas alquilado</th>
        <th scope="col">Precio total pagado</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $rents = old_rents($_SESSION["id"]);
    if (count($rents) > 0) {
        foreach ($rents as $rent) {
            $out_office = get_office_city($rent->out_office);
            $in_office = get_office_city($rent->in_office);
            $out_date = $rent->out_date->format("Y-m-d H:i:s");
            $in_date = $rent->in_date->format("Y-m-d H:i:s");
            $plate = $rent->vehicle->plate;
            $model = $rent->vehicle->model;
            echo "
            <tr>
                <td>$out_office</td>
                <td>$in_office</td>
                <td>$plate</td>
                <td>$rent->type</td>
                <td>$model</td>
                <td>$out_date</td>
                <td>$in_date</td>
                <td>$rent->days_rented</td>
                <td>$rent->weeks_rented</td>
                <td>$rent->payed_price</td>
            </tr>";
        }
    }
    ?>

    </tbody>
</table>