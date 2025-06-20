<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/Por-Dni.css">
    <title>Consulta por DNI - UNLaR</title>
</head>
<body>
    <div class="pagina">

        
          <header class="titulo-sistema">
             <div class="titulo-con-logo">
                <img src="Logo-UNLAR.png" alt="UNLaR" class="logo-unlar">
                <h1>Portal de Consultas Académicas - UNLaR</h1>
              </div>
        </header>

        <main class="contenido">
            <h2>Notas del Alumno</h2>

            <?php
            $dni = $_REQUEST["dni"];

            include "conexion.php";
            if (!$conexion) {
                echo "<p class='error'>Error en la conexión con la base de datos.</p>";
            } else {
                $dni = pg_escape_string($conexion, $dni);
                $sql = "SELECT * FROM alumnos WHERE dni = '$dni'";
                $resultado = pg_query($conexion, $sql);

                if (!$reg = pg_fetch_array($resultado)) {
                    echo "<p class='error'><strong>Alumno no encontrado.</strong></p>";
                } else {
                    $id_alumno = $reg["id_alumno"];
                    $nombre = $reg["nombre"];
                    echo "<h3>$nombre</h3>";

                    $sql = "SELECT notas.nota, notas.observacion, materias.materia 
                            FROM notas 
                            INNER JOIN materias ON notas.idmateria = materias.id_materia 
                            WHERE notas.idalumno = '$id_alumno'";
                    $resultado = pg_query($conexion, $sql);

                    if (pg_num_rows($resultado) > 0) {
                        $notas = pg_fetch_all($resultado);
                        echo "<table class='tabla-notas'>
                                <thead>
                                    <tr>
                                        <th>Nota</th>
                                        <th>Observación</th>
                                        <th>Materia</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        foreach ($notas as $nota) {
                            echo "<tr>
                                    <td>{$nota['nota']}</td>
                                    <td>{$nota['observacion']}</td>
                                    <td>{$nota['materia']}</td>
                                  </tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "<p class='info'>Este alumno no tiene notas registradas.</p>";
                    }
                }
            }
            ?>

            <a href="index.html" class="btn-volver">Volver</a>
        </main>

    </div>
</body>
</html>
