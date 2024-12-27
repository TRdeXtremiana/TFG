<?php
session_start();
require_once __DIR__ . '/../backend/models/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
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
</head>

<body>
    <?php include 'header.php'; ?>

    <main>
        <?php
        // Gestión general
        if (isset($_POST['meses']) && isset($_POST['anios'])) :
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
        ?>

        <!-- TODO ponerle nombre para que ocupe media pantalla -->
        <div>
            <table id="tablaGastos">
                <thead>
                    <tr>
                        <th>fecha</th>
                        <th>cantidad</th>
                        <th>etiqueta</th>
                        <th>descripcion</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        foreach ($resultado as $gasto) {
                            echo "<tr>";
                            echo "<td>{$gasto['fecha_gasto']}</td>";
                            echo "<td>{$gasto['cantidad']}</td>";
                            echo "<td>{$gasto['categoria']}</td>";
                            echo "<td>{$gasto['descripcion']}</td>";
                            echo "<td> <form action='edit.php' method='POST'> <button type='submit' name='id' value='{$gasto['id_gasto']}'> editar </button> </form> </td>";
                            echo "</tr>";
                        }
                        ?>
                </tbody>
            </table>
        </div>

        <script>
        let table = new DataTable('#tablaGastos', {
            // options:
        });
        </script>

        <?php
        // Selector por fecha
        else :
        ?>

        <form action="" method="POST">
            <select name="anios" id="anios">
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
            </select>

            <select name="meses" id="meses">
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


            <button type="submit">Buscar</button>
        </form>


        <?php
        endif;
        ?>

    </main>
</body>

</html>