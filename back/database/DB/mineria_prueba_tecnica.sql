DROP DATABASE IF EXISTS db_equipos_mineros;

CREATE DATABASE IF NOT EXISTS db_equipos_mineros
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE db_equipos_mineros;

CREATE TABLE tipos_equipos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(60) COLLATE utf8mb4_unicode_ci NOT NULL,
    intervalo_mantenimiento INT NOT NULL,
    estado CHAR(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
    userinsert VARCHAR(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
    created_at TIMESTAMP NULL DEFAULT NULL,
    userupdate VARCHAR(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY uk_tipos_equipos_nombre (nombre),
    CONSTRAINT chk_tipos_intervalo CHECK (intervalo_mantenimiento > 0),
    CONSTRAINT chk_tipos_estado CHECK (estado IN ('A','E'))
);

-- TABLA: EQUIPOS
CREATE TABLE equipos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    tipo_equipo_id BIGINT UNSIGNED NOT NULL,
    horometro_actual DECIMAL(12,2) NOT NULL DEFAULT 0,
    horometro_ultimo_mantenimiento DECIMAL(12,2) NOT NULL DEFAULT 0,
    estado CHAR(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
    userinsert VARCHAR(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
    created_at TIMESTAMP NULL DEFAULT NULL,
    userupdate VARCHAR(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY uk_equipos_codigo (codigo),
    INDEX idx_equipos_tipo (tipo_equipo_id),
    INDEX idx_equipos_estado (estado),
    CONSTRAINT fk_equipos_tipo
        FOREIGN KEY (tipo_equipo_id)
        REFERENCES tipos_equipos(id),
    CONSTRAINT chk_equipos_horometro CHECK (horometro_actual >= 0),
    CONSTRAINT chk_equipos_ultimo_mantenimiento CHECK (horometro_ultimo_mantenimiento >= 0),
    CONSTRAINT chk_equipos_horometros CHECK (horometro_actual >= horometro_ultimo_mantenimiento),
    CONSTRAINT chk_equipos_estado CHECK (estado IN ('A','E'))
);

-- TABLA: MANTENIMIENTOS
CREATE TABLE mantenimientos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    equipo_id BIGINT UNSIGNED NOT NULL,
    horometro DECIMAL(12,2) NOT NULL,
    fecha_mantenimiento DATETIME NOT NULL,
    descripcion VARCHAR(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    estado CHAR(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
    userinsert VARCHAR(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
    created_at TIMESTAMP NULL DEFAULT NULL,
    userupdate VARCHAR(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_mantenimientos_equipo (equipo_id),
    INDEX idx_mantenimientos_fecha (fecha_mantenimiento),
    INDEX idx_mantenimientos_estado (estado),
    CONSTRAINT fk_mantenimientos_equipo
        FOREIGN KEY (equipo_id)
        REFERENCES equipos(id),
    CONSTRAINT chk_mantenimientos_horometro CHECK (horometro >= 0),
    CONSTRAINT chk_mantenimientos_estado CHECK (estado IN ('A','E'))
);

INSERT INTO tipos_equipos (nombre, intervalo_mantenimiento)
VALUES ('Camión', 500),
    ('Excavadora', 1000),
    ('Perforadora', 400);

-- VISTA EQUIPOS Y ESTADO DE MANTENIMIENTO


CREATE OR REPLACE VIEW vw_equipos_mantenimiento AS
SELECT
    e.id,
    e.codigo,
    t.nombre AS tipo_equipo,
    e.tipo_equipo_id,
    e.horometro_actual,
    e.horometro_ultimo_mantenimiento,
    t.intervalo_mantenimiento,

    (e.horometro_actual - e.horometro_ultimo_mantenimiento)
        AS horas_desde_mantenimiento,

    GREATEST(
        t.intervalo_mantenimiento -
        (e.horometro_actual - e.horometro_ultimo_mantenimiento),
        0
    ) AS horas_restantes,

    CASE
        WHEN (e.horometro_actual - e.horometro_ultimo_mantenimiento)
             >= t.intervalo_mantenimiento
        THEN 'Requiere mantenimiento'
        ELSE 'Operativo'
    END AS estado_mantenimiento,

    e.estado,
    e.userinsert,
    e.created_at,
    e.userupdate,
    e.updated_at

FROM equipos e

INNER JOIN tipos_equipos t
    ON t.id = e.tipo_equipo_id

WHERE e.estado = 'A'
  AND t.estado = 'A';


-- CONSULTAS:
-- Consultar tipos de equipos activos
SELECT *
FROM tipos_equipos
WHERE estado = 'A';

SELECT id, nombre, estado
FROM tipos_equipos
WHERE id = 6;

-- equipos activos y su estado de mantenimiento
SELECT *
FROM vw_equipos_mantenimiento;

-- quipos que requieren mantenimiento
SELECT *
FROM vw_equipos_mantenimiento
WHERE estado_mantenimiento = 'Requiere mantenimiento';

-- historial de mantenimientos de un equipo
SELECT
    m.id,
    e.codigo,
    m.horometro,
    m.fecha_mantenimiento,
    m.descripcion,
    m.userinsert,
    m.created_at
FROM mantenimientos m

INNER JOIN equipos e
    ON e.id = m.equipo_id

WHERE m.equipo_id = 1
  AND m.estado = 'A'

ORDER BY m.fecha_mantenimiento DESC;


select * from equipos;