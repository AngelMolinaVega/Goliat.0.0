<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rol - Goliat</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
<div class="header-bar">
    <h1>Rol / Campañas</h1>
    <p class="user-info">
        Hola, <?php echo $_SESSION['nombre_usuario']; ?>
        | <a href="../../logout.php">Cerrar sesión</a>
    </p>
</div>
<p><a href="../index.php">&laquo; Volver</a></p>

<p>Próximamente: gestión de campañas y personajes.</p>
<p><a href="campanas/index.php">Campañas</a></p>
</body>
</html>
