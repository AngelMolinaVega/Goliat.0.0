<?php
// Script de listado de campañas: muestra las campañas creadas por el usuario y aquellas en las que participa
session_start();

// Comprobación de sesión: si no hay usuario logueado, redirige al login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../../login.php');
    exit();
}

include '../../../includes/conexion.php';

$usuario_id = $_SESSION['usuario_id'];

// Consulta de campañas donde el usuario es creador O participante (evita duplicados con DISTINCT)
$mis_campanas = $conexion->query("
    SELECT DISTINCT c.*, u.nombre_usuario AS nombre_creador
    FROM wh40k_campanas c
    JOIN usuarios u ON u.id = c.creador_id
    LEFT JOIN wh40k_campana_participantes p ON p.campana_id = c.id
    WHERE c.creador_id = $usuario_id OR p.usuario_id = $usuario_id
    ORDER BY c.fecha_creacion DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campañas - Goliat</title>
    <link rel="stylesheet" href="../../css/estilo.css">
</head>
<body>

<!-- Cabecera con título y saludo del usuario -->
<div class="header-bar">
    <h1>Campañas</h1>
    <p class="user-info">
        Hola, <?php echo $_SESSION['nombre_usuario']; ?>
        | <a href="../../../logout.php">Cerrar sesión</a>
    </p>
</div>

<p><a href="../index.php">&laquo; Volver</a></p>
<p><a href="crear_campana.php">+ Crear nueva campaña</a></p>

<h2>Tus campañas</h2>

<?php if ($mis_campanas->num_rows > 0): ?>
    <!-- Recorrido de cada campaña del usuario (propia o donde participa) -->
    <?php while ($fila = $mis_campanas->fetch_assoc()): ?>
    <div class="campana-wrapper">
        <a href="ver_campana.php?id=<?php echo $fila['id']; ?>" class="campana-card">
            <h3><?php echo htmlspecialchars($fila['nombre']); ?></h3>
            <p class="campana-meta">
                <?php echo htmlspecialchars($fila['edicion']); ?> ·
                <?php echo $fila['num_participantes']; ?> participante<?php echo $fila['num_participantes'] != 1 ? 's' : ''; ?>
            </p>
            <p class="campana-creador">👤 <?php echo htmlspecialchars($fila['nombre_creador']); ?></p>
        </a>
        <!-- El botón de borrar solo se muestra si el usuario actual es el creador de esta campaña -->
	<?php if ($fila['creador_id'] == $usuario_id): ?>
            <a href="borrar_campana.php?id=<?php echo $fila['id']; ?>" class="campana-borrar" onclick="return confirm('¿Seguro que quieres borrar esta campaña?');">Borrar</a>
	<?php endif; ?>
    </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Aún no tienes campañas creadas.</p>
<?php endif; ?>

</body>
</html>
