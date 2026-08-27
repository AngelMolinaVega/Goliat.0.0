<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit();
}

include '../includes/conexion.php';

$lista_id = (int) ($_POST['lista_id'] ?? 0);
$unidad_id = (int) ($_POST['unidad_id'] ?? 0);
$usuario_id = $_SESSION['usuario_id'];

// Verificamos que la lista pertenece a este usuario antes de tocar nada
$stmt = $conexion->prepare("SELECT id FROM wh40k_listas WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $lista_id, $usuario_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    die("No tienes permiso sobre esta lista.");
}
$stmt->close();

$stmt = $conexion->prepare("UPDATE wh40k_lista_unidades SET cantidad = cantidad - 1 WHERE lista_id = ? AND unidad_id = ?");
$stmt->bind_param("ii", $lista_id, $unidad_id);
$stmt->execute();
$stmt->close();

// Si la cantidad llegó a 0, eliminamos la fila del todo
$conexion->query("DELETE FROM wh40k_lista_unidades WHERE lista_id = $lista_id AND unidad_id = $unidad_id AND cantidad <= 0");

$conexion->query("UPDATE wh40k_listas SET puntos_totales = (
    SELECT COALESCE(SUM(u.puntos * lu.cantidad), 0)
    FROM wh40k_lista_unidades lu
    JOIN unidades u ON u.id = lu.unidad_id
    WHERE lu.lista_id = $lista_id
) WHERE id = $lista_id");

header("Location: gestionar_lista.php?id=" . $lista_id);
exit();
?>
