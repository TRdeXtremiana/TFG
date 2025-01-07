<?php
session_start(); // Comienza la sesión para manejar los datos del usuario
require_once __DIR__ . '/../backend/models/Database.php'; // Incluye el archivo para conectar a la base de datos

// Verifica si el usuario está autenticado, si no lo está, lo redirige a la página de inicio de sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirige al usuario no autenticado
    exit(); // Detiene la ejecución del código
}

// Variables que almacenarán los datos de los gastos y los totales por categoría
$resultado = [];
$totalesPorCategoria = [];

// Si se envió un formulario con mes y año seleccionados, procesa la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meses']) && isset($_POST['anios'])) {
    try {
        $db = Database::connect(); // Conecta a la base de datos

        // Consulta para obtener los gastos del usuario según el mes y año seleccionados
        $sql = 'SELECT g.id_gasto, g.cantidad, g.descripcion, g.fecha_gasto, e.nombre as categoria
                FROM gastos g
                JOIN etiquetas e ON g.id_etiqueta = e.id_etiqueta
                WHERE g.id_usuario = :id_usuario
                AND MONTH(g.fecha_gasto) = :mes
                AND YEAR(g.fecha_gasto) = :anio
                AND g.eliminado = 0
                ORDER BY g.fecha_gasto DESC';

        $stm = $db->prepare($sql); // Prepara la consulta SQL
        $stm->execute([
            ':id_usuario' => $_SESSION['user_id'], // Pasa el ID del usuario autenticado
            ':mes' => $_POST['meses'], // Pasa el mes seleccionado en el formulario
            ':anio' => $_POST['anios'] // Pasa el año seleccionado en el formulario
        ]);

        $resultado = $stm->fetchAll(); // Obtiene los datos de los gastos

        // Consulta para obtener los totales por cada categoría de gasto
        $sqlTotalesPorCategoria = 'SELECT e.nombre as categoria, SUM(g.cantidad) as total_categoria
                                   FROM gastos g
                                   JOIN etiquetas e ON g.id_etiqueta = e.id_etiqueta
                                   WHERE g.id_usuario = :id_usuario
                                   AND MONTH(g.fecha_gasto) = :mes
                                   AND YEAR(g.fecha_gasto) = :anio
                                   AND g.eliminado = 0
                                   GROUP BY e.nombre
                                   ORDER BY total_categoria DESC';

        $stmTotales = $db->prepare($sqlTotalesPorCategoria); // Prepara la consulta SQL
        $stmTotales->execute([
            ':id_usuario' => $_SESSION['user_id'], // Pasa el ID del usuario autenticado
            ':mes' => $_POST['meses'], // Pasa el mes seleccionado en el formulario
            ':anio' => $_POST['anios'] // Pasa el año seleccionado en el formulario
        ]);

        $totalesPorCategoria = $stmTotales->fetchAll(); // Obtiene los totales por categoría
    } catch (PDOException $e) {
        error_log('Error en la consulta: ' . $e->getMessage()); // Registra errores en el archivo de log
    }
}

// Si hay parámetros de mes y año en la URL, realiza la consulta para mostrar los gastos correspondientes
if (!empty($_GET['mes']) || !empty($_GET['anio'])) :
    $db = Database::connect(); // Conecta a la base de datos

    // Consulta para obtener los gastos según los parámetros de la URL
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

    $resultado = $stm->fetchAll(); // Obtiene los gastos

    // Consulta para obtener los totales por categoría basados en los parámetros de la URL
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

    $totalesPorCategoria = $stmTotales->fetchAll(); // Obtiene los totales por categoría
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
    <?php include 'header.php'; // Incluir el encabezado de la página 
    ?>

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
            // Inicializa el gráfico circular
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
            // Inicializa la tabla de datos
            let table = new DataTable('#tablaGastos', {
                responsive: true,
                pageLength: 10,
                language: {
                    decimal: ',',
                    thousands: '.',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty: 'Mostrando 0 a 0 de 0 registros',
                    infoFiltered: '(filtrado de _MAX_ registros totales)',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    loadingRecords: 'Cargando...',
                    processing: 'Procesando...',
                    search: 'Buscar:',
                    zeroRecords: 'No se encontraron resultados',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior',
                    },
                    aria: {
                        sortAscending: ': activar para ordenar la columna ascendente',
                        sortDescending: ': activar para ordenar la columna descendente',
                    },
                },
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