<?php
session_start();
require_once __DIR__ . '/../backend/models/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Inicializar $resultado
$resultado = [];
$totalesPorCategoria = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meses']) && isset($_POST['anios'])) {
    try {
        $db = Database::connect();
        $sql = 'SELECT g.id_gasto, g.cantidad, g.descripcion, g.fecha_gasto, e.nombre as categoria
                FROM gastos g
                JOIN etiquetas e ON g.id_etiqueta = e.id_etiqueta
                WHERE g.id_usuario = :id_usuario
                AND MONTH(g.fecha_gasto) = :mes
                AND YEAR(g.fecha_gasto) = :anio
                AND g.eliminado = 0
                ORDER BY g.fecha_gasto DESC';

        $stm = $db->prepare($sql);
        $stm->execute([
            ':id_usuario' => $_SESSION['user_id'],
            ':mes' => $_POST['meses'],
            ':anio' => $_POST['anios']
        ]);

        $resultado = $stm->fetchAll();

        $sqlTotalesPorCategoria = 'SELECT e.nombre as categoria, SUM(g.cantidad) as total_categoria
        FROM gastos g
        JOIN etiquetas e ON g.id_etiqueta = e.id_etiqueta
        WHERE g.id_usuario = :id_usuario
        AND MONTH(g.fecha_gasto) = :mes
        AND YEAR(g.fecha_gasto) = :anio
        AND g.eliminado = 0
        GROUP BY e.nombre
        ORDER BY total_categoria DESC';


        $stmTotales = $db->prepare($sqlTotalesPorCategoria);
        $stmTotales->execute([
            ':id_usuario' => $_SESSION['user_id'],
            ':mes' => $_POST['meses'],
            ':anio' => $_POST['anios']
        ]);

        $totalesPorCategoria = $stmTotales->fetchAll();
    } catch (PDOException $e) {
        error_log('Error en la consulta: ' . $e->getMessage());
    }
}


if (!empty($_GET['mes']) || !empty($_GET['anio'])) : ?>


<?php
    $db = Database::connect();
    $sql = 'SELECT g.id_gasto, g.cantidad, g.descripcion, g.fecha_gasto, e.nombre as categoria
                    FROM gastos g
                    JOIN etiquetas e ON g.id_etiqueta = e.id_etiqueta
                    WHERE g.id_usuario = :id_usuario
                    AND MONTH(g.fecha_gasto) = :mes
                    AND YEAR(g.fecha_gasto) = :anio
                    AND g.eliminado = 0
                    ORDER BY g.fecha_gasto DESC';

    $stm = $db->prepare($sql);
    $stm->execute([
        ':id_usuario' => $_SESSION['user_id'],
        ':mes' => $_GET['mes'],
        ':anio' => $_GET['anio']
    ]);


    $resultado = $stm->fetchAll();

    $sqlTotalesPorCategoria = 'SELECT e.nombre as categoria, SUM(g.cantidad) as total_categoria
        FROM gastos g
        JOIN etiquetas e ON g.id_etiqueta = e.id_etiqueta
        WHERE g.id_usuario = :id_usuario
        AND MONTH(g.fecha_gasto) = :mes
        AND YEAR(g.fecha_gasto) = :anio
        AND g.eliminado = 0
        GROUP BY e.nombre
        ORDER BY total_categoria DESC';


    $stmTotales = $db->prepare($sqlTotalesPorCategoria);
    $stmTotales->execute([
        ':id_usuario' => $_SESSION['user_id'],
        ':mes' => $_GET['mes'],
        ':anio' => $_GET['anio']
    ]);

    $totalesPorCategoria = $stmTotales->fetchAll();

endif;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include 'header.php'; ?>

    <main class="historial">
        <?php if ((isset($_POST['meses']) && isset($_POST['anios'])) ||  (!empty($_GET['mes']) || !empty($_GET['anio']))) : ?>
        <div class="historialContainer">
            <div class="tabla-historial">
                <table id="tablaGastos" class="historial-tabla">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                            <th>Etiqueta</th>
                            <th>Descripción</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (is_array($resultado)) : ?>
                        <?php foreach ($resultado as $gasto) : ?>
                        <tr>
                            <td><?= $gasto['fecha_gasto'] ?></td>
                            <td><?= $gasto['cantidad'] ?></td>
                            <td><?= $gasto['categoria'] ?></td>
                            <td><?= $gasto['descripcion'] ?></td>
                            <td>
                                <form action="edit.php" method="POST" class="editarHistorial">
                                    <button type="submit" name="id" value="<?= $gasto['id_gasto'] ?>">Editar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else : ?>
                        <tr>
                            <td colspan="5">No hay datos disponibles.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="historial-total">
                <h2>Total</h2>
                <p class="historial-total-cantidad">
                    <?php
                        $total = 0;
                        foreach ($resultado as $gasto) {
                            $total += $gasto['cantidad'];
                        }
                        echo $total;
                        ?>
                </p>

                <div class="historial-categorias">
                    <table class="historial-categorias-tabla">
                        <thead>
                            <tr>
                                <th>Categoría</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($totalesPorCategoria as $categoria) : ?>
                            <tr>
                                <td><?= htmlspecialchars($categoria['categoria']) ?></td>
                                <td><?= number_format($categoria['total_categoria'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="grafico-circular">
                    <canvas id="graficoCircular"></canvas>
                </div>
            </div>

            <script>
            let etiquetas = <?= json_encode(array_column($totalesPorCategoria, 'categoria')) ?>;
            let totales = <?= json_encode(array_column($totalesPorCategoria, 'total_categoria')) ?>;

            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('graficoCircular').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: etiquetas,
                        datasets: [{
                            label: 'Distribución de gastos por categoría',
                            data: totales,
                            backgroundColor: [
                                'rgb(255, 99, 133)',
                                'rgba(54, 162, 235)',
                                'rgba(255, 206, 86)',
                                'rgba(75, 192, 192)',
                                'rgba(153, 102, 255)',
                                'rgba(255, 159, 64)'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.label}: ${context.raw} €`;
                                    }
                                }
                            }
                        }
                    }
                });
            });
            </script>

            <script>
            let table = new DataTable('#tablaGastos', {
                // options:
            });
            </script>

        </div>
        <?php else : ?>

        <form action="" method="POST" class="form-historial">
            <div class="historial-selects">
                <select name="meses" id="meses" class="historial-select">
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>

                <select name="anios" id="anios" class="historial-select">
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>
            </div>

            <button type="submit" class="historial-boton">Buscar</button>
        </form>
        <?php endif; ?>
    </main>

</body>

</html>