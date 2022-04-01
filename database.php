<?php

function js_redirect(string $target_url)
{
    echo "<script>window.location.href = '$target_url'</script>";
    exit;
}

function salt(): string {
    return "AntonioJuanSebastian";
}

function connect(): PDO {
    $database_host = "172.30.242.24:3306";
    $database = "laboratorio_3";
    $database_username = "root";
    $database_password = "password";

    return new PDO("mysql:host=$database_host;dbname=$database;", $database_username, $database_password);
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
): bool {
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
    } catch (Exception $e) {}
    return false;
}

function login(string $username, string $password): ?int {
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