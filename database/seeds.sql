-- ShopSmart PostgreSQL seeds

INSERT INTO roles (nombre, descripcion)
VALUES
('admin', 'Acceso total al panel administrativo'),
('cliente', 'Usuario comprador de la tienda')
ON CONFLICT (nombre)
DO UPDATE SET descripcion = EXCLUDED.descripcion;

INSERT INTO categorias (nombre, descripcion)
VALUES
('Electronica', 'Equipos y accesorios de tecnologia'),
('Audio', 'Audifonos, bocinas y perifericos de sonido'),
('Oficina', 'Articulos utiles para estudio y trabajo'),
('Gaming', 'Perifericos y accesorios para jugadores')
ON CONFLICT (nombre)
DO UPDATE SET descripcion = EXCLUDED.descripcion;

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Laptop Pro 14', 'Laptop ultraligera para productividad y estudio.', 18999.00, 8, 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Electronica'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Laptop Pro 14');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Smartphone Nova X', 'Pantalla AMOLED, bateria de larga duracion y camara dual.', 12999.00, 20, 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Electronica'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Smartphone Nova X');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Tablet Smart Note', 'Tablet ideal para lectura, clases y videollamadas.', 7499.00, 12, 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Electronica'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Tablet Smart Note');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Audifonos Wave ANC', 'Cancelacion de ruido y autonomia de 30 horas.', 2399.00, 30, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Audio'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Audifonos Wave ANC');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Bocina Pulse Mini', 'Bocina bluetooth portatil con sonido 360.', 1599.00, 25, 'https://images.unsplash.com/photo-1545454675-3531b543be5d?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Audio'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Bocina Pulse Mini');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Microfono StreamCast', 'Microfono USB para videoclases y streaming.', 1799.00, 16, 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Audio'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Microfono StreamCast');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Silla Ergonomica Office+', 'Soporte lumbar y ajuste de altura para jornadas largas.', 3499.00, 10, 'https://images.unsplash.com/photo-1580480055273-228ff5388ef8?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Oficina'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Silla Ergonomica Office+');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Teclado Mecanico Typing Pro', 'Teclado mecanico ideal para oficina y programacion.', 1899.00, 22, 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Oficina'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Teclado Mecanico Typing Pro');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Monitor UltraView 27"', 'Monitor 2K para productividad con gran nitidez.', 4999.00, 14, 'https://images.unsplash.com/photo-1527443224154-c4f0617d1f42?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Oficina'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Monitor UltraView 27"');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Mouse Gamer Viper', 'Mouse RGB de alta precision con 8 botones.', 999.00, 40, 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Gaming'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Mouse Gamer Viper');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Headset Titan 7.1', 'Audio envolvente para sesiones gaming largas.', 1999.00, 18, 'https://images.unsplash.com/photo-1599669454699-248893623440?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Gaming'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Headset Titan 7.1');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Silla Gamer Storm', 'Diseno reclinable con cojines ergonomicos.', 5299.00, 9, 'https://images.unsplash.com/photo-1592078615290-033ee584e267?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Gaming'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Silla Gamer Storm');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Smartwatch Orbit', 'Reloj inteligente con monitoreo de salud y notificaciones.', 2799.00, 26, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Electronica'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Smartwatch Orbit');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Camara Vision 4K', 'Camara compacta para creadores de contenido y viajes.', 6899.00, 11, 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Electronica'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Camara Vision 4K');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Router Mesh Air', 'Cobertura WiFi estable para casa y oficina.', 1899.00, 24, 'https://images.unsplash.com/photo-1642121598054-9f6f2f8f5a6f?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Electronica'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Router Mesh Air');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Earbuds Neo Lite', 'Audifonos in-ear compactos con estuche de carga rapida.', 999.00, 35, 'https://images.unsplash.com/photo-1588423771073-b8903fbb85b5?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Audio'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Earbuds Neo Lite');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Soundbar Cinema Beat', 'Barra de sonido para una experiencia envolvente en casa.', 3299.00, 13, 'https://images.unsplash.com/photo-1545454675-3531b543be5d?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Audio'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Soundbar Cinema Beat');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Consola Mixer Audio Home', 'Mezclador basico para podcasts, clases y streaming.', 2499.00, 14, 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Audio'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Consola Mixer Audio Home');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Escritorio Elevable Flex', 'Escritorio ajustable para trabajar sentado o de pie.', 6399.00, 7, 'https://images.unsplash.com/photo-1593476550610-87baa860004a?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Oficina'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Escritorio Elevable Flex');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Lampara LED Focus', 'Lampara de escritorio con intensidad regulable.', 699.00, 44, 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Oficina'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Lampara LED Focus');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Webcam Pro Meet', 'Webcam Full HD con microfono integrado para videollamadas.', 1199.00, 29, 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Oficina'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Webcam Pro Meet');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Teclado Gamer Phantom', 'Teclado RGB de respuesta rapida para partidas competitivas.', 2299.00, 21, 'https://images.unsplash.com/photo-1595225476474-87563907a212?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Gaming'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Teclado Gamer Phantom');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Control HyperPad X', 'Control inalambrico ergonomico para gaming casual.', 1599.00, 33, 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Gaming'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Control HyperPad X');

INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
SELECT c.id_categoria, 'Mousepad RGB Titan', 'Superficie amplia con borde RGB y base antideslizante.', 499.00, 55, 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?auto=format&fit=crop&w=1200&q=80', 'activo'
FROM categorias c
WHERE c.nombre = 'Gaming'
AND NOT EXISTS (SELECT 1 FROM productos p WHERE p.nombre = 'Mousepad RGB Titan');

-- Credenciales demo:
-- admin@shopsmart.local / Admin123!
-- cliente@shopsmart.local / Cliente123!
INSERT INTO usuarios (nombre, email, password_hash, id_rol, saldo, activo)
SELECT 'Administrador ShopSmart', 'admin@shopsmart.local', '$2b$12$87jmC6tx7FKgvS34aixu9uUWEbKHYcCqSm3CUAY8a1V6NT8j7YeRu', r.id_rol, 0.00, TRUE
FROM roles r
WHERE r.nombre = 'admin'
AND NOT EXISTS (SELECT 1 FROM usuarios u WHERE u.email = 'admin@shopsmart.local');

INSERT INTO usuarios (nombre, email, password_hash, id_rol, saldo, activo)
SELECT 'Cliente Demo ShopSmart', 'cliente@shopsmart.local', '$2b$12$iKxrYEg22iKrF.LAOJABsO4B2YgfFE4hpPTVkkA.8iCspTej0rOVW', r.id_rol, 0.00, TRUE
FROM roles r
WHERE r.nombre = 'cliente'
AND NOT EXISTS (SELECT 1 FROM usuarios u WHERE u.email = 'cliente@shopsmart.local');

INSERT INTO carritos (id_usuario, estado)
SELECT u.id_usuario, 'activo'
FROM usuarios u
WHERE u.email = 'admin@shopsmart.local'
AND NOT EXISTS (
    SELECT 1
    FROM carritos c
    WHERE c.id_usuario = u.id_usuario AND c.estado = 'activo'
);

INSERT INTO carritos (id_usuario, estado)
SELECT u.id_usuario, 'activo'
FROM usuarios u
WHERE u.email = 'cliente@shopsmart.local'
AND NOT EXISTS (
    SELECT 1
    FROM carritos c
    WHERE c.id_usuario = u.id_usuario AND c.estado = 'activo'
);
