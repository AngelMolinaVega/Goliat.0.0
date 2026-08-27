<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit();
}

include '../includes/conexion.php';

$lista_id = $_GET['id'] ?? 0;
$usuario_id = $_SESSION['usuario_id'];

// Comprobamos que la lista existe y pertenece a este usuario
$stmt = $conexion->prepare("SELECT * FROM wh40k_listas WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $lista_id, $usuario_id);
$stmt->execute();
$lista = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$lista) {
    die("Lista no encontrada o no tienes permiso para verla.");
}

// Añadir unidad a la lista
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unidad_id'])) {
    $unidad_id = (int) $_POST['unidad_id'];

    $stmt = $conexion->prepare("INSERT INTO wh40k_lista_unidades (lista_id, unidad_id, cantidad) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE cantidad = cantidad + 1");
    $stmt->bind_param("ii", $lista_id, $unidad_id);
    $stmt->execute();
    $stmt->close();

    // Recalcular puntos totales
    $conexion->query("UPDATE wh40k_listas SET puntos_totales = (
        SELECT COALESCE(SUM(u.puntos * lu.cantidad), 0)
        FROM wh40k_lista_unidades lu
        JOIN unidades u ON u.id = lu.unidad_id
        WHERE lu.lista_id = $lista_id
    ) WHERE id = $lista_id");

    header("Location: gestionar_lista.php?id=" . $lista_id);
    exit();
}

// Recargar puntos totales actualizados
$stmt = $conexion->prepare("SELECT puntos_totales FROM wh40k_listas WHERE id = ?");
$stmt->bind_param("i", $lista_id);
$stmt->execute();
$lista['puntos_totales'] = $stmt->get_result()->fetch_assoc()['puntos_totales'];
$stmt->close();

// Unidades disponibles de esa facción
$stmt = $conexion->prepare("SELECT * FROM unidades WHERE faccion = ?");
$stmt->bind_param("s", $lista['faccion']);
$stmt->execute();
$unidades_disponibles = $stmt->get_result();

// Unidades ya añadidas a esta lista
$unidades_en_lista = $conexion->query("
    SELECT u.id AS unidad_id, u.nombre, u.puntos, lu.cantidad
    FROM wh40k_lista_unidades lu
    JOIN unidades u ON u.id = lu.unidad_id
    WHERE lu.lista_id = $lista_id
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($lista['nombre_lista']); ?> - Goliat</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($lista['nombre_lista']); ?></h1>
    <p>Facción: <?php echo htmlspecialchars($lista['faccion']); ?> | Puntos totales: <?php echo $lista['puntos_totales']; ?></p>

    <p><a href="index.php">&laquo; Volver a mis listas</a></p>

    <h2>Unidades en tu lista</h2>
    <table border="1">
        <tr><th>Nombre</th><th>Puntos</th><th>Cantidad</th></tr>
        <?php while ($fila = $unidades_en_lista->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
            <td><?php echo $fila['puntos']; ?></td>
            <td><?php echo $fila['cantidad']; ?></td>
	    <td>
        	<form method="POST" action="quitar_unidad.php" style="display:inline;">
            	    <input type="hidden" name="lista_id" value="<?php echo $lista_id; ?>">
            	    <input type="hidden" name="unidad_id" value="<?php echo $fila['unidad_id']; ?>">
            	    <button type="submit">Quitar</button>
        	</form>
    	    </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Unidades disponibles (<?php echo htmlspecialchars($lista['faccion']); ?>)</h2>
    <table border="1">
        <tr><th>Nombre</th><th>Puntos</th><th>Ataque</th><th>Resistencia</th><th>Heridas</th><th></th></tr>
        <?php while ($fila = $unidades_disponibles->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
            <td><?php echo $fila['puntos']; ?></td>
            <td><?php echo htmlspecialchars($fila['ataque']); ?></td>
            <td><?php echo $fila['resistencia']; ?></td>
            <td><?php echo $fila['heridas']; ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="unidad_id" value="<?php echo $fila['id']; ?>">
                    <button type="submit">Añadir</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
