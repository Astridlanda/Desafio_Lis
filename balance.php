<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'finanzas_db');

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Obtener datos detallados de entradas y salidas
$queryEntradas = $conn->query("SELECT tipo, monto, fecha FROM entradas");
$entradas = $queryEntradas->fetch_all(MYSQLI_ASSOC);

$querySalidas = $conn->query("SELECT tipo, monto, fecha FROM salidas");
$salidas = $querySalidas->fetch_all(MYSQLI_ASSOC);

$totalEntradas = array_sum(array_column($entradas, 'monto'));
$totalSalidas = array_sum(array_column($salidas, 'monto'));

$balance = $totalEntradas - $totalSalidas;

// Función para formatear números
function escapar($valor) {
    return htmlspecialchars(number_format($valor, 2));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance</title>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf-autotable"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: auto;
            overflow: hidden;
        }
        .btn {
            display: inline-block;
            margin: 10px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        h1, h2, h3 {
            text-align: center;
        }
        .chart-container {
            width: 60%;
            margin: auto;
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .comparative-table {
            display: flex;
            justify-content: space-between;
        }
        .comparative-table table {
            width: 48%;
        }
        img {
            width: 100px;
            height: auto;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Balance General</h1>
    <h3>Proporción Entradas vs Salidas</h3>
    <div class="chart-container" style="width: 400px; height: 400px; margin: auto;">
        <canvas id="balanceChart"></canvas>
    </div>

    <div class="comparative-table">
        <div>
            <h3>Detalles de Entradas</h3>
            <table>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entradas as $entrada): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($entrada['tipo']); ?></td>
                        <td>$<?php echo escapar($entrada['monto']); ?></td>
                        <td><?php echo htmlspecialchars($entrada['fecha']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div>
            <h3>Detalles de Salidas</h3>
            <table>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($salidas as $salida): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($salida['tipo']); ?></td>
                        <td>$<?php echo escapar($salida['monto']); ?></td>
                        <td><?php echo htmlspecialchars($salida['fecha']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <button id="generarPDF" class="btn">Generar PDF</button>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const totalEntradas = <?php echo $totalEntradas; ?>;
        const totalSalidas = <?php echo $totalSalidas; ?>;
        const dataEntradas = <?php echo json_encode($entradas); ?>;

        // Crear gráfico
        const ctx = document.getElementById('balanceChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Entradas', 'Salidas'],
                datasets: [{
                    data: [totalEntradas, totalSalidas],
                    backgroundColor: ['#4CAF50', '#FF5733'],
                }]
            },
        });

        // Evento para generar PDF
        document.getElementById("generarPDF").addEventListener("click", function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Capturar gráfico como imagen
            const chartImage = chart.toBase64Image();

            doc.text("Balance General", 10, 10);
            doc.text(`Total Entradas: $${totalEntradas.toFixed(2)}`, 10, 20);
            doc.text(`Total Salidas: $${totalSalidas.toFixed(2)}`, 10, 30);
            doc.text(`Balance Final: $${(totalEntradas - totalSalidas).toFixed(2)}`, 10, 40);

            // Agregar tabla
            doc.autoTable({
                startY: 50,
                head: [['Tipo', 'Monto', 'Fecha']],
                body: dataEntradas.map(entry => [entry.tipo, `$${entry.monto}`, entry.fecha]),
            });

            // Agregar gráfico al PDF
            doc.addImage(chartImage, 'PNG', 10, doc.lastAutoTable.finalY + 10, 180, 100);

            doc.save('Balance.pdf');
        });
    });
</script>
</body>
</html>
