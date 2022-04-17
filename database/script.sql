DROP DATABASE upb_rental;
CREATE DATABASE upb_rental;
USE upb_rental;

-- -- Employees -- --
CREATE TABLE IF NOT EXISTS employees
(
    id                 INT           NOT NULL PRIMARY KEY AUTO_INCREMENT,
    personal_id        VARCHAR(45)   NOT NULL,
    complete_name      VARCHAR(255)  NOT NULL,
    address            VARCHAR(1000) NOT NULL,
    phone_number       VARCHAR(45)   NOT NULL,
    email              VARCHAR(255)  NOT NULL,
    last_time_modified DATETIME      NOT NULL DEFAULT NOW(),
    CONSTRAINT unique_employees_personal_id UNIQUE (personal_id),
    CONSTRAINT unique_employees_email UNIQUE (email)
);

DELIMITER @@
CREATE TRIGGER before_update_employees
    BEFORE UPDATE
    ON employees
    FOR EACH ROW
BEGIN
    SET NEW.last_time_modified = NOW();
END;
@@
DELIMITER ;

INSERT INTO employees (personal_id,
                       complete_name,
                       address,
                       phone_number,
                       email) VALUE
    (
     '1234567890',
     'Antonio Santana',
     'Al lado del arbol',
     '1234567890',
     'antonio@network.io'
        ),
    (
     '1234567891',
     'Sebastian Garcia',
     'Al lado del arbol',
     '1234567890',
     'sebastian@network.io'
        ),
    (
     '1234567892',
     'Camila Torres',
     'Al lado del arbol',
     '1234567890',
     'camila@network.io'
        );

-- -- Offices -- --
CREATE TABLE IF NOT EXISTS offices
(
    id                 INT          NOT NULL PRIMARY KEY AUTO_INCREMENT,
    city               VARCHAR(50)  NOT NULL,
    address            VARCHAR(500) NOT NULL,
    phone_number       VARCHAR(45)  NOT NULL,
    email              VARCHAR(255) NOT NULL,
    last_time_modified DATETIME     NOT NULL DEFAULT NOW(),
    CONSTRAINT unique_office_city_address UNIQUE (city, address)
);

DELIMITER @@
CREATE TRIGGER before_update_offices
    BEFORE UPDATE
    ON offices
    FOR EACH ROW
BEGIN
    SET NEW.last_time_modified = NOW();
END;
@@
DELIMITER ;

INSERT INTO offices (city,
                     address,
                     phone_number,
                     email)
VALUES ('Bucaramanga',
        'La cumbre',
        '1234567890',
        'carro@motors.com'),
       ('Cali',
        'La otra cumbre',
        '1234567890',
        'carro@motors.com'),
       ('Bogota',
        'La nevera',
        '1234567890',
        'carro@motors.com'),
       ('Medellin',
        'La otra cumbre pero en otro lugar',
        '1234567890',
        'carro@motors.com'),
       ('Cartagena',
        'La playa',
        '1234567890',
        'carro@motors.com');


-- -- Vehicles -- --
CREATE TABLE IF NOT EXISTS vehicles
(
    id                 INT          NOT NULL PRIMARY KEY AUTO_INCREMENT,
    model              VARCHAR(50)  NOT NULL,
    type               VARCHAR(50)  NOT NULL,
    color              VARCHAR(50)  NOT NULL,
    doors              INT          NOT NULL,
    capacity           INT          NOT NULL,
    convertible        BOOLEAN      NOT NULL,
    motor              VARCHAR(500) NOT NULL,
    day_price          INT          NOT NULL,
    week_price         INT          NOT NULL,
    last_time_modified DATETIME     NOT NULL DEFAULT NOW(),
    CONSTRAINT unique_vehicle UNIQUE (
                                      model,
                                      type,
                                      color,
                                      doors,
                                      capacity,
                                      convertible,
                                      motor
        )
);

DELIMITER @@
CREATE TRIGGER before_update_vehicles
    BEFORE UPDATE
    ON vehicles
    FOR EACH ROW
BEGIN
    SET NEW.last_time_modified = NOW();
END;
@@
DELIMITER ;

-- -- Inventory -- --
CREATE TABLE IF NOT EXISTS inventory
(
    id                 INT         NOT NULL PRIMARY KEY AUTO_INCREMENT,
    offices_id         INT         NOT NULL,
    vehicles_id        INT         NOT NULL,
    plate              VARCHAR(45) NOT NULL,
    available          BOOLEAN     NOT NULL DEFAULT true,
    last_time_modified DATETIME    NOT NULL DEFAULT NOW(),
    CONSTRAINT unique_inventory_vehicle UNIQUE (plate),
    CONSTRAINT fk_inventory_office_id_refs_offices_id FOREIGN KEY (offices_id) REFERENCES offices (id),
    CONSTRAINT fk_inventory_vehicle_id_refs_vehicles_id FOREIGN KEY (vehicles_id) REFERENCES vehicles (id)
);

DELIMITER @@
CREATE TRIGGER before_update_inventory
    BEFORE UPDATE
    ON inventory
    FOR EACH ROW
BEGIN
    SET NEW.last_time_modified = NOW();
END;
@@
DELIMITER ;

-- -- Clients -- --
CREATE TABLE IF NOT EXISTS clients
(
    id                 INT           NOT NULL PRIMARY KEY AUTO_INCREMENT,
    personal_id        VARCHAR(45)   NOT NULL,
    complete_name      VARCHAR(500)  NOT NULL,
    address            VARCHAR(1000) NOT NULL,
    phone_number       VARCHAR(45)   NOT NULL,
    email              VARCHAR(255)  NOT NULL,
    password_hash      VARCHAR(128)  NOT NULL,
    password_salt      varchar(500)  NOT NULL,
    last_time_modified DATETIME      NOT NULL DEFAULT NOW(),
    CONSTRAINT unique_clients_personal_id UNIQUE (personal_id),
    CONSTRAINT unique_clients_email UNIQUE (email)
);

DELIMITER @@
CREATE TRIGGER before_update_clients
    BEFORE UPDATE
    ON clients
    FOR EACH ROW
BEGIN
    SET NEW.last_time_modified = NOW();
END;
@@
DELIMITER ;

-- -- Vehicle rents -- --
CREATE TABLE IF NOT EXISTS vehicle_rents
(
    id                 INT      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    clients_id         INT      NOT NULL,
    employees_id       INT      NOT NULL,
    out_office         INT      NOT NULL,
    in_office          INT,
    inventory_id       INT      NOT NULL,
    out_date           DATETIME NOT NULL,
    expected_in_date   DATETIME NOT NULL,
    in_date            DATETIME,
    final_day_price    INT      NOT NULL,
    final_week_price   INT      NOT NULL,
    days_rented        INT      NOT NULL,
    weeks_rented       INT      NOT NULL,
    expected_price     INT,
    payed_price        INT,
    last_time_modified DATETIME NOT NULL DEFAULT NOW(),
    CONSTRAINT fk_vehicle_rents_clients_id FOREIGN KEY (clients_id) REFERENCES clients (id),
    CONSTRAINT fk_vehicle_rents_employees_id FOREIGN KEY (employees_id) REFERENCES employees (id),
    CONSTRAINT fk_vehicle_rents_out_office FOREIGN KEY (out_office) REFERENCES offices (id),
    CONSTRAINT fk_vehicle_rents_in_office FOREIGN KEY (in_office) REFERENCES offices (id),
    CONSTRAINT fk_vehicle_rents_inventory_id FOREIGN KEY (inventory_id) REFERENCES inventory (id),
    CONSTRAINT min_rent_days_and_weeks CHECK (days_rented > 0 OR weeks_rented > 0)
);

DELIMITER @@

CREATE TRIGGER before_update_vehicle_rents
    BEFORE UPDATE
    ON vehicle_rents
    FOR EACH ROW
BEGIN
    SET NEW.last_time_modified = NOW();
END;
@@

CREATE TRIGGER after_insert_vehicle_rent
    AFTER INSERT
    ON vehicle_rents
    FOR EACH ROW
BEGIN
    UPDATE inventory
    SET inventory.available = false
    WHERE inventory.vehicles_id = NEW.inventory_id;
END;
@@

CREATE TRIGGER after_update_vehicle_rent
    AFTER UPDATE
    ON vehicle_rents
    FOR EACH ROW
BEGIN
    IF OLD.in_date IS NULL AND NEW.in_date IS NOT NULL THEN
        UPDATE inventory
        SET inventory.available = true
        WHERE inventory.vehicles_id = NEW.inventory_id;
    END IF;
END;
@@

DELIMITER ;

-- -- Views -- --
CREATE VIEW available_vehicles AS
SELECT inventory.id          AS inventory_id,
       inventory.offices_id  AS office_id,
       offices.city          AS city,
       inventory.vehicles_id AS vehicle_id,
       inventory.plate       AS plate,
       vehicles.model        AS model,
       vehicles.type         AS type,
       vehicles.color        AS color,
       vehicles.doors        AS doors,
       vehicles.capacity     AS capacity,
       vehicles.convertible  AS convertible,
       vehicles.motor        AS motor,
       vehicles.day_price    AS day_price,
       vehicles.week_price   AS week_price
FROM vehicles,
     inventory,
     offices
WHERE inventory.vehicles_id = vehicles.id
  AND inventory.available
  AND inventory.offices_id = offices.id;

CREATE VIEW completed_rents AS
SELECT vehicle_rents.id               AS id,
       vehicle_rents.clients_id       AS clients_id,
       vehicle_rents.employees_id     AS employees_id,
       vehicle_rents.out_office       AS out_office,
       vehicle_rents.in_office        AS in_office,
       vehicle_rents.inventory_id     AS inventory_id,
       vehicles.type                  AS type,
       vehicle_rents.out_date         AS out_date,
       vehicle_rents.expected_in_date AS expected_in_date,
       vehicle_rents.in_date          AS in_date,
       vehicle_rents.final_day_price  AS final_day_price,
       vehicle_rents.final_week_price AS final_week_price,
       vehicle_rents.days_rented      AS days_rented,
       vehicle_rents.weeks_rented     AS weeks_rented,
       vehicle_rents.expected_price   AS expected_price,
       vehicle_rents.payed_price      AS payed_price
FROM vehicle_rents,
     vehicles,
     inventory
WHERE vehicle_rents.in_date IS NOT NULL
  AND inventory.id = vehicle_rents.inventory_id
  AND vehicles.id = inventory.vehicles_id;

DELIMITER @@

-- -- Registrar cliente -- --

CREATE FUNCTION
    salt()
    RETURNS VARCHAR(500)
    LANGUAGE SQL
    DETERMINISTIC
BEGIN
    SELECT LEFT(UUID(), 500) INTO @salt_result;
    return @salt_result;
END;
@@

CREATE PROCEDURE
    register_client(
    v_personal_id VARCHAR(45),
    v_complete_name VARCHAR(500),
    v_address VARCHAR(1000),
    v_phone_number VARCHAR(45),
    v_email VARCHAR(255),
    v_password VARCHAR(128)
)
BEGIN
    SET @password_salt = salt();
    INSERT INTO clients (personal_id,
                         complete_name,
                         address,
                         phone_number,
                         email,
                         password_hash,
                         password_salt)
    VALUES (v_personal_id,
            v_complete_name,
            v_address,
            v_phone_number,
            v_email,
            ENCRYPT(v_password, @password_salt),
            @password_salt);
END;
@@


CREATE FUNCTION
    client_login(
    v_email VARCHAR(255),
    v_password VARCHAR(500)
)
    RETURNS INT
    LANGUAGE SQL
    DETERMINISTIC
BEGIN
    SELECT password_salt INTO @password_salt FROM clients WHERE email = v_email LIMIT 1;
    IF @password_salt IS NULL THEN
        RETURN NULL;
    END IF;
    SELECT id
    INTO @id_cliente
    FROM clients
    WHERE clients.email = v_email
      AND clients.password_hash = ENCRYPT(v_password, @password_salt)
    LIMIT 1;
    RETURN @id_cliente;
END;
@@

CREATE PROCEDURE
    register_vehicle(
    v_model VARCHAR(50),
    v_type VARCHAR(50),
    v_color VARCHAR(50),
    v_doors INT,
    v_capacity INT,
    v_convertible BOOLEAN,
    v_motor VARCHAR(500),
    v_day_price INT,
    v_week_price INT,
    v_office_id INT,
    v_plate VARCHAR(50)
)
BEGIN
    INSERT IGNORE INTO vehicles (model,
                                 type,
                                 color,
                                 doors,
                                 capacity,
                                 convertible,
                                 motor,
                                 day_price,
                                 week_price)
    VALUES (v_model,
            v_type,
            v_color,
            v_doors,
            v_capacity,
            v_convertible,
            v_motor,
            v_day_price,
            v_week_price);

    SELECT id
    INTO @vehicle_id
    FROM vehicles
    WHERE model = v_model
      AND type = v_type
      AND color = v_color
      AND doors = v_doors
      AND capacity = v_capacity
      AND convertible = v_convertible
      AND motor = v_motor
      AND day_price = v_day_price
      AND week_price = v_week_price
    LIMIT 1;

    INSERT INTO inventory (offices_id,
                           vehicles_id,
                           plate)
    VALUES (v_office_id,
            @vehicle_id,
            v_plate);
END;
@@

CREATE PROCEDURE rent_vehicle(
    IN v_inventory_id INT,
    IN v_client_id INT,
    IN v_days_rented INT,
    IN v_weeks_rented INT
)
BEGIN
    SET @transaction_time = NOW();
    -- Assign an employee to the rent to monitor it --
    SELECT employees.id INTO @employee_id FROM employees ORDER BY RAND() LIMIT 1;
    -- Capture the vehicle information
    SELECT inventory_id,
           office_id,
           day_price,
           week_price
    INTO @inventory_id, @office_id, @final_day_price, @final_week_price
    FROM available_vehicles
    WHERE available_vehicles.inventory_id = v_inventory_id
    LIMIT 1;
    -- Rent the vehicle
    INSERT INTO vehicle_rents (clients_id,
                               employees_id,
                               out_office,
                               inventory_id,
                               out_date,
                               expected_in_date,
                               final_day_price,
                               final_week_price,
                               days_rented,
                               weeks_rented,
                               expected_price)
    VALUES (v_client_id,
            @employee_id,
            @office_id,
            @inventory_id,
            @transaction_time,
            ADDDATE(@transaction_time, INTERVAL (v_weeks_rented * 7) + v_days_rented DAY),
            @final_day_price,
            @final_week_price,
            v_days_rented,
            v_weeks_rented,
            @final_day_price * v_days_rented + @final_week_price * v_weeks_rented);
END;
@@

CREATE PROCEDURE recover_vehicle(
    IN v_plate VARCHAR(45),
    IN v_in_office INT,
    IN v_payment_amount FLOAT
)
BEGIN
    -- Get the time of the transaction
    SET @transaction_time = NOW();
    -- Get the id of the rent
    SELECT vehicle_rents.id
    INTO @rent_id
    FROM vehicle_rents,
         inventory
    WHERE vehicle_rents.inventory_id = inventory.id
      AND inventory.plate = v_plate
      AND vehicle_rents.in_date IS NULL
    LIMIT 1;
    -- -

    IF @rent_id IS NOT NULL THEN
        -- - Change the status of the rent
        UPDATE
            vehicle_rents
        SET vehicle_rents.in_office   = v_in_office,
            vehicle_rents.in_date     = @transaction_time,
            vehicle_rents.payed_price = v_payment_amount
        WHERE vehicle_rents.id = @rent_id;
    END IF;
END;
@@

DELIMITER ;


-- - Demo vehicles - --

CALL register_vehicle(
        'Nissan Versa 2016',
        'sedan',
        'rojo',
        4,
        6,
        false,
        'V8',
        200000,
        1000000,
        1,
        'SUB-527'
    );
CALL register_vehicle(
        'Nissan Versa 2016',
        'sedan',
        'rojo',
        4,
        6,
        false,
        'V8',
        200000,
        1000000,
        1,
        'SUB-528'
    );
CALL register_vehicle(
        'Nissan Versa 2016',
        'sedan',
        'rojo',
        4,
        6,
        false,
        'V8',
        200000,
        1000000,
        1,
        'SUB-529'
    );

CALL register_vehicle(
        'Nissan Versa 2016',
        'sedan',
        'gris',
        4,
        6,
        false,
        'V8',
        200000,
        1000000,
        1,
        'SUB-530'
    );

CALL register_vehicle(
        'Nissan Versa 2016',
        'sedan',
        'gris',
        4,
        6,
        false,
        'V8',
        200000,
        1000000,
        1,
        'SUB-531'
    );

CALL register_vehicle(
        'Nissan Versa 2020',
        'sedan',
        'gris',
        4,
        6,
        false,
        'V8',
        200000,
        1000000,
        1,
        'SUB-532'
    );
