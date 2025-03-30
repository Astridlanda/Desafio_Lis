-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS finanzas_db;
USE finanzas_db;

--Creacion de tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL,
    contraseña VARCHAR(255) NOT NULL
);

--Creacion de tabla de entradas
CREATE TABLE entradas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50),
    monto DECIMAL(10, 2),
    fecha DATE,
    factura VARCHAR(255)
);

--Creacion de la tabla de salidas
CREATE TABLE salidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50),
    monto DECIMAL(10, 2),
    fecha DATE,
    factura VARCHAR(255)
);