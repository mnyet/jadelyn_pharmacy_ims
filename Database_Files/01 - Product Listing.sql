SELECT * FROM jadelyn_pharmacy_brands jpb; -- Product Brands
SELECT * FROM jadelyn_pharmacy_product_types; -- Product Types
SELECT * FROM jadelyn_pharmacy_product_list; -- Product List (The main table)

SELECT a.id AS product_id, a.name AS product_name, b.name AS product_type, c.name AS brand_name FROM jadelyn_pharmacy_product_list a
INNER JOIN jadelyn_pharmacy_product_types b ON a.product_type_id = b.id
INNER JOIN jadelyn_pharmacy_brands c ON a.brand_id = c.id;

TRUNCATE TABLE jadelyn_pharmacy_brands

-- Insert sample brand
INSERT INTO jadelyn_pharmacy_brands (name) VALUES ('Abbott');

-- Insert sample product type
INSERT INTO jadelyn_pharmacy_product_types (name, description) VALUES ('Drug', 'Lists all the tablets and capsules');
INSERT INTO jadelyn_pharmacy_product_types (name, description) VALUES ('Syrup', '');
INSERT INTO jadelyn_pharmacy_product_types (name, description) VALUES ('Suspension', '');

-- Insert sample product
INSERT INTO jadelyn_pharmacy_product_list (name, product_type_id, brand_id, price) VALUES ('Gamot Gamotan', '1', '1', 0);
INSERT INTO jadelyn_pharmacy_product_list (name, product_type_id, brand_id, price) VALUES ('Syrup Test', '2', '1', 0);
INSERT INTO jadelyn_pharmacy_product_list (name, product_type_id, brand_id, price) VALUES ('Suspension Test', '3', '1', 0);



