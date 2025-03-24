<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'finanzas_db');

// Procesar el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['nuevo_usuario'];
    $contraseña = $_POST['nueva_contraseña'];

    // Verificar si el usuario ya existe
    $verificarUsuario = "SELECT * FROM usuarios WHERE nombre_usuario = '$usuario'";
    $resultado = $conn->query($verificarUsuario);

    if ($resultado->num_rows > 0) {
        $errorRegistro = "El nombre de usuario ya existe. Por favor, elige otro.";
    } else {
        // Cifrar la contraseña
        $contraseña_cifrada = password_hash($contraseña, PASSWORD_BCRYPT);

        // Insertar el usuario en la base de datos
        $query = "INSERT INTO usuarios (nombre_usuario, contraseña) VALUES ('$usuario', '$contraseña_cifrada')";
        if ($conn->query($query)) {
            $successRegistro = "Usuario registrado con éxito.";
        } else {
            $errorRegistro = "Error al registrar usuario: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <!-- Formulario de Registro -->
    <div class="form-box">
        <h1>Registrar Usuario</h1>
        <form method="POST">
            <input type="text" name="nuevo_usuario" placeholder="Nuevo Usuario" required>
            <input type="password" name="nueva_contraseña" placeholder="Contraseña" required>
            <button type="submit" name="registro">Registrar</button>
            <?php if (isset($errorRegistro)): ?>
                <p style="color: red;"><?php echo $errorRegistro; ?></p>
            <?php endif; ?>
            <?php if (isset($successRegistro)): ?>
                <p style="color: green;"><?php echo $successRegistro; ?></p>
                <a href="login.php" style="display: block; margin-top: 10px; text-align: center;">Iniciar sesión</a>
            <?php endif; ?>
        </form>
    </div>

    <a href="login.php" style="display: inline-block; margin: 10px 0; padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Volver al Login</a>

</body>
</html>
