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
            $query = "INSERT INTO salidas (tipo, monto, fecha, factura) VALUES ('$tipo', '$monto', '$fecha', '$ruta')";
            if ($conn->query($query)) {
                $success = "Salida registrada con éxito.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Salida</title>
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
        }
        h1 {
            font-size: 24px;
            color: #28a745;
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        form input, form button {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }
        form input[type="date"] {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            color: #333;
            background-color: #f4f4f4;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 8px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        form input[type="date"]:focus {
            border-color: #28a745;
            box-shadow: 0px 0px 4px rgba(40, 167, 69, 0.5);
        }
        form button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        form button:hover {
            background-color: #218838;
        }
        .message {
            text-align: center;
            margin-top: 10px;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: red;
        }
        .back-link {
            display: block;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registrar Nueva Salida</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="tipo" placeholder="Tipo de Salida" required>
            <input type="number" name="monto" placeholder="Monto" required>
            <input type="date" name="fecha" required>
            <input type="file" name="factura" accept="image/*" required>
            <button type="submit">Registrar</button>
        </form>
        <?php if (isset($success)): ?>
            <p class="message success"><?php echo $success; ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="message error"><?php echo $error; ?></p>
        <?php endif; ?>
        <a href="dashboard.php" class="back-link">Volver al Dashboard</a>
    </div>
</body>
</html>