<?php
require_once 'conexion.php';

//AQUI VAMOS A DEFINIR Y PREPARAR LA CONSULTA QUE HARA EL PDO A NUESTRA BDD

/*
EN ESTA CONSULTA RECOGEMOS LOS DATOS QUE DESPUES MOSTRAREMOS EN UNA TABLA HTML:

- SELECT: seleccionamos el ID, TITULO, PRIORIDAD y ESTADO de las incidencias, además del NOMBRE del técnico.
- FROM incidencias: indicamos que la tabla principal de la consulta es incidencias.
- LEFT JOIN tecnicos: relacionamos la tabla incidencias con la tabla tecnicos para obtener el nombre del técnico asignado. Utilizamos LEFT JOIN para mostrar también las incidencias que todavía no tienen técnico.
- ON: indicamos que la relación entre ambas tablas se realiza comparando tecnico_id de incidencias con id de tecnicos.
- AS tecnico: damos el nombre "tecnico" a la columna tecnicos.nombre para identificarla más fácilmente desde PHP.
*/
$sql = "SELECT incidencias.id, incidencias.titulo, incidencias.prioridad, incidencias.estado, tecnicos.nombre AS tecnico
        FROM incidencias
        LEFT JOIN tecnicos
        ON incidencias.tecnico_id = tecnicos.id";

//AHORA QUE ENTENDEMOS QUE VAMOS A RECOGER PREPARAMOS LA SENTENCIA CON prepare()
$stmt = $pdo->prepare($sql);
//Y POR ULTIMO LA EJECUTAMOS CON execute()
$stmt->execute();
/* AHORA RECOGEMOS EL RESULTADO DE LA CONSULTA EN LA VARIABLE $resultado CON fetchAll QUE NOS PERMITE RECOGER TODAS LAS FILAS DE GOLPE
Y LAS TRATAMOS COMO UN ARRAY ASOCIATIVO CON PDO::FETCH_ASSOC*/
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*NUESTRA INTENCION AHORA ES CREAR UNA TABLA HTML DINAMICAMENTE CON EL ARRAY ASOCIATIVO QUE NOS HA LLEGADO
PARA ELLO 1 RECORREMOS EL ARRAY CON UN BUCLE FOR EACH 
Y CADA FILA LA VAMOS A RECORRER COMO UNA $incidencia QUE CONTIENE SU PROPIO id, descripcion, estado...
*/
?>

<a href="nueva_incidencia.php">
    <button type="button">Añadir incidencia</button>
</a>

<table>
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Prioridad</th>
        <th>Estado</th>
        <th>Técnico asignado</th>
        <th>Editar</th>
        <th>Eliminar</th>
    </tr>

    <?php
    foreach ($resultado as $incidencia) {
    ?>

        <tr>
            <td><?php echo htmlspecialchars($incidencia['id']); ?></td>
            <td><?php echo htmlspecialchars($incidencia['titulo']); ?></td>
            <td><?php echo htmlspecialchars($incidencia['prioridad']); ?></td>
            <td><?php echo htmlspecialchars($incidencia['estado']); ?></td>
            <td><?php echo htmlspecialchars($incidencia['tecnico']); ?></td>

            <!-- EDITAR ESTA INCIDENCIA -->
            <td>
                <form action="editar_incidencia.php" method="GET">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($incidencia['id']); ?>">
                    <button type="submit">Editar incidencia</button>
                </form>
            </td>

            <!-- ELIMINAR ESTA INCIDENCIA -->
            <td>
                <form action="eliminar_incidencia.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($incidencia['id']); ?>">
                    <button type="submit">Eliminar incidencia</button>
                </form>
            </td>
        </tr>

    <?php
    }
    ?>
</table>