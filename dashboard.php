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
    <title>Menú</title>
</head>
<body>
    <h1>Bienvenido, <?php echo $_SESSION['usuario']; ?>!</h1>
    <nav>
    <ul>
        <li><a href="registrar_entrada.php">Registrar Entrada</a></li>
        <li><a href="registrar_salida.php">Registrar Salida</a></li>
        <li><a href="ver_entradas.php">Ver Entradas</a></li>
        <li><a href="ver_salidas.php">Ver Salidas</a></li>
        <li><a href="balance.php">Mostrar Balance</a></li>
        <li><a href="registro_usuario.php">Agregar Usuario</a></li> <!-- Nueva opción -->
    </ul>
</nav>

    <a href="logout.php">Cerrar Sesión</a>
    
</body>
</html>
