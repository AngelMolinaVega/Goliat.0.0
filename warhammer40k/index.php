<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

include '../includes/conexion.php';
$usuario_id = $_SESSION['usuario_id'];
$mis_listas = $conexion->query("SELECT * FROM wh40k_listas WHERE usuario_id = $usuario_id ORDER BY fecha_creacion DESC");
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
        | <a href="../logout.php">Cerrar sesión</a>
    </p>
</div>
<p><a href="../index.php">&laquo; Volver</a></p>
<p><a href="elegir_faccion.php">+ Crear lista</a></p>
<p><a href="rol/index.php">Rol / Campañas</a></p>
<h2>Tus listas</h2>
<?php if ($mis_listas->num_rows > 0): ?>
    <table border="1">
        <tr><th>Nombre</th><th>Facción</th><th>Puntos</th><th></th></tr>
        <?php while ($fila = $mis_listas->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($fila['nombre_lista']); ?></td>
            <td><?php echo htmlspecialchars($fila['faccion']); ?></td>
            <td><?php echo $fila['puntos_totales']; ?></td>
	    <td>
    		<a href="gestionar_lista.php?id=<?php echo $fila['id']; ?>">Ver/editar</a>
    		| <a href="borrar_lista.php?id=<?php echo $fila['id']; ?>" onclick="return confirm('¿Seguro que quieres borrar esta lista?');">Borrar</a>
	    </td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>Aún no tienes listas creadas.</p>
<?php endif; ?>

</body>
</html>
