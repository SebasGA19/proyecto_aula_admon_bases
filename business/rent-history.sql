-- - Not completed rents - --

SELECT * FROM incomplete_rents WHERE clients_id = :user_id;

-- - Completed rents - --

SELECT * FROM completed_rents WHERE clients_id = :user_id;