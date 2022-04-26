-- - Not rented vehicles - --

SELECT * FROM available_vehicles;

-- - Vehicle type - --

SELECT * FROM available_vehicles WHERE type = 'sedan';

-- - Price range - --

-- -- By Day price -- --
SELECT * FROM available_vehicles WHERE day_price BETWEEN 0 AND 10000;
-- -- By Week price -- --
SELECT * FROM available_vehicles WHERE week_price BETWEEN 0 AND 10000;

-- - Available between dates - --
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
     offices,
     vehicle_rents
WHERE inventory.vehicles_id = vehicles.id
  AND NOT inventory.available
  AND inventory.offices_id = offices.id
  AND vehicle_rents.in_date IS NULL
  AND vehicle_rents.expected_in_date BETWEEN '2022-01-01' AND '2022-12-31'
-- More conditions here --
;
