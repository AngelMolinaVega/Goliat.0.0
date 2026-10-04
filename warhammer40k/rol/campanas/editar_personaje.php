<?php
// Script para que un participante edite su ficha (facción y nombre de personaje) dentro de una campaña
session_start();

// Comprobación de sesión: si no hay usuario logueado, redirige al login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../../login.php');
    exit();
}

include '../../../includes/conexion.php';

$campana_id = $_GET['id'] ?? 0;
$usuario_id = $_SESSION['usuario_id'];

// Comprobamos que este usuario participa en la campaña
$stmt = $conexion->prepare("SELECT * FROM wh40k_campana_participantes WHERE campana_id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $campana_id, $usuario_id);
$stmt->execute();
$participante = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Si no es participante de esta campaña, no puede editar nada
if (!$participante) {
    die("No participas en esta campaña.");
}

// Procesamiento del formulario de edición de ficha
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faccion = trim($_POST['faccion']);
    $nombre_personaje = trim($_POST['nombre_personaje']);

    $stmt = $conexion->prepare("UPDATE wh40k_campana_participantes SET faccion = ?, nombre_personaje = ? WHERE campana_id = ? AND usuario_id = ?");
    $stmt->bind_param("ssii", $faccion, $nombre_personaje, $campana_id, $usuario_id);
    $stmt->execute();
    $stmt->close();

    header("Location: ver_campana.php?id=$campana_id");
    exit();
}

// Consulta de las facciones disponibles en la tabla de unidades, para el desplegable
$facciones_disponibles = $conexion->query("SELECT DISTINCT faccion FROM unidades ORDER BY faccion");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu personaje - Goliat</title>
    <link rel="stylesheet" href="../../css/estilo.css">
</head>
<body>
<h1>Tu personaje</h1>
<p><a href="ver_campana.php?id=<?php echo $campana_id; ?>">&laquo; Volver a la campaña</a></p>

<form method="POST">
    <!-- Desplegable con las facciones disponibles, extraídas de la tabla unidades -->
    <label>Facción que juegas:</label><br>
    <select name="faccion" required>
        <option value="">-- Elige una facción --</option>
        <?php while ($f = $facciones_disponibles->fetch_assoc()): ?>
            <option value="<?php echo htmlspecialchars($f['faccion']); ?>"
                <?php echo ($participante['faccion'] === $f['faccion']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($f['faccion']); ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Nombre del personaje:</label><br>
    <input type="text" name="nombre_personaje" value="<?php echo htmlspecialchars($participante['nombre_personaje'] ?? ''); ?>"><br><br>

    <button type="submit">Guardar</button>
</form>
</body>
</html>
