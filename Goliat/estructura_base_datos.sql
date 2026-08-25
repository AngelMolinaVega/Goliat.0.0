-- Base de datos: goliat_db

CREATE TABLE unidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    faccion VARCHAR(100) NOT NULL,
    puntos INT NOT NULL,
    ataque VARCHAR(20),
    resistencia INT,
    heridas INT
);

CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash CHAR(60) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo TINYINT(1) DEFAULT 1,
    rol ENUM('usuario', 'admin') DEFAULT 'usuario'
);
