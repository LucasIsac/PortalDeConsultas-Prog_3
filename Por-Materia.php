<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/Por-Materia.css">
    <title>Consulta por Materia - UNLaR</title>
</head>

<body>
    <div class="pagina">

        
        <header class="titulo-sistema">
            <h1>Portal de Consultas Académicas - UNLaR</h1>
        </header>

        <main class="contenido">
            <h2>Notas de la materia</h2>

            <?php
            include "conexion.php";

            if (!$conexion) {
                echo "<p class='error'>Error en la conexión.</p>";
                exit;
            }

            if (!isset($_REQUEST["materia"])) {
                echo "<p class='error'>No se recibió el nombre de la materia.</p>";
                exit;
            }

            $materia = pg_escape_string($conexion, $_REQUEST["materia"]);

            $sql = "SELECT * FROM materias WHERE materia = '$materia'";
            $resultado = pg_query($conexion, $sql);

            if (!$resultado) {
                echo "<p class='error'>Error en la consulta: " . pg_last_error($conexion) . "</p>";
                exit;
            }

            if (!$reg = pg_fetch_array($resultado)) {
                echo "<p class='error'>Materia no encontrada.</p>";
                exit;
            }

            $id_materia = $reg["id_materia"];
            $anio = $reg["anio"];

            $sql = "SELECT notas.nota, notas.observacion, alumnos.nombre 
                    FROM notas 
                    INNER JOIN alumnos ON notas.idalumno = alumnos.id_alumno 
                    WHERE notas.idmateria = $id_materia";

            $resultado = pg_query($conexion, $sql);

            if (!$resultado) {
                echo "<p class='error'>Error en la consulta: " . pg_last_error($conexion) . "</p>";
                exit;
            }

            $notas = pg_fetch_all($resultado);

            echo "<h3>$materia - Año: $anio</h3>";

            if ($notas) {
            ?>
                <table class="tabla-notas">
                    <thead>
                        <tr>
                            <th>Nota</th>
                            <th>Observación</th>
                            <th>Alumno</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notas as $nota) : ?>
                            <tr>
                                <td><?= htmlspecialchars($nota['nota']) ?></td>
                                <td><?= htmlspecialchars($nota['observacion']) ?></td>
                                <td><?= htmlspecialchars($nota['nombre']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php
            } else {
                echo "<p class='info'>No hay notas registradas para esta materia.</p>";
            }
            ?>

            <a href="index.html" class="btn-volver">Volver</a>
        </main>
    </div>
</body>
</html>
