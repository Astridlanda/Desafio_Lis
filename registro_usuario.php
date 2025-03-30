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
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-box {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 300px;
            text-align: center;
        }
        .form-box h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #28a745;
        }
        .form-box input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-box button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-box button:hover {
            background-color: #218838;
        }
        .form-box p {
            font-size: 14px;
            margin-top: 10px;
        }
        .form-box a {
            text-decoration: none;
            color: #28a745;
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        .form-box a:hover {
            text-decoration: underline;
        }
    </style>
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
                <a href="login.php">Iniciar sesión</a>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>