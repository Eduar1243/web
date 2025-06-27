-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS base_carrito;

-- Usar la base de datos
USE base_carrito;

-- Crear la tabla de registros
CREATE TABLE IF NOT EXISTS registros_carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(250) NOT NULL,
    correo_electronico VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_correo (correo_electronico),
    INDEX idx_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 