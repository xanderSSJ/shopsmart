# ShopSmart (PHP MVC + PostgreSQL + Railway)

Tienda online en PHP puro con arquitectura MVC simple y flujo e-commerce completo:
- Registro e inicio de sesion
- Catalogo publico con detalle de producto
- Carrito por usuario
- Checkout simulado con transaccion y control de stock
- Historial de pedidos
- Panel admin para CRUD de productos

## Stack
- PHP 8+
- PostgreSQL (Railway-ready)
- Apache (Docker para Railway)
- Bootstrap 5 + CSS personalizado

## Credenciales demo
- Admin: `admin@shopsmart.local / Admin123!`
- Cliente: `cliente@shopsmart.local / Cliente123!`

## Variables de entorno
Archivo base: `.env.example`

Variables clave:
- `DB_CONNECTION=pgsql`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `DB_SSLMODE=prefer`
- `DATABASE_URL` (si existe, tiene prioridad y se parsea automaticamente)

## Base de datos PostgreSQL
Scripts disponibles:
- `database/schema.sql` -> estructura
- `database/seeds.sql` -> datos demo (roles, categorias, 12 productos, usuarios)
- `database/install.sql` -> instalacion integral (schema + seeds)

### Instalacion local (PostgreSQL)
1. Crea tu base de datos (ejemplo):
   - `CREATE DATABASE shopsmart_db;`
2. Ejecuta `database/install.sql` en `shopsmart_db`.
3. Ajusta `.env` con tus credenciales.

## Ejecucion local
### Opcion 1: Apache (XAMPP/WAMP)
- Configura el DocumentRoot apuntando a `public/`.

### Opcion 2: Servidor embebido PHP
- Ejecuta:
  - `php -S 127.0.0.1:8080 server-router.php`
- Abre:
  - `http://127.0.0.1:8080`

## Despliegue en Railway (PostgreSQL)
1. Sube este proyecto a GitHub.
2. En Railway, crea `New Project -> Deploy from GitHub repo`.
3. Agrega un servicio `PostgreSQL` dentro del mismo proyecto.
4. En el servicio web, define variable:
   - `DATABASE_URL=${{Postgres.DATABASE_URL}}`
5. En la base PostgreSQL de Railway (Query), ejecuta:
   - `database/install.sql`
6. Redeploy del servicio web.

El proyecto incluye:
- `Dockerfile` con `pdo_pgsql` + Apache rewrite
- `railway.json` con healthcheck `/catalogo`

## Rutas principales
- `GET /`
- `GET /login`
- `POST /register`
- `POST /login`
- `POST /logout`
- `GET /catalogo`
- `GET /producto/{id}`
- `GET /carrito`
- `POST /carrito/agregar`
- `POST /carrito/actualizar`
- `POST /carrito/eliminar`
- `POST /checkout`
- `GET /mis-pedidos`
- `GET /admin/productos`
- `GET /admin/productos/crear`
- `POST /admin/productos`
- `GET /admin/productos/{id}/editar`
- `POST /admin/productos/{id}`
- `POST /admin/productos/{id}/eliminar`

## Seguridad implementada
- Passwords con `password_hash(PASSWORD_BCRYPT)`
- Verificacion con `password_verify`
- Sesiones seguras y cierre con invalidacion completa
- CSRF token en formularios POST
- Control de acceso por rol para rutas admin
- Checkout dentro de transaccion SQL con rollback en errores
