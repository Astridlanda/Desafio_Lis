<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'finanzas_db');

// Verificar si la carpeta "uploads" existe; si no, crearla
$uploadsDir = 'uploads';
if (!file_exists($uploadsDir)) {
    if (!mkdir($uploadsDir, 0777, true)) {
        die('No se pudo crear la carpeta "uploads". Verifica los permisos del servidor.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $factura = $_FILES['factura']['name'];
    $ruta = realpath($uploadsDir) . DIRECTORY_SEPARATOR . basename($factura);

    // Validar que el archivo sea una imagen
    $tipoArchivo = mime_content_type($_FILES['factura']['tmp_name']);
    if (strpos($tipoArchivo, 'image/') !== 0) {
        $error = "Solo se permiten archivos de imagen (JPEG, PNG, GIF, etc.).";
    } else {
        // Subir el archivo al servidor
        if (move_uploaded_file($_FILES['factura']['tmp_name'], $ruta)) {
            // Guardar los datos en la base de datos
            $query = "INSERT INTO entradas (tipo, monto, fecha, factura) VALUES ('$tipo', '$monto', '$fecha', '$ruta')";
            if ($conn->query($query)) {
                $success = "Entrada registrada con éxito.";
            } else {
                $error = "Error al guardar en la base de datos: " . $conn->error;
            }
        } else {
            $error = "Error al subir el archivo. Verifica los permisos de la carpeta 'uploads'.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Entrada</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h1>Registrar Nueva Entrada</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="tipo" placeholder="Tipo de Entrada" required>
        <input type="number" name="monto" placeholder="Monto" required>
        <input type="date" name="fecha" required>
        <input type="file" name="factura" accept="image/*" required>
        <button type="submit">Registrar</button>
        <?php if (isset($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
    </form>
    <a href="dashboard.php" style="display: inline-block; margin: 10px 0; padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Volver al Dashboard</a>
</body>
</html>
