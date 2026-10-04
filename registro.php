<?php
include 'includes/conexion.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Ciframos la contraseña, nunca se guarda en texto plano
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Usamos consulta preparada para evitar inyección SQL
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre_usuario, $email, $password_hash);

    if ($stmt->execute()) {
        $mensaje = "¡Cuenta creada correctamente! Ya puedes iniciar sesión.";
    } else {
        if ($conexion->errno === 1062) {
            $mensaje = "Ese nombre de usuario o email ya está registrado.";
        } else {
            $mensaje = "Error al crear la cuenta: " . $conexion->error;
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Goliat</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <h1>Crear cuenta</h1>

    <?php if ($mensaje): ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form method="POST" action="registro.php">
        <label>Nombre de usuario:</label><br>
        <input type="text" name="nombre_usuario" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required minlength="6"><br><br>

        <button type="submit">Registrarme</button>
    </form>

    <p><a href="login.php">¿Ya tienes cuenta? Inicia sesión</a></p>
</body>
</html>
