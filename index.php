<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Goliat</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <h1>Goliat</h1>

    <p>
        Hola, <?php echo $_SESSION['nombre_usuario']; ?>
        | <a href="logout.php">Cerrar sesión</a>
    </p>

    <h2>Elige un juego</h2>

    <ul>
        <li><a href="warhammer40k/index.php">Warhammer 40.000</a></li>
    </ul>
</body>
</html>
