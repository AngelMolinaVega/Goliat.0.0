<?php
session_start();
include 'includes/conexion.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $password = $_POST['password'];

    $stmt = $conexion->prepare("SELECT id, nombre_usuario, password_hash, rol FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $nombre_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario['password_hash'])) {
            // Contraseña correcta: guardamos datos en la sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
            $_SESSION['rol'] = $usuario['rol'];

            header("Location: index.php");
            exit();
        } else {
            $mensaje = "Contraseña incorrecta.";
        }
    } else {
        $mensaje = "Ese usuario no existe.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Goliat</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <h1>Iniciar sesión</h1>

    <?php if ($mensaje): ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>Nombre de usuario:</label><br>
        <input type="text" name="nombre_usuario" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Entrar</button>
    </form>

    <p><a href="registro.php">¿No tienes cuenta? Regístrate</a></p>
</body>
</html>
