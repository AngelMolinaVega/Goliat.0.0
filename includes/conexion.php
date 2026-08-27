<?php
$host = "localhost";
$usuario = "phpmyadmin";
$password = "Angelo-509_angelo."; 
$basedatos = "goliat_db";

$conexion = new mysqli($host, $usuario, $password, $basedatos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
