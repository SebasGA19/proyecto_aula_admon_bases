<?php

session_start();

function js_redirect(string $target_url)
{
    echo "<script>window.location.href = '$target_url'</script>";
    exit;
}

function connect(): PDO
{
    $database_host = getenv("DB_HOST");
    $database = getenv("DB_SCHEMA");
    $database_username = getenv("DB_USER");
    $database_password = getenv("DB_PASSWORD");

    return new PDO("mysql:host=$database_host;dbname=$database;", $database_username, $database_password);
}

function rent_vehicle(
    int $inventory_id,
    int $client_id,
    int $days_rented,
    int $weeks_rented,
): bool
{
    try {
        $records = connect()->prepare('CALL rent_vehicle(:inventory_id, :client_id, :days_rented, :weeks_rented)');
        $records->bindParam(':inventory_id', $inventory_id);
        $records->bindParam(':client_id', $client_id);
        $records->bindParam(':days_rented', $days_rented);
        $records->bindParam(':weeks_rented', $weeks_rented);
        $records->execute();
        return true;
    } catch (Exception $e) {
    }
    return false;
}

class Vehicle
{
    public int $inventory_id;
    public int $office_id;
    public string $city;
    public int $vehicle_id;
    public string $plate;
    public string $model;
    public string $type;
    public string $color;
    public string $doors;
    public string $capacity;
    public string $convertible;
    public string $motor;
    public int $day_price;
    public int $week_price;

    public function __construct(
        int    $v_inventory_id,
        int    $v_office_id,
        string $v_city,
        int    $v_vehicle_id,
        string $v_plate,
        string $v_model,
        string $v_type,
        string $v_color,
        string $v_doors,
        string $v_capacity,
        string $v_convertible,
        string $v_motor,
        int    $v_day_price,
        int    $v_week_price
    )
    {
        $this->inventory_id = $v_inventory_id;
        $this->office_id = $v_office_id;
        $this->city = $v_city;
        $this->vehicle_id = $v_vehicle_id;
        $this->plate = $v_plate;
        $this->model = $v_model;
        $this->type = $v_type;
        $this->color = $v_color;
        $this->doors = $v_doors;
        $this->capacity = $v_capacity;
        $this->convertible = $v_convertible;
        $this->motor = $v_motor;
        $this->day_price = $v_day_price;
        $this->week_price = $v_week_price;
    }

    /**
     * @throws Exception
     */
    public static function loadFromId(
        int $id
    ): Vehicle
    {
        $records = connect()->prepare('
SELECT
    inventory.id AS inventory_id,
    inventory.offices_id AS office_id,
    offices.city AS city,
    inventory.vehicles_id AS vehicle_id,
    inventory.plate AS plate,
    vehicles.model AS model,
    vehicles.type AS type,
    vehicles.color AS color,
    vehicles.doors AS doors,
    vehicles.capacity AS capacity,
    vehicles.convertible AS convertible,
    vehicles.motor AS motor,
    vehicles.day_price AS day_price,
    vehicles.week_price AS week_price
FROM
    inventory,
    vehicles,
    offices
WHERE inventory.id = :id
    AND inventory.vehicles_id = vehicles.id
    AND inventory.offices_id = offices.id
LIMIT 1');
        $records->bindParam(':id', $id);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
        if ($results) {
            return new Vehicle(
                $results["inventory_id"],
                $results["office_id"],
                $results["city"],
                $results["vehicle_id"],
                $results["plate"],
                $results["model"],
                $results["type"],
                $results["color"],
                $results["doors"],
                $results["capacity"],
                $results["convertible"],
                $results["motor"],
                $results["day_price"],
                $results["week_price"]
            );
        } else {
            throw new Exception("Inventory entry not found");
        }
    }
}

class Rent
{
    public int $id;
    public int $clients_id;
    public int $employees_id;
    public int $out_office;
    public int $in_office;
    public int $inventory_id;
    public Vehicle $vehicle;
    public string $type;
    public DateTime $out_date;
    public DateTime $expected_in_date;
    public DateTime $in_date;
    public int $final_day_price;
    public int $final_week_price;
    public int $days_rented;
    public int $weeks_rented;
    public int $expected_price;
    public int $payed_price;

    /**
     * @throws Exception
     */
    public function __construct(
        int      $id,
        int      $clients_id,
        int      $employees_id,
        int      $out_office,
        int      $in_office,
        int      $inventory_id,
        string   $type,
        DateTime $out_date,
        DateTime $expected_in_date,
        DateTime $in_date,
        int      $final_day_price,
        int      $final_week_price,
        int      $days_rented,
        int      $weeks_rented,
        int      $expected_price,
        int      $payed_price
    )
    {
        $this->id = $id;
        $this->clients_id = $clients_id;
        $this->employees_id = $employees_id;
        $this->out_office = $out_office;
        $this->in_office = $in_office;
        $this->inventory_id = $inventory_id;
        $this->vehicle = Vehicle::loadFromId($inventory_id);
        $this->type = $type;
        $this->out_date = $out_date;
        $this->expected_in_date = $expected_in_date;
        $this->in_date = $in_date;
        $this->final_day_price = $final_day_price;
        $this->final_week_price = $final_week_price;
        $this->days_rented = $days_rented;
        $this->weeks_rented = $weeks_rented;
        $this->expected_price = $expected_price;
        $this->payed_price = $payed_price;
    }
}

function get_office_city(int $id): ?string
{
    $records = connect()->prepare('SELECT city FROM offices WHERE id = :id');
    $records->bindParam(':id', $id);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
    if (count($results) > 0) {
        return $results["city"];
    }
    return null;
}

/**
 * @return Rent[]
 */
function current_rents(int $user_id): array
{
    $products = array();
    try {
        $records = connect()->prepare('
SELECT vehicle_rents.id AS vehicle_rents_id,
       vehicle_rents.clients_id AS clients_id,
       vehicle_rents.employees_id AS employees_id,
       vehicle_rents.out_office AS out_office,
       vehicle_rents.out_office AS out_office,
       vehicle_rents.inventory_id AS inventory_id,
       vehicles.type AS type,
       vehicle_rents.out_date AS out_date,
       vehicle_rents.expected_in_date AS expected_in_date,
       vehicle_rents.in_date AS in_date,
       vehicle_rents.final_day_price AS final_day_price,
       vehicle_rents.final_week_price AS final_week_price,
       vehicle_rents.days_rented AS days_rented,
       vehicle_rents.weeks_rented AS weeks_rented,
       vehicle_rents.expected_price AS expected_price,
       vehicle_rents.payed_price AS payed_price
FROM vehicle_rents,
     vehicles,
     inventory
WHERE
    vehicle_rents.in_date IS NULL
    AND inventory.id = vehicle_rents.inventory_id
    AND vehicles.id = inventory.vehicles_id
    AND vehicle_rents.clients_id = :user_id');
        $records->bindParam(":user_id", $user_id);
        $records->execute();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $products[] = new Rent(
                $row["vehicle_rents_id"],
                $row["clients_id"],
                $row["employees_id"],
                $row["out_office"],
                $row["out_office"],
                $row["inventory_id"],
                $row["type"],
                DateTime::createFromFormat('Y-m-d H:i:s', $row["out_date"]),
                DateTime::createFromFormat('Y-m-d H:i:s', $row["expected_in_date"]),
                DateTime::createFromFormat('Y-m-d H:i:s', "2000-12-12 12:12:12"),
                $row["final_day_price"],
                $row["final_week_price"],
                $row["days_rented"],
                $row["weeks_rented"],
                $row["expected_price"],
                0
            );
        }
    } catch (Exception $e) {
    }
    return $products;
}

/**
 * @return Rent[]
 */
function old_rents(int $user_id): array
{
    $products = array();
    try {
        $records = connect()->prepare('
SELECT
    id,
    clients_id,
    employees_id,
    out_office,
    in_office,
    inventory_id,
    type,
    out_date,
    expected_in_date,
    in_date,
    final_day_price,
    final_week_price,
    days_rented,
    weeks_rented,
    expected_price,
    payed_price
FROM
    completed_rents
WHERE
      clients_id = :user_id');
        $records->bindParam(":user_id", $user_id);
        $records->execute();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $products[] = new Rent(
                $row["id"],
                $row["clients_id"],
                $row["employees_id"],
                $row["out_office"],
                $row["in_office"],
                $row["inventory_id"],
                $row["type"],
                DateTime::createFromFormat('Y-m-d H:i:s', $row["out_date"]),
                DateTime::createFromFormat('Y-m-d H:i:s', $row["expected_in_date"]),
                DateTime::createFromFormat('Y-m-d H:i:s', $row["in_date"]),
                $row["final_day_price"],
                $row["final_week_price"],
                $row["days_rented"],
                $row["weeks_rented"],
                $row["expected_price"],
                $row["payed_price"]
            );
        }
    } catch (Exception $e) {
    }
    return $products;
}

/**
 * @return Vehicle[]
 */
function available_vehicles(): array
{
    $products = array();
    try {
        $records = connect()->prepare('
SELECT
    inventory_id,
    office_id,
    city,
    vehicle_id,
    plate,
    model,
    type,
    color,
    doors,
    capacity,
    convertible,
    motor,
    day_price,
    week_price
FROM
    available_vehicles');
        $records->execute();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $products[] = new Vehicle(
                $row["inventory_id"],
                $row["office_id"],
                $row["city"],
                $row["vehicle_id"],
                $row["plate"],
                $row["model"],
                $row["type"],
                $row["color"],
                $row["doors"],
                $row["capacity"],
                $row["convertible"],
                $row["motor"],
                $row["day_price"],
                $row["week_price"]
            );
        }
    } catch (Exception $e) {
    }
    return $products;
}

function register_client(
    string $personal_id,
    string $complete_name,
    string $address,
    string $phone_number,
    string $email,
    string $password,
    string $confirm_password
): bool
{
    if ($password !== $confirm_password) {
        return false;
    }
    try {
        $records = connect()->prepare('CALL register_client( :personal_id, :complete_name, :address, :phone_number, :email, :password);');
        $records->bindParam(':personal_id', $personal_id);
        $records->bindParam(':complete_name', $complete_name);
        $records->bindParam(':address', $address);
        $records->bindParam(':phone_number', $phone_number);
        $records->bindParam(':email', $email);
        $records->bindParam(':password', $password);
        $records->execute();
        return true;
    } catch (Exception $e) {
    }
    return false;
}

function login(string $username, string $password): ?int
{
    $records = connect()->prepare('SELECT client_login(:username, :password) AS user_id');
    $records->bindParam(':username', $username);
    $records->bindParam(':password', $password);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
    if (count($results) > 0) {
        return $results["user_id"];
    }
    return null;
}

function exists(int $user_id): bool
{
    $records = connect()->prepare('SELECT id FROM clients WHERE id = :user_id');
    $records->bindParam(':user_id', $user_id);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
    if ($results) {
        return count($results) > 0;
    }
    return false;
}