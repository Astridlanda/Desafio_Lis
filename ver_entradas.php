<?php
// Iniciar sesión y conectar a la base de datos
session_start();
$conn = new mysqli('localhost', 'root', '', 'finanzas_db');

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consultar las entradas
$query = "SELECT tipo, monto, fecha, factura FROM entradas";
$resultado = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entradas Registradas</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h1>Entradas Registradas</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Factura</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fila['tipo']); ?></td>
                        <td><?php echo htmlspecialchars($fila['monto']); ?></td>
                        <td><?php echo htmlspecialchars($fila['fecha']); ?></td>
                        <td>
                            <?php if (file_exists($fila['factura'])): ?>
                                <a href="<?php echo $fila['factura']; ?>" target="_blank">Ver Factura</a>
                            <?php else: ?>
                                No disponible
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No hay entradas registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="dashboard.php" style="display: inline-block; margin: 10px 0; padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Volver al Dashboard</a>
</body>
</html>
<?php $conn->close(); ?>
