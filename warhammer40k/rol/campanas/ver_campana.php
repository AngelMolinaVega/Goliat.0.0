<?php
// Script de visualización de una campaña de rol (vista general + vista según rol del usuario)
session_start();

// Comprobación de sesión: si no hay usuario logueado, redirige al login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../../login.php');
    exit();
}

include '../../../includes/conexion.php';

$campana_id = $_GET['id'] ?? 0;
$usuario_id = $_SESSION['usuario_id'];

// Consulta de los datos de la campaña
$stmt = $conexion->prepare("SELECT * FROM wh40k_campanas WHERE id = ?");
$stmt->bind_param("i", $campana_id);
$stmt->execute();
$campana = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Si la campaña no existe, detenemos la ejecución
if (!$campana) {
    die("Campaña no encontrada.");
}

// Comprobamos si el usuario actual es el creador de la campaña
$es_creador = ($campana['creador_id'] == $usuario_id);

// Consulta de todos los participantes de la campaña, con su nombre de usuario, facción y personaje
$participantes = $conexion->query("
    SELECT p.*, u.nombre_usuario
    FROM wh40k_campana_participantes p
    JOIN usuarios u ON u.id = p.usuario_id
    WHERE p.campana_id = $campana_id
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($campana['nombre']); ?> - Goliat</title>
    <link rel="stylesheet" href="../../css/estilo.css">
</head>
<body>

<!-- Cabecera con nombre de campaña y saludo del usuario -->
<div class="header-bar">
    <h1><?php echo htmlspecialchars($campana['nombre']); ?></h1>
    <p class="user-info">
        Hola, <?php echo $_SESSION['nombre_usuario']; ?>
        | <a href="../../../logout.php">Cerrar sesión</a>
    </p>
</div>

<p><a href="index.php">&laquo; Volver a campañas</a></p>
<p><?php echo htmlspecialchars($campana['edicion']); ?></p>
<p><?php echo htmlspecialchars($campana['descripcion']); ?></p>

<!-- Vista general: tabla visible para todos los participantes -->
<h2>Participantes</h2>
<table border="1">
    <tr><th>Usuario</th><th>Facción</th><th>Personaje</th></tr>
    <?php while ($fila = $participantes->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($fila['nombre_usuario']); ?></td>
        <td><?php echo htmlspecialchars($fila['faccion'] ?? '—'); ?></td>
        <td><?php echo htmlspecialchars($fila['nombre_personaje'] ?? '—'); ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<?php if ($es_creador): ?>
    <!-- Vista de creador: puede invitar jugadores y (más adelante) ver todas las unidades -->
    <h2>Vista de creador (ves todo)</h2>
    <p><a href="invitar.php?id=<?php echo $campana_id; ?>">+ Invitar jugadores</a></p>
    <p>Aquí más adelante verás las unidades detalladas de cada jugador.</p>
<?php else: ?>
    <!-- Vista de participante normal: puede editar su propia ficha -->
    <h2>Tu ficha</h2>
    <p><a href="editar_personaje.php?id=<?php echo $campana_id; ?>">Editar mi personaje</a></p>
<?php endif; ?>

</body>
</html>
