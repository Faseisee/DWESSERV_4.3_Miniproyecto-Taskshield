
<?php
require_once "conexion.php";

// RECOGEMOS EL ID DE LA INCIDENCIA QUE HEMOS SELECCIONADO
$id_incidencia = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// COMPROBAMOS QUE EL ID ES VALIDO
if (!$id_incidencia) {
    die("El ID de la incidencia no es válido");
}

// BUSCAMOS LA INCIDENCIA SELECCIONADA
$sql = "SELECT id, titulo, tecnico_id FROM incidencias WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id_incidencia]);

$incidencia = $stmt->fetch(PDO::FETCH_ASSOC);

// COMPROBAMOS QUE LA INCIDENCIA EXISTE
if (!$incidencia) {
    die("La incidencia no existe");
}

// RECOGEMOS LOS TECNICOS PARA MOSTRARLOS EN EL DESPLEGABLE
$sql = "SELECT id, nombre FROM tecnicos";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$tecnicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// VARIABLE PARA MOSTRAR MENSAJES
$mensaje = "";

// COMPROBAMOS SI SE HA ENVIADO EL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // RECOGEMOS EL ID DEL TECNICO SELECCIONADO
    $id_tecnico = filter_input(INPUT_POST, 'tecnico', FILTER_VALIDATE_INT);

    // COMPROBAMOS QUE EL ID DEL TECNICO ES VALIDO
    if (!$id_tecnico) {

        $mensaje = "Debes seleccionar un técnico válido.";

    } else {

        try {

            // INICIAMOS LA TRANSACCION
            $pdo->beginTransaction();

            // COMPROBAMOS QUE LA INCIDENCIA SIGUE EXISTIENDO Y BLOQUEAMOS SU FILA
            $sql = "SELECT id, tecnico_id FROM incidencias WHERE id = :id FOR UPDATE";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id_incidencia]);

            $incidencia_actual = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$incidencia_actual) {
                throw new Exception("La incidencia no existe.");
            }

            // COMPROBAMOS QUE EL TECNICO EXISTE Y BLOQUEAMOS SU FILA
            $sql = "SELECT id FROM tecnicos WHERE id = :id FOR UPDATE";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id_tecnico]);

            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                throw new Exception("El técnico seleccionado no existe.");
            }

            // EVITAMOS ASIGNAR DOS VECES EL MISMO TECNICO
            if ($incidencia_actual['tecnico_id'] == $id_tecnico) {
                throw new Exception("Esta incidencia ya está asignada a ese técnico.");
            }

            // PRIMER UPDATE: ASIGNAMOS EL TECNICO Y CAMBIAMOS EL ESTADO
            $sql = "UPDATE incidencias
                    SET tecnico_id = :tecnico_id, estado = 'En Proceso'
                    WHERE id = :id_incidencia";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':tecnico_id' => $id_tecnico,
                ':id_incidencia' => $id_incidencia
            ]);

            // SI LA INCIDENCIA TENIA OTRO TECNICO, DESCONTAMOS SU TAREA
            if ($incidencia_actual['tecnico_id'] !== null) {

                $sql = "UPDATE tecnicos
                        SET tareas_activas = tareas_activas - 1
                        WHERE id = :id_tecnico";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    ':id_tecnico' => $incidencia_actual['tecnico_id']
                ]);
            }

            // SEGUNDO UPDATE: INCREMENTAMOS LAS TAREAS ACTIVAS DEL NUEVO TECNICO
            $sql = "UPDATE tecnicos
                    SET tareas_activas = tareas_activas + 1
                    WHERE id = :id_tecnico";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':id_tecnico' => $id_tecnico
            ]);

            // SI TODO HA IDO BIEN, CONFIRMAMOS LOS CAMBIOS
            $pdo->commit();

            // VOLVEMOS AL LISTADO DE INCIDENCIAS
            header("Location: index.php");
            exit;

        } catch (Exception $e) {

            // SI HAY ALGUN ERROR, DESHACEMOS LA TRANSACCION
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $mensaje = "No se ha podido realizar la asignación: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar incidencia</title>
</head>
<body>

    <h1>Asignar incidencia a un técnico</h1>

    <h3>
        Incidencia seleccionada:
        <?php echo htmlspecialchars($incidencia['titulo']); ?>
    </h3>

    <?php
    if ($mensaje != "") {
        echo '<p>' . htmlspecialchars($mensaje) . '</p>';
    }
    ?>

    <form method="POST">

        <label for="tecnico">Selecciona un técnico:</label>

        <select name="tecnico" id="tecnico" required>

            <option value="">Selecciona un técnico</option>

            <?php
            foreach ($tecnicos as $tecnico) {
            ?>
                <option value="<?php echo htmlspecialchars($tecnico['id']); ?>">
                    <?php echo htmlspecialchars($tecnico['nombre']); ?>
                </option>
            <?php
            }
            ?>

        </select>

        <br><br>

        <button type="submit">Asignar incidencia</button>

    </form>

    <br>

</body>
</html>
