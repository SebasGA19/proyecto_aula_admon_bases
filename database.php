<?php

session_start();

function js_redirect(string $target_url)
{
    echo "<script>window.location.href = '$target_url'</script>";
    exit;
}

function salt(): string
{
    return "AntonioJuanSebastian";
}

function connect(): PDO
{
    $database_host = "172.30.242.24:3306";
    $database = "laboratorio_3";
    $database_username = "root";
    $database_password = "password";

    return new PDO("mysql:host=$database_host;dbname=$database;", $database_username, $database_password);
}

function rent_vehicle(
    int   $inventario_id,
    int   $id_cliente,
    float $numero_semanas,
    float $numero_dias,
): bool
{
    try {
        $records = connect()->prepare('CALL alquilar_vehiculo(:inventario_id, :id_cliente, :numero_semanas, :numero_dias)');
        $records->bindParam(':inventario_id', $inventario_id);
        $records->bindParam(':id_cliente', $id_cliente);
        $records->bindParam(':numero_semanas', $numero_semanas);
        $records->bindParam(':numero_dias', $numero_dias);
        $records->execute();
        return true;
    } catch (Exception $e) {
        echo $e;
    }
    return false;
}

class Vehiculo
{
    public ?int $inventario_id = null;
    public ?int $sucursales_id = null;
    public ?string $ciudad = null;
    public ?int $vehiculos_id = null;
    public ?string $placa = null;
    public ?int $modelo_id = null;
    public ?string $nombre = null;
    public ?int $color_id = null;
    public ?float $precio_referencia = null;
    public ?string $puertas = null;
    public ?string $capacidad = null;
    public ?string $descapotable = null;
    public ?string $motor = null;
    public ?float $precio_semana = null;
    public ?float $precio_dia = null;

    public function __construct(
        ?int    $inventario_id,
        ?int    $sucursales_id,
        ?string $ciudad,
        ?int    $vehiculos_id,
        ?string $placa,
        ?int    $modelo_id,
        ?string $nombre,
        ?int    $color_id,
        ?float  $precio_referencia,
        ?string $puertas,
        ?string $capacidad,
        ?string $descapotable,
        ?string $motor,
        ?float  $precio_semana,
        ?float  $precio_dia
    )
    {
        $this->inventario_id = $inventario_id;
        $this->sucursales_id = $sucursales_id;
        $this->ciudad = $ciudad;
        $this->vehiculos_id = $vehiculos_id;
        $this->placa = $placa;
        $this->modelo_id = $modelo_id;
        $this->nombre = $nombre;
        $this->color_id = $color_id;
        $this->precio_referencia = $precio_referencia;
        $this->puertas = $puertas;
        $this->capacidad = $capacidad;
        $this->descapotable = $descapotable;
        $this->motor = $motor;
        $this->precio_semana = $precio_semana;
        $this->precio_dia = $precio_dia;
    }
}

class Rent
{
    public ?int $id = null;
    public ?int $clientes_id = null;
    public ?int $empleados_id = null;
    public ?int $sucursal_salida = null;
    public ?int $sucursal_entrega = null;
    public ?int $inventario_id = null;
    public ?string $nombre_vehiculo = null;
    public ?string $fecha_salida = null;
    public ?string $fecha_llegada_esperada = null;
    public ?string $fecha_llegada = null;
    public ?float $precio_semana_final = null;
    public ?float $precio_dia_final = null;
    public ?int $semanas_alquilado = null;
    public ?float $dias_alquilado = null;
    public ?float $valor_cotizado = null;
    public ?float $valor_pagado = null;

    public function __construct(
        int    $id,
        int    $clientes_id,
        int    $empleados_id,
        int    $sucursal_salida,
        int    $sucursal_entrega,
        int    $inventario_id,
        string $nombre_vehiculo,
        string $fecha_salida,
        string $fecha_llegada_esperada,
        string $fecha_llegada,
        float  $precio_semana_final,
        float  $precio_dia_final,
        int    $semanas_alquilado,
        float  $dias_alquilado,
        float  $valor_cotizado,
        float  $valor_pagado
    )
    {
        $this->id = $id;
        $this->clientes_id = $clientes_id;
        $this->empleados_id = $empleados_id;
        $this->sucursal_salida = $sucursal_salida;
        $this->sucursal_entrega = $sucursal_entrega;
        $this->inventario_id = $inventario_id;
        $this->nombre_vehiculo = $nombre_vehiculo;
        $this->fecha_salida = $fecha_salida;
        $this->fecha_llegada_esperada = $fecha_llegada_esperada;
        $this->fecha_llegada = $fecha_llegada;
        $this->precio_semana_final = $precio_semana_final;
        $this->precio_dia_final = $precio_dia_final;
        $this->semanas_alquilado = $semanas_alquilado;
        $this->dias_alquilado = $dias_alquilado;
        $this->valor_cotizado = $valor_cotizado;
        $this->valor_pagado = $valor_pagado;
    }
}

function get_sucursal_city(int $id): ?string
{
    $records = connect()->prepare('SELECT ciudad FROM sucursales WHERE id = :id');
    $records->bindParam(':id', $id);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
    if (count($results) > 0) {
        return $results["ciudad"];
    }
    return null;
}

function current_rents(int $user_id): array
{
    $products = array();
    try {
        $records = connect()->prepare('
SELECT alquileres.id AS id,
       clientes_id,
       empleados_id,
       sucursal_salida,
       sucursal_entrega,
       inventario_id,
       vehiculos.nombre AS nombre_vehiculo,
       fecha_salida,
       fecha_llegada_esperada,
       fecha_llegada,
       precio_semana_final,
       precio_dia_final,
       semanas_alquilado,
       dias_alquilado,
       valor_cotizado,
       valor_pagado
FROM alquileres,
     vehiculos,
     inventario
WHERE
    fecha_llegada IS NULL
    AND inventario.id = alquileres.inventario_id
    AND vehiculos.id = inventario.vehiculos_id
    AND clientes_id = :user_id');
        $records->bindParam(":user_id", $user_id);
        $records->execute();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $products[] = new Rent(
                $row["id"],
                $row["clientes_id"],
                $row["empleados_id"],
                $row["sucursal_salida"],
                -1,
                $row["inventario_id"],
                $row["nombre_vehiculo"],
                $row["fecha_salida"],
                $row["fecha_llegada_esperada"],
                "NUNCA",
                $row["precio_semana_final"],
                $row["precio_dia_final"],
                $row["semanas_alquilado"],
                $row["dias_alquilado"],
                $row["valor_cotizado"],
                0,
            );
        }
    } catch (Exception $e) {
    }
    return $products;
}

function old_rents(int $user_id): array
{
    $products = array();
    try {
        $records = connect()->prepare('
SELECT
    id,
    clientes_id,
    empleados_id,
    sucursal_salida,
    sucursal_entrega,
    sucursal_entrega,
    inventario_id,
    nombre_vehiculo,
    fecha_salida,
    fecha_llegada_esperada,
    fecha_llegada,
    precio_semana_final,
    precio_dia_final,
    semanas_alquilado,
    dias_alquilado,
    valor_cotizado,
    valor_pagado
FROM
    historial_de_alquileres
WHERE
      clientes_id = :user_id');
        $records->bindParam(":user_id", $user_id);
        $records->execute();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $products[] = new Rent(
                $row["id"],
                $row["clientes_id"],
                $row["empleados_id"],
                $row["sucursal_salida"],
                $row["sucursal_entrega"],
                $row["inventario_id"],
                $row["nombre_vehiculo"],
                $row["fecha_salida"],
                $row["fecha_llegada_esperada"],
                $row["fecha_llegada"],
                $row["precio_semana_final"],
                $row["precio_dia_final"],
                $row["semanas_alquilado"],
                $row["dias_alquilado"],
                $row["valor_cotizado"],
                $row["valor_pagado"]
            );
        }
    } catch (Exception $e) {
    }
    return $products;
}

function available_vehicles(): array
{
    $products = array();
    try {
        $records = connect()->prepare('
SELECT
    inventario_id,
    sucursales_id,
    ciudad,
    vehiculos_id,
    placa,
    modelo_id,
    nombre,
    color_id,
    precio_referencia,
    puertas,
    capacidad,
    descapotable,
    motor,
    precio_semana,
    precio_dia
FROM
    vehiculos_disponibles');
        $records->execute();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $products[] = new Vehiculo(
                $row["inventario_id"],
                $row["sucursales_id"],
                $row["ciudad"],
                $row["vehiculos_id"],
                $row["placa"],
                $row["modelo_id"],
                $row["nombre"],
                $row["color_id"],
                $row["precio_referencia"],
                $row["puertas"],
                $row["capacidad"],
                $row["descapotable"],
                $row["motor"],
                $row["precio_semana"],
                $row["precio_dia"]
            );
        }
    } catch (Exception $e) {
    }
    return $products;
}

function register_client(
    string $ciudad_residencia,
    string $cedula,
    string $nombres,
    string $apellidos,
    string $direccion,
    string $telefono_celular,
    string $correo,
    string $contrasena,
    string $confirm_password
): bool
{
    if ($contrasena !== $confirm_password) {
        return false;
    }
    try {
        $records = connect()->prepare('CALL registrar_cliente(:ciudad_residencia, :cedula, :nombres, :apellidos, :direccion, :telefono_celular, :correo, :contrasena)');
        $records->bindParam(':ciudad_residencia', $ciudad_residencia);
        $records->bindParam(':cedula', $cedula);
        $records->bindParam(':nombres', $nombres);
        $records->bindParam(':apellidos', $apellidos);
        $records->bindParam(':direccion', $direccion);
        $records->bindParam(':telefono_celular', $telefono_celular);
        $records->bindParam(':correo', $correo);
        $password_hash = crypt($contrasena, salt());
        $records->bindParam(':contrasena', $password_hash);
        $records->execute();
        return true;
    } catch (Exception $e) {
    }
    return false;
}

function login(string $username, string $password): ?int
{
    $crypt_password = crypt($password, salt());
    $records = connect()->prepare('SELECT login_cliente(:username, :crypt_password) AS user_id');
    $records->bindParam(':username', $username);
    $records->bindParam(':crypt_password', $crypt_password);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
    if (count($results) > 0) {
        return $results["user_id"];
    }
    return null;
}

function exists(int $user_id): bool
{
    $records = connect()->prepare('SELECT id FROM clientes WHERE id = :user_id');
    $records->bindParam(':user_id', $user_id);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
    if ($results) {
        return count($results) > 0;
    }
    return false;
}