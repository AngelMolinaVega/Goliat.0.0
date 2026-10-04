<?php
// Script de invitación de usuarios a una campaña de rol
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../../login.php');
    exit();
}

include '../../../includes/conexion.php';

$campana_id = $_GET['id'] ?? 0;
$usuario_id = $_SESSION['usuario_id'];

// Comprobación de permisos: solo el creador de la campaña puede invitar
$stmt = $conexion->prepare("SELECT * FROM wh40k_campanas WHERE id = ? AND creador_id = ?");
$stmt->bind_param("ii", $campana_id, $usuario_id);
$stmt->execute();
$campana = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$campana) {
    die("No tienes permiso para invitar a esta campaña.");
}

$mensaje = "";

// Procesamiento del formulario de búsqueda + invitación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['invitar_usuario_id'])) {
    $invitado_id = (int) $_POST['invitar_usuario_id'];

    // Comprobamos que no esté ya invitado (evita duplicados por la UNIQUE KEY)
    $stmt = $conexion->prepare("INSERT IGNORE INTO wh40k_campana_participantes (campana_id, usuario_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $campana_id, $invitado_id);
    $stmt->execute();
    $stmt->close();

    // Recalculamos el número de participantes de la campaña
    $conexion->query("UPDATE wh40k_campanas SET num_participantes = (
        SELECT COUNT(*) FROM wh40k_campana_participantes WHERE campana_id = $campana_id
    ) WHERE id = $campana_id");

    $mensaje = "Usuario añadido a la campaña.";
}

// Consulta de todos los usuarios que aún no participan en esta campaña
$stmt = $conexion->prepare("
    SELECT u.id, u.nombre_usuario
    FROM usuarios u
    WHERE u.id NOT IN (
        SELECT usuario_id FROM wh40k_campana_participantes WHERE campana_id = ?
    )
    ORDER BY u.nombre_usuario
");
$stmt->bind_param("i", $campana_id);
$stmt->execute();
$usuarios_disponibles = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitar - Goliat</title>
    <link rel="stylesheet" href="../../css/estilo.css">
</head>
<body>
<h1>Invitar a <?php echo htmlspecialchars($campana['nombre']); ?></h1>
<p><a href="ver_campana.php?id=<?php echo $campana_id; ?>">&laquo; Volver a la campaña</a></p>

<?php if ($mensaje): ?>
    <p><?php echo htmlspecialchars($mensaje); ?></p>
<?php endif; ?>

<!-- Formulario desplegable para invitar a un usuario ya registrado -->
<?php if ($usuarios_disponibles->num_rows > 0): ?>
    <form method="POST">
        <label>Selecciona un usuario:</label><br>
        <select name="invitar_usuario_id" required>
            <option value="">-- Elige un usuario --</option>
            <?php while ($fila = $usuarios_disponibles->fetch_assoc()): ?>
                <option value="<?php echo $fila['id']; ?>">
                    <?php echo htmlspecialchars($fila['nombre_usuario']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Invitar</button>
    </form>
<?php else: ?>
    <p>No hay usuarios disponibles para invitar.</p>
<?php endif; ?>
</body>
</html>
