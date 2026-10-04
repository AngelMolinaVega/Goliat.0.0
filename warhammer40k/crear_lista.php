<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit();
}

include '../includes/conexion.php';

$faccion = $_GET['faccion'] ?? '';
$mensaje = "";

// Si se envía el formulario para nombrar y crear la lista
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_lista'])) {
    $nombre_lista = trim($_POST['nombre_lista']);
    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conexion->prepare("INSERT INTO wh40k_listas (usuario_id, nombre_lista, faccion) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $usuario_id, $nombre_lista, $faccion);
    $stmt->execute();
    $lista_id = $stmt->insert_id;
    $stmt->close();

    header("Location: gestionar_lista.php?id=" . $lista_id);
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Lista - Goliat</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <h1>Nueva lista: <?php echo htmlspecialchars($faccion); ?></h1>

    <p><a href="elegir_faccion.php">&laquo; Volver</a></p>

    <form method="POST">
        <label>Nombre de la lista:</label><br>
        <input type="text" name="nombre_lista" required><br><br>
        <button type="submit">Crear lista</button>
    </form>
</body>
</html>
