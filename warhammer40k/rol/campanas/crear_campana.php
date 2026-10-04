<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../../login.php');
    exit();
}

include '../../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $edicion = trim($_POST['edicion']);
    $descripcion = trim($_POST['descripcion']);
    $creador_id = $_SESSION['usuario_id'];

    $stmt = $conexion->prepare("INSERT INTO wh40k_campanas (nombre, descripcion, edicion, creador_id, num_participantes) VALUES (?, ?, ?, ?, 1)");
    $stmt->bind_param("sssi", $nombre, $descripcion, $edicion, $creador_id);
    $stmt->execute();
    $campana_id = $stmt->insert_id;
    $stmt->close();

// El creador entra automáticamente como primer participante
    $stmt = $conexion->prepare("INSERT INTO wh40k_campana_participantes (campana_id, usuario_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $campana_id, $creador_id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Campaña - Goliat</title>
    <link rel="stylesheet" href="../../css/estilo.css">
</head>
<body>
<h1>Nueva campaña</h1>
<p><a href="index.php">&laquo; Volver</a></p>

<form method="POST">
    <label>Nombre de la campaña:</label><br>
    <input type="text" name="nombre" required><br><br>
    <label>Edición del juego (ej. D&D 5e, Warhammer 40k 10ª):</label><br>
    <input type="text" name="edicion" required><br><br>
    <label>Descripción:</label><br>
    <textarea name="descripcion" rows="4"></textarea><br><br>
    <button type="submit">Crear campaña</button>
</form>
</body>
</html>
