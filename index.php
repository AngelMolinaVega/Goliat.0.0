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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goliat</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
  <div class="header-bar">
    <h1>Goliat</h1>
    <p class="user-info">
        Hola, <?php echo $_SESSION['nombre_usuario']; ?>
        | <a href="logout.php">Cerrar sesión</a>
    </p>
  </div>
    <h2>Elige un juego</h2>

    <ul>
        <li><a href="warhammer40k/index.php">Warhammer 40.000</a></li>
    </ul>
</body>
</html>
