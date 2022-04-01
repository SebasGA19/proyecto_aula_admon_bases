# Laboratorio 3 - Diseño

###### Antonio Jose Donis  Hung - 000408397

## Cuentas

Tanto la cuenta para el cliente móvil como para el cliente web serán las mismas debido a que ambas realizan las mismas actividades.

### Permisos necesarios

- SELECT
- UPDATE
- INSERT

## Triggers

### `modelos_vehiculos`

#### UPDATE

- [x] De cada vehículo con el modelo, actualizar el precio por semana.

### `precio_puerta_constante`

#### UPDATE

- [x] Recalcular el precio por semana de todos los vehículos.

### `precio_capacidad_constante`

#### UPDATE

- [x] Recalcular el precio por semana de todos los vehículos.

### `precio_descapotable_constante`

- [x] Recalcular el precio por semana de todos los vehículos.

## Procesos

### Actualizar todos los vehículos

#### Notas

- [x] Actualiza todos los precios por semana y por día de todos los vehículos .

### Actualizar todos los vehículos teniendo en cuenta el modelo

#### Notas

- [x] Actualiza los precios solo de los vehículos del modelo especificado.

#### Argumentos

- [x] ID del modelo del vehículo a actualizar

### Registro de cliente

#### Argumentos

- [x] Ciudad de residencia
- [x] Cedula
- [x] Nombres
- [x] Apellidos
- [x] Dirección
- [x] Teléfono celular
- [x] Correo
- [x] Hash de contraseña

### Inicio de sesión

#### Argumentos

- [x] Correo electrónico
- [x] Hash de contraseña

#### Resultados

##### ID del usuario

- [x] 0: Cuando no se encontró ningún usuario con las credenciales especificadas.
- [x] ID_DEL_USUARIO:  Cuando se logro autenticar la cuenta.

### Registrar vehículo

#### Notas

- El proceso también registra la entrada correspondiente en `inventario`.

#### Argumentos

- [x] Nombre del modelo
- [x] Nombre del color
- [x] Placa
- [x] Precio de referencia
- [x] Numero de puertas
- [x] Capacidad
- [x] Es descapotable
- [x] Motor
- [x] ID Sucursal  en la que se guardara

### Alquiler de vehículos (registrar nuevo alquiler)

#### Notas

- Este proceso actualiza las siguientes columnas de `inventario`
  - `disponible`: Coloca falso en la fila correspondiente al carro alquilado

#### Argumentos

- [ ] ID del cliente
- [ ] ID del empleado que cerro el trato
- [ ] ID de la sucursal en la que se cerro el trato
- [ ] ID del vehículo a alquilar
- [ ] Fecha esperada de llegada del vehículo
- [ ] Numero de semanas alquilado
- [ ] Numero de días alquilado

#### Resultados

##### ID del alquiler

- [ ] 0: El alquiler del vehículo no se pudo realizar.
- [ ] ID_DEL_ALQUILER: El alquiler del vehículo se completo con éxito.

### Alquiler de vehículos (entregar vehículo)

#### Notas

- Este proceso actualiza las  siguientes columnas de `alquileres`
  - `fecha_llegada`: Con la fecha en que el proceso se ejecuto.
  - `sucursal_entrega`: Con la sucursal del argumento
  - `valor_pagado`: Con el valor que el cliente debe pagar teniendo en cuenta también si se demoro.
- Este proceso actualiza las siguientes columnas de `inventario`
  - `disponible`: Coloca verdadero en la fila correspondiente al carro alquilado
  - `sucursales_id`: Cambia la sucursal a la sucursal en la que se recibió el vehículo.

#### Argumentos

- [ ] ID del alquiler
- [ ] ID de la sucursal que lo recibe

#### Resultados

##### Éxito

- [ ] Verdadero: El alquiler se cerro con éxito
- [ ] Falso: El alquiler no se pudo cerrar

##### Precio a pagar

- [ ] 0:  Cuando éxito es falso.
- [ ] VALOR_A_PAGAR: Valor que debe pagar el cliente.

### Cancelar alquiler

#### Notas

#### Argumentos

- [ ] ID del alquiler

## Vistas

### `vehiculos_disponibles` (HECHO)

#### Notas

- Buscar todos los vehículos que están disponibles para alquilar.
- Filtrar por rango de precio de alquiler en semana
- Filtrar por rango de precio de alquiler en día
- Filtrar por modelo

### `historial_de_alquileres_pendientes`

#### Notas

- Buscar todos los alquileres pendientes.

### `historial_de_alquileres_completados`

#### Notas

- Buscar todos los alquileres completados.