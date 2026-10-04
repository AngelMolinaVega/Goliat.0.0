<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit();
}

include '../includes/conexion.php';

$resultado = $conexion->query("SELECT DISTINCT faccion FROM unidades ORDER BY faccion");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elegir Facción - Goliat</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <h1>Elige tu facción</h1>

    <p><a href="index.php">&laquo; Volver</a></p>

    <ul>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <li>
                <a href="crear_lista.php?faccion=<?php echo urlencode($fila['faccion']); ?>">
                    <?php echo htmlspecialchars($fila['faccion']); ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
