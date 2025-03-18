<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'finanzas_db');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        // Proceso de inicio de sesión
        $usuario = $_POST['nombre_usuario'];
        $contraseña = $_POST['contraseña'];

        $query = "SELECT * FROM usuarios WHERE nombre_usuario = '$usuario'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($contraseña, $row['contraseña'])) {
                $_SESSION['usuario'] = $usuario;
                header('Location: dashboard.php');
                exit;
            } else {
                $errorLogin = "Contraseña incorrecta.";
            }
        } else {
            $errorLogin = "Usuario no encontrado.";
        }
    } elseif (isset($_POST['registro'])) {
        // Proceso de registro de nuevo usuario
        $nuevoUsuario = $_POST['nuevo_usuario'];
        $nuevaContraseña = $_POST['nueva_contraseña'];

        $verificarUsuario = "SELECT * FROM usuarios WHERE nombre_usuario = '$nuevoUsuario'";
        $resultado = $conn->query($verificarUsuario);

        if ($resultado->num_rows > 0) {
            $errorRegistro = "El nombre de usuario ya existe. Por favor, elige otro.";
        } else {
            $contraseñaCifrada = password_hash($nuevaContraseña, PASSWORD_BCRYPT);

            $query = "INSERT INTO usuarios (nombre_usuario, contraseña) VALUES ('$nuevoUsuario', '$contraseñaCifrada')";
            if ($conn->query($query)) {
                $successRegistro = "Usuario registrado con éxito. Ahora puedes iniciar sesión.";
            } else {
                $errorRegistro = "Error al registrar usuario: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión y Registro</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .container {
            display: flex;
            justify-content: space-around;
            margin-top: 50px;
        }
        .form-box {
            width: 40%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .form-box h1 {
            text-align: center;
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
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .form-box p {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Formulario de Login -->
        <div class="form-box">
            <h1>Iniciar Sesión</h1>
            <form method="POST">
                <input type="text" name="nombre_usuario" placeholder="Usuario" required>
                <input type="password" name="contraseña" placeholder="Contraseña" required>
                <button type="submit" name="login">Ingresar</button>
                <?php if (isset($errorLogin)): ?>
                    <p><?php echo $errorLogin; ?></p>
                <?php endif; ?>
            </form>
        </div>

 
</body>
</html>
