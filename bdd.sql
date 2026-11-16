CREATE DATABASE IF NOT EXISTS miniproyectoTaskshield
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE miniproyectoTaskshield;

CREATE TABLE IF NOT EXISTS tecnicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tareas_activas INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS incidencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    prioridad VARCHAR(20) NOT NULL,
    email_cliente VARCHAR(150) NOT NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'Pendiente',
    tecnico_id INT NULL,

    CONSTRAINT fk_incidencias_tecnicos
    FOREIGN KEY (tecnico_id)
    REFERENCES tecnicos(id)
) ENGINE=InnoDB;

-- UNA VEZ CREADO LA BASE DE DATO CON LAS TABLAS LE AÑADIMOS LOS TECNICOS CON : --
INSERT INTO tecnicos (nombre) VALUES
('Sergio'),
('Rafa'),
('Jesus');

--CREAMOS TAMBIEN UNA INCIDENCIA PARA PROBAR--
INSERT INTO incidencias (titulo, descripcion, prioridad, email_cliente, tecnico_id) 
VALUES (
    'Fallo de conexión a la impresora de red', 
    'El equipo de recepción no detecta la impresora HP del pasillo.', 
    'Media', 
    'recepcion@empresa.com', 
    NULL
);