-- 1. Desactivar candados de seguridad
SET FOREIGN_KEY_CHECKS = 0;

-- 2. BORRAR MOVIMIENTOS Y VENTAS
-- Basado en tus tablas: 'orders' y 'order_details'
TRUNCATE TABLE order_details;
TRUNCATE TABLE orders;

-- Gastos e Inventario
TRUNCATE TABLE expenses;
TRUNCATE TABLE inventory_logs; -- Este es tu Kardex

-- Reservas
TRUNCATE TABLE reservations;

-- 3. BORRAR CARTA Y NEGOCIO
-- Ingredientes de productos (Recetas)
TRUNCATE TABLE product_ingredients;

-- Productos y Categorías
TRUNCATE TABLE products;
TRUNCATE TABLE categories;

-- Mesas y Areas
TRUNCATE TABLE tables;
TRUNCATE TABLE areas;

-- Clientes
TRUNCATE TABLE clients;

-- 4. BORRAR USUARIOS (Manteniendo al Admin #1)
-- Borra todos los usuarios cuyo ID NO sea 1
DELETE FROM users WHERE id != 1;

-- 5. Reactivar candados
SET FOREIGN_KEY_CHECKS = 1;