-- ShopSmart PostgreSQL schema
-- Compatible with Railway Postgres

CREATE TABLE IF NOT EXISTS roles (
    id_rol INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL UNIQUE,
    descripcion VARCHAR(150)
);

CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    id_rol INTEGER NOT NULL REFERENCES roles(id_rol),
    saldo NUMERIC(10,2) NOT NULL DEFAULT 0.00,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    fecha_registro TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    fecha_actualizacion TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS categorias (
    id_categoria INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL UNIQUE,
    descripcion VARCHAR(180)
);

CREATE TABLE IF NOT EXISTS productos (
    id_producto INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_categoria INTEGER NOT NULL REFERENCES categorias(id_categoria),
    nombre VARCHAR(120) NOT NULL,
    descripcion TEXT,
    precio NUMERIC(10,2) NOT NULL CHECK (precio >= 0),
    stock INTEGER NOT NULL DEFAULT 0 CHECK (stock >= 0),
    imagen_url VARCHAR(255),
    estado VARCHAR(20) NOT NULL DEFAULT 'activo' CHECK (estado IN ('activo', 'inactivo')),
    fecha_creacion TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    fecha_actualizacion TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS carritos (
    id_carrito INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_usuario INTEGER NOT NULL REFERENCES usuarios(id_usuario),
    estado VARCHAR(20) NOT NULL DEFAULT 'activo' CHECK (estado IN ('activo', 'cerrado')),
    fecha_creacion TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    fecha_actualizacion TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS detalle_carrito (
    id_detalle_carrito INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_carrito INTEGER NOT NULL REFERENCES carritos(id_carrito) ON DELETE CASCADE,
    id_producto INTEGER NOT NULL REFERENCES productos(id_producto),
    cantidad INTEGER NOT NULL DEFAULT 1 CHECK (cantidad > 0),
    precio_unitario NUMERIC(10,2) NOT NULL CHECK (precio_unitario >= 0),
    subtotal NUMERIC(10,2) NOT NULL CHECK (subtotal >= 0),
    UNIQUE (id_carrito, id_producto)
);

CREATE TABLE IF NOT EXISTS pedidos (
    id_pedido INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_usuario INTEGER NOT NULL REFERENCES usuarios(id_usuario),
    total NUMERIC(10,2) NOT NULL CHECK (total >= 0),
    estado VARCHAR(20) NOT NULL DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'confirmado', 'cancelado')),
    fecha_pedido TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS detalle_pedidos (
    id_detalle_pedido INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    id_pedido INTEGER NOT NULL REFERENCES pedidos(id_pedido) ON DELETE CASCADE,
    id_producto INTEGER NOT NULL REFERENCES productos(id_producto),
    cantidad INTEGER NOT NULL CHECK (cantidad > 0),
    precio_unitario NUMERIC(10,2) NOT NULL CHECK (precio_unitario >= 0),
    subtotal NUMERIC(10,2) NOT NULL CHECK (subtotal >= 0)
);

CREATE INDEX IF NOT EXISTS idx_productos_estado ON productos(estado);
CREATE INDEX IF NOT EXISTS idx_carritos_usuario_estado ON carritos(id_usuario, estado);
CREATE INDEX IF NOT EXISTS idx_detalle_carrito_cart ON detalle_carrito(id_carrito);
CREATE INDEX IF NOT EXISTS idx_pedidos_usuario ON pedidos(id_usuario);
CREATE INDEX IF NOT EXISTS idx_detalle_pedidos_pedido ON detalle_pedidos(id_pedido);
CREATE UNIQUE INDEX IF NOT EXISTS idx_carritos_usuario_activo ON carritos(id_usuario) WHERE estado = 'activo';

CREATE OR REPLACE FUNCTION set_fecha_actualizacion()
RETURNS TRIGGER AS $$
BEGIN
    NEW.fecha_actualizacion = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS tr_usuarios_fecha_actualizacion ON usuarios;
CREATE TRIGGER tr_usuarios_fecha_actualizacion
BEFORE UPDATE ON usuarios
FOR EACH ROW
EXECUTE FUNCTION set_fecha_actualizacion();

DROP TRIGGER IF EXISTS tr_productos_fecha_actualizacion ON productos;
CREATE TRIGGER tr_productos_fecha_actualizacion
BEFORE UPDATE ON productos
FOR EACH ROW
EXECUTE FUNCTION set_fecha_actualizacion();

DROP TRIGGER IF EXISTS tr_carritos_fecha_actualizacion ON carritos;
CREATE TRIGGER tr_carritos_fecha_actualizacion
BEFORE UPDATE ON carritos
FOR EACH ROW
EXECUTE FUNCTION set_fecha_actualizacion();
