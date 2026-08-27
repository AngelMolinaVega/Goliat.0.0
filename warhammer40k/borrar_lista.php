<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit();
}

include '../includes/conexion.php';

$lista_id = $_GET['id'] ?? 0;
$usuario_id = $_SESSION['usuario_id'];

$stmt = $conexion->prepare("DELETE FROM wh40k_listas WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $lista_id, $usuario_id);
$stmt->execute();
$stmt->close();

header("Location: index.php");
exit();
?>
