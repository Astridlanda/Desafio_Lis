<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
            text-align: center;
        }
        h1 {
            color: #28a745;
            margin-bottom: 15px;
        }
        nav {
            margin: 20px 0;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            margin: 10px 0;
        }
        nav ul li a {
            display: block;
            text-decoration: none;
            color: white;
            background-color: #28a745;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        nav ul li a:hover {
            background-color: #218838;
        }
        .logout {
            text-decoration: none;
            color: white;
            background-color: #dc3545;
            padding: 10px 15px;
            border-radius: 5px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        .logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <?php echo $_SESSION['usuario']; ?>!</h1>
        <nav>
            <ul>
                <li><a href="registrar_entrada.php">Registrar Entrada</a></li>
                <li><a href="registrar_salida.php">Registrar Salida</a></li>
                <li><a href="ver_entradas.php">Ver Entradas</a></li>
                <li><a href="ver_salidas.php">Ver Salidas</a></li>
                <li><a href="balance.php">Mostrar Balance</a></li>
                <li><a href="registro_usuario.php">Agregar Usuario</a></li>
            </ul>
        </nav>
        <a href="logout.php" class="logout">Cerrar Sesión</a>
    </div>
</body>
</html>