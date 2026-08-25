<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

include 'includes/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Goliat</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <h1>Goliat</h1>

    <p>
        Hola, <?php echo $_SESSION['nombre_usuario']; ?>
        | <a href="logout.php">Cerrar sesión</a>
    </p>

    <?php
    $resultado = $conexion->query("SELECT * FROM unidades");

    if ($resultado->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Nombre</th><th>Facción</th><th>Puntos</th><th>Ataque</th><th>Resistencia</th><th>Heridas</th></tr>";
        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila['nombre'] . "</td>";
            echo "<td>" . $fila['faccion'] . "</td>";
            echo "<td>" . $fila['puntos'] . "</td>";
            echo "<td>" . $fila['ataque'] . "</td>";
            echo "<td>" . $fila['resistencia'] . "</td>";
            echo "<td>" . $fila['heridas'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No hay unidades todavía.";
    }
    ?>
</body>
</html>
