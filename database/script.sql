-- - Limpiar - --
DROP DATABASE laboratorio_3;

-- - Creacion de base de datos - --
CREATE DATABASE laboratorio_3;
USE laboratorio_3;

-- - Creacion de tablas - --

-- -- colores -- --
CREATE TABLE IF NOT EXISTS colores
(
    id     INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45),
    CONSTRAINT colores_color_unico UNIQUE (nombre)
);

DELIMITER @@
CREATE FUNCTION get_color_id(color VARCHAR(45))
    RETURNS INT
    LANGUAGE SQL
    DETERMINISTIC
BEGIN
    SELECT id INTO @result FROM colores WHERE nombre = color;
    RETURN @result;
END;
@@
DELIMITER ;


INSERT INTO colores (nombre)
VALUES ('rojo'),
       ('amarillo'),
       ('gris'),
       ('negro'),
       ('blanco'),
       ('azul');

-- -- modelos_vehiculos -- --
CREATE TABLE IF NOT EXISTS modelos_vehiculos
(
    id     INT         NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45) NOT NULL,
    valor  FLOAT       NOT NULL,
    CONSTRAINT modelos_vehiculos_modelo_unico UNIQUE (nombre)
);

DELIMITER @@
CREATE FUNCTION get_modelo_id(modelo VARCHAR(45))
    RETURNS FLOAT
    LANGUAGE SQL
    DETERMINISTIC
BEGIN
    SELECT id INTO @result FROM modelos_vehiculos WHERE nombre = modelo;
    RETURN @result;
END;
@@
DELIMITER ;

INSERT INTO modelos_vehiculos (nombre,
                               valor)
VALUES ('sedan', 1),
       ('camioneta', 2),
       ('deportivo', 3);

-- -- precio_capacidad_constante -- --
CREATE TABLE IF NOT EXISTS precio_capacidad_constante
(
    id    INT   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    valor FLOAT NOT NULL
);

DELIMITER @@
CREATE FUNCTION get_precio_capacidad()
    RETURNS FLOAT
    LANGUAGE SQL
    DETERMINISTIC
BEGIN
    SELECT valor INTO @result FROM precio_capacidad_constante WHERE id = 1;
    RETURN @result;
END;
@@
DELIMITER ;

INSERT INTO precio_capacidad_constante (valor)
VALUES (100000);

-- -- precio_puerta_constante -- --
CREATE TABLE IF NOT EXISTS precio_puerta_constante
(
    id    INT   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    valor FLOAT NOT NULL
);

DELIMITER @@
CREATE FUNCTION get_precio_puerta()
    RETURNS FLOAT
    LANGUAGE SQL
    DETERMINISTIC
BEGIN
    SELECT valor INTO @result FROM precio_puerta_constante WHERE id = 1;
    RETURN @result;
END;
@@
DELIMITER ;

INSERT INTO precio_puerta_constante (valor)
VALUES (100000);

-- -- impuesto_constante -- --
CREATE TABLE IF NOT EXISTS impuesto_constante
(
    id    INT   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    valor FLOAT NOT NULL
);

DELIMITER @@
CREATE FUNCTION get_impuesto()
    RETURNS FLOAT
    LANGUAGE SQL
    DETERMINISTIC
BEGIN
    SELECT valor INTO @result FROM impuesto_constante WHERE id = 1;
    RETURN @result;
END;
@@
DELIMITER ;

INSERT INTO impuesto_constante (valor)
VALUES (19);

-- -- precio_descapotable_constante -- -- 
CREATE TABLE IF NOT EXISTS precio_descapotable_constante
(
    id    INT   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    valor FLOAT NOT NULL
);

DELIMITER @@
CREATE FUNCTION get_precio_descapotable()
    RETURNS FLOAT
    LANGUAGE SQL
    DETERMINISTIC
BEGIN
    SELECT valor INTO @result FROM precio_descapotable_constante WHERE id = 1;
    RETURN @result;
END;
@@
DELIMITER ;

INSERT INTO precio_descapotable_constante (valor)
VALUES (10000);

-- -- multiplicador_semana_consante -- --
CREATE TABLE IF NOT EXISTS multiplicador_semana_consante
(
    id    INT   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    valor FLOAT NOT NULL
);

DELIMITER @@
CREATE FUNCTION get_multiplicador_semana()
    RETURNS FLOAT
    LANGUAGE SQL
    DETERMINISTIC
BEGIN
    SELECT valor INTO @result FROM multiplicador_semana_consante WHERE id = 1;
    RETURN @result;
END;
@@
DELIMITER ;

INSERT INTO multiplicador_semana_consante (valor)
VALUES (5);

-- -- empleados -- --
CREATE TABLE IF NOT EXISTS empleados
(
    id                INT           NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ciudad_residencia VARCHAR(45),
    cedula            VARCHAR(45)   NOT NULL,
    nombres           VARCHAR(255)  NOT NULL,
    apellidos         VARCHAR(255)  NOT NULL,
    direccion         VARCHAR(1000) NOT NULL,
    telefono_celular  VARCHAR(45)   NOT NULL,
    correo            VARCHAR(255)  NOT NULL,
    CONSTRAINT empleados_cedula_unica UNIQUE (cedula)
);

INSERT INTO empleados (ciudad_residencia,
                       cedula,
                       nombres,
                       apellidos,
                       direccion,
                       telefono_celular,
                       correo) VALUE
    (
     1,
     '1234567890',
     'Antonio',
     'Santana',
     'Al lado del arbol',
     '1234567890',
     'antonio@network.io'
        ),
    (
     3,
     '1234567891',
     'Sebastian',
     'Garcia',
     'Al lado del arbol',
     '1234567890',
     'sebastian@network.io'
        ),
    (
     2,
     '1234567892',
     'Camila',
     'Torres',
     'Al lado del arbol',
     '1234567890',
     'camila@network.io'
        );

-- -- sucursales -- --
CREATE TABLE IF NOT EXISTS sucursales
(
    id               INT           NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ciudad           VARCHAR(45),
    direccion        VARCHAR(1000) NOT NULL,
    telefono_fijo    VARCHAR(45)   NOT NULL,
    telefono_celular VARCHAR(45)   NOT NULL,
    correo           VARCHAR(255)  NOT NULL
);

INSERT INTO sucursales (ciudad,
                        direccion,
                        telefono_fijo,
                        telefono_celular,
                        correo)
VALUES ('Bucaramanga',
        'La cumbre',
        '1234567890',
        '1234567890',
        'carro@motors.com'),
       ('Cali',
        'La otra cumbre',
        '1234567890',
        '1234567890',
        'carro@motors.com'),
       ('Bogota',
        'La otra cumbre pero en otro lugar',
        '1234567890',
        '1234567890',
        'carro@motors.com');


-- -- vehiculos -- --
CREATE TABLE IF NOT EXISTS vehiculos
(
    id                   INT          NOT NULL PRIMARY KEY AUTO_INCREMENT,
    modelos_vehiculos_id INT          NOT NULL,
    nombre               VARCHAR(45)  NOT NULL,
    colores_id           INT          NOT NULL,
    precio_referencia    FLOAT        NOT NULL,
    puertas              INT          NOT NULL,
    capacidad            INT          NOT NULL,
    es_descapotable      BOOLEAN      NOT NULL,
    motor                VARCHAR(500) NOT NULL,
    precio_semana        FLOAT,
    precio_dia           FLOAT,
    CONSTRAINT vehiculos_vehiculo_unico UNIQUE (nombre, modelos_vehiculos_id, colores_id, precio_referencia, puertas,
                                                capacidad, es_descapotable, motor),
    CONSTRAINT fk_vehiculos_modelos_vehiculos_id FOREIGN KEY (modelos_vehiculos_id) REFERENCES modelos_vehiculos (id),
    CONSTRAINT fk_vehiculos_colores_id FOREIGN KEY (colores_id) REFERENCES colores (id)
);

-- --inventario -- --
CREATE TABLE IF NOT EXISTS inventario
(
    id            INT         NOT NULL PRIMARY KEY AUTO_INCREMENT,
    sucursales_id INT         NOT NULL,
    vehiculos_id  INT         NOT NULL,
    placa         VARCHAR(45) NOT NULL,
    disponible    BOOLEAN     NOT NULL DEFAULT true,
    CONSTRAINT inventario_placa_unica UNIQUE (placa),
    CONSTRAINT fk_inventario_sucursales_id FOREIGN KEY (sucursales_id) REFERENCES sucursales (id),
    CONSTRAINT fk_inventario_vehiculos_id FOREIGN KEY (vehiculos_id) REFERENCES vehiculos (id)
);

-- -- clientes -- --
CREATE TABLE IF NOT EXISTS clientes
(
    id                INT           NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ciudad_residencia VARCHAR(45),
    cedula            VARCHAR(45)   NOT NULL,
    nombres           VARCHAR(255)  NOT NULL,
    apellidos         VARCHAR(255)  NOT NULL,
    direccion         VARCHAR(1000) NOT NULL,
    telefono_celular  VARCHAR(45)   NOT NULL,
    correo            VARCHAR(255)  NOT NULL,
    contrasena        VARCHAR(128)  NOT NULL,
    CONSTRAINT clientes_cedula_unica UNIQUE (cedula)
);

-- -- alquileres -- --
CREATE TABLE IF NOT EXISTS alquileres
(
    id                     INT      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    clientes_id            INT      NOT NULL,
    empleados_id           INT      NOT NULL,
    sucursal_salida        INT      NOT NULL,
    sucursal_entrega       INT,
    inventario_id          INT      NOT NULL,
    fecha_salida           DATETIME NOT NULL,
    fecha_llegada_esperada DATETIME NOT NULL,
    fecha_llegada          DATETIME,
    precio_semana_final    FLOAT    NOT NULL,
    precio_dia_final       FLOAT    NOT NULL,
    semanas_alquilado      INT      NOT NULL,
    dias_alquilado         INT      NOT NULL,
    valor_cotizado         FLOAT,
    valor_pagado           FLOAT,
    CONSTRAINT fk_alquileres_clientes_id FOREIGN KEY (clientes_id) REFERENCES clientes (id),
    CONSTRAINT fk_alquileres_empleados_id FOREIGN KEY (empleados_id) REFERENCES empleados (id),
    CONSTRAINT fk_alquileres_sucursal_salida FOREIGN KEY (sucursal_salida) REFERENCES sucursales (id),
    CONSTRAINT fk_alquileres_sucursal_entrega FOREIGN KEY (sucursal_entrega) REFERENCES sucursales (id),
    CONSTRAINT fk_alquileres_inventario_id FOREIGN KEY (inventario_id) REFERENCES inventario (id)

);

-- -- Creacion de vistas -- --
CREATE VIEW vehiculos_disponibles AS
SELECT inventario.id                  AS inventario_id,
       inventario.sucursales_id       AS sucursales_id,
       inventario.vehiculos_id        AS vehiculos_id,
       inventario.placa               AS placa,
       vehiculos.modelos_vehiculos_id AS modelo_id,
       vehiculos.nombre               AS nombre,
       vehiculos.colores_id           AS color_id,
       vehiculos.precio_referencia    AS precio_referencia,
       vehiculos.puertas              AS puertas,
       vehiculos.capacidad            AS capacidad,
       vehiculos.es_descapotable      AS descapotable,
       vehiculos.motor                AS motor,
       vehiculos.precio_semana        AS precio_semana,
       vehiculos.precio_dia           AS precio_dia
FROM vehiculos,
     inventario
WHERE inventario.vehiculos_id = vehiculos.id
  AND inventario.disponible;

CREATE VIEW historial_de_alquileres AS
SELECT id,
       clientes_id,
       empleados_id,
       sucursal_salida,
       sucursal_entrega,
       inventario_id,
       fecha_salida,
       fecha_llegada_esperada,
       fecha_llegada,
       precio_semana_final,
       precio_dia_final,
       semanas_alquilado,
       dias_alquilado,
       valor_cotizado,
       valor_pagado
FROM alquileres
WHERE fecha_llegada IS NOT NULL;

/*
SELECT
    id,
    clientes_id,
    empleados_id,
    sucursal_salida,
    sucursal_entrega,
    inventario_id,
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
    alquileres
WHERE
    alquileres.clientes_id = :id_client;
*/

DELIMITER @@

-- - Creacion de proceos y funciones - --
-- -- Actualizar vehiculos - --
CREATE PROCEDURE
    actualizar_vehiculos()
BEGIN
    SET @capacidad_valor = get_precio_capacidad();
    SET @puerta_valor = get_precio_puerta();
    SET @impuesto_valor = get_impuesto();
    SET @descapotable_precio = get_precio_descapotable();
    SET @multiplicador_semana = get_multiplicador_semana();
    UPDATE
        vehiculos,
        modelos_vehiculos
    SET vehiculos.precio_semana = @impuesto_valor * (
            (vehiculos.precio_referencia + modelos_vehiculos.valor) +
            (vehiculos.capacidad + @capacidad_valor) +
            (vehiculos.puertas * @puerta_valor) +
            (vehiculos.es_descapotable * @descapotable_precio)
        )
    WHERE vehiculos.modelos_vehiculos_id = modelos_vehiculos.id;
    UPDATE
        vehiculos,
        modelos_vehiculos
    SET vehiculos.precio_dia = precio_semana / @multiplicador_semana
    WHERE vehiculos.modelos_vehiculos_id = modelos_vehiculos.id;

END;
@@

-- -- actualizar_vehiculos_por_modelo -- --
CREATE PROCEDURE
    actualizar_vehiculos_por_modelo(IN v_id_modelo INT)
BEGIN
    SET @capacidad_valor = get_precio_capacidad();
    SET @puerta_valor = get_precio_puerta();
    SET @impuesto_valor = get_impuesto();
    SET @descapotable_precio = get_precio_descapotable();
    SET @multiplicador_semana = get_multiplicador_semana();
    UPDATE
        vehiculos,
        modelos_vehiculos
    SET vehiculos.precio_semana = @impuesto_valor * (
            (vehiculos.precio_referencia + modelos_vehiculos.valor) +
            (vehiculos.capacidad + @capacidad_valor) +
            (vehiculos.puertas * @puerta_valor) +
            (vehiculos.es_descapotable * @descapotable_precio)
        ),
        vehiculos.precio_dia    = precio_semana / @multiplicador_semana
    WHERE modelos_vehiculos.id = v_id_modelo
      AND vehiculos.modelos_vehiculos_id = modelos_vehiculos.id;
END;
@@

-- -- Registrar cliente -- --

CREATE PROCEDURE
    registrar_cliente(
    IN v_ciudad_residencia VARCHAR(45),
    IN v_cedula VARCHAR(45),
    IN v_nombres VARCHAR(255),
    IN v_apellidos VARCHAR(255),
    IN v_direccion VARCHAR(1000),
    IN v_telefono_celular VARCHAR(45),
    IN v_correo VARCHAR(255),
    IN v_contrasena VARCHAR(128)
)
BEGIN
    INSERT IGNORE INTO clientes (ciudad_residencia,
                                 cedula,
                                 nombres,
                                 apellidos,
                                 direccion,
                                 telefono_celular,
                                 correo,
                                 contrasena)
    VALUES ((v_ciudad_residencia),
            v_cedula,
            v_nombres,
            v_apellidos,
            v_direccion,
            v_telefono_celular,
            v_correo,
            v_contrasena);
END;
@@


CREATE FUNCTION
    login_cliente(
    v_correo VARCHAR(255),
    v_password_hash VARCHAR(128)
)
    RETURNS INT
    LANGUAGE SQL
    DETERMINISTIC
BEGIN
    SELECT id INTO @id_cliente FROM clientes WHERE clientes.correo = v_correo AND clientes.contrasena = v_password_hash;
    RETURN @id_cliente;
END;
@@

CREATE PROCEDURE
    registrar_vehiculo(
    v_nombre VARCHAR(45),
    v_modelo VARCHAR(45),
    v_color VARCHAR(45),
    v_placa VARCHAR(45),
    v_precio_referencia FLOAT,
    v_puertas INT,
    v_capacidad INT,
    v_es_descapotable BOOLEAN,
    v_motor VARCHAR(500),
    v_id_sucursal INT
)
BEGIN
    SET @id_modelo = get_modelo_id(v_modelo);
    SET @id_color = get_color_id(v_color);
    IF (@id_color IS NULL) THEN
        INSERT INTO colores (nombre) VALUES (v_color);
        SET @id_color = get_color_id(v_color);
    END IF;
    IF @id_modelo > 0 THEN
        INSERT IGNORE INTO vehiculos (modelos_vehiculos_id,
                                      nombre,
                                      colores_id,
                                      precio_referencia,
                                      puertas,
                                      capacidad,
                                      es_descapotable,
                                      motor)
        VALUES (@id_modelo,
                v_nombre,
                @id_color,
                v_precio_referencia,
                v_puertas,
                v_capacidad,
                v_es_descapotable,
                v_motor);
        SELECT id
        INTO @id_vehiculo
        FROM vehiculos
        WHERE modelos_vehiculos_id = @id_modelo
          AND nombre = v_nombre
          AND colores_id = @id_color
          AND precio_referencia = v_precio_referencia
          AND puertas = v_puertas
          AND capacidad = v_capacidad
          AND es_descapotable = v_es_descapotable
          AND motor = v_motor;
        IF @id_vehiculo > 0 THEN
            INSERT INTO inventario (sucursales_id,
                                    vehiculos_id,
                                    placa)
            VALUES (v_id_sucursal,
                    @id_vehiculo,
                    v_placa);
            CALL actualizar_vehiculos();
        END IF;
    END IF;
END;
@@

CREATE PROCEDURE alquilar_vehiculo(
    IN v_inventario_id INT,
    IN v_id_cliente INT,
    IN v_numero_semanas INT,
    IN v_numero_dias INT
)
BEGIN
    SET @ahora = NOW();
    SELECT empleados.id INTO @id_empleado FROM empleados ORDER BY RAND() LIMIT 1;
    SELECT inventario_id,
           precio_semana,
           precio_dia,
           sucursales_id
    INTO @id_inventario, @semana_final, @dia_final, @id_sucursal
    FROM vehiculos_disponibles
    WHERE vehiculos_id = v_inventario_id
    LIMIT 1;
    INSERT INTO alquileres (clientes_id,
                            empleados_id,
                            sucursal_salida,
                            inventario_id,
                            fecha_salida,
                            fecha_llegada_esperada,
                            precio_semana_final,
                            precio_dia_final,
                            semanas_alquilado,
                            dias_alquilado,
                            valor_cotizado)
    VALUES (v_id_cliente,
            @id_empleado,
            @id_sucursal,
            @id_inventario,
            @ahora,
            ADDDATE(@ahora, INTERVAL (v_numero_semanas * 7) + v_numero_dias DAY),
            @semana_final,
            @dia_final,
            v_numero_semanas,
            v_numero_dias,
            @semana_final * v_numero_semanas + @dia_final * v_numero_dias);
    UPDATE inventario SET inventario.disponible = false WHERE inventario.id = @id_inventario;
END;
@@

CREATE PROCEDURE recuperar_vehiculo(
    IN v_placa VARCHAR(45),
    IN v_sucursal_entrega INT,
    IN v_valor_pagado FLOAT
)
BEGIN
    SELECT inventario.id
    INTO @id_alquiler
    FROM alquileres,
         inventario
    WHERE alquileres.inventario_id = inventario.id
      AND inventario.placa = placa
    LIMIT 1;
    SET @right_now = NOW();
    IF @id_alquiler IS NOT NULL THEN
        UPDATE
            inventario
        SET inventario.disponible = true
        WHERE inventario.placa = v_placa;
        UPDATE
            alquileres
        SET alquileres.sucursal_entrega = v_sucursal_entrega,
            alquileres.fecha_llegada    = @right_now,
            alquileres.valor_pagado     = v_valor_pagado
        WHERE alquileres.id = @id_alquiler;
    END IF;
END;
@@


-- - Creacion de triggers - --

-- -- modelos_vehiculos -- --

CREATE TRIGGER modelos_vehiculos_after_update
    AFTER UPDATE
    ON modelos_vehiculos
    FOR EACH ROW
BEGIN
    IF OLD.valor != NEW.valor THEN
        CALL actualizar_vehiculos_por_modelo(NEW.id);
    END IF;
END;
@@

-- -- precio_puerta_constante -- --
CREATE TRIGGER precio_puerta_constante_after_update
    AFTER UPDATE
    ON precio_puerta_constante
    FOR EACH ROW
BEGIN
    CALL actualizar_vehiculos();
END;
@@

-- -- precio_capacidad_constante -- --
CREATE TRIGGER precio_capacidad_constante_after_update
    AFTER UPDATE
    ON precio_capacidad_constante
    FOR EACH ROW
BEGIN
    CALL actualizar_vehiculos();
END;
@@

-- -- precio_descapotable_constante -- --
CREATE TRIGGER precio_descapotable_constante_after_update
    AFTER UPDATE
    ON precio_descapotable_constante
    FOR EACH ROW
BEGIN
    CALL actualizar_vehiculos();
END;
@@

DELIMITER ;


-- - Datos de inicializacion - --

CALL registrar_vehiculo(
        'Nissan Versa 2016',
        'sedan',
        'rojo',
        'SUB-527',
        1000000,
        4,
        5,
        false,
        'v8',
        1
    );
CALL registrar_vehiculo(
        'Nissan Versa 2016',
        'sedan',
        'rojo',
        'SUB-528',
        1000000,
        4,
        5,
        false,
        'v8',
        1
    );
CALL registrar_vehiculo(
        'Nissan Versa 2020',
        'sedan',
        'rojo',
        'SUB-529',
        1000000,
        4,
        5,
        false,
        'v8',
        1
    );
