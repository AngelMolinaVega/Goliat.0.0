<?php
$host = "localhost";
$usuario = "root";
$password = ""; // por defecto en XAMPP no hay contraseña
$basedatos = "goliat_db";

$conexion = new mysqli($host, $usuario, $password, $basedatos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
