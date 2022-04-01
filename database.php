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

function available_vehicles(): ?array
{
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
        return $products;
    } catch (Exception $e) {
    }
    return null;
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

function exists(int $user_id): bool {
    $records = connect()->prepare('SELECT id FROM clientes WHERE id = :user_id');
    $records->bindParam(':user_id', $user_id);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
    if ($results) {
        return count($results) > 0;
    }
    return false;
}