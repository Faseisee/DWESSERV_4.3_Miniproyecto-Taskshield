<?php
require_once "conexion.php";
//RECOGEMOS EL ID COMO ANTES
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("El ID de la incidencia no es válido");
}

// BUSCAMOS LA INCIDENCIA EN NUESTRA BASE DE DATOS Y EJECUTAMOS LA CONSULTA 
$sql = "SELECT * FROM incidencias WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([':id' => $id]);

$incidencia = $stmt->fetch(PDO::FETCH_ASSOC);

// SI SE HA ENVIADO EL FORMULARIO, ACTUALIZAMOS EL ESTADO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // RECOGEMOS EL NUEVO ESTADO
    $estado = $_POST['estado'];

    // ACTUALIZAMOS EL ESTADO DE LA INCIDENCIA
    $sql = "UPDATE incidencias SET estado = :estado WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':estado' => $estado,
        ':id' => $id
    ]);
    header("Location: index.php?id=" . $id);
        exit;
}


?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar incidencia</title>
</head>
<body>

    <h1>Editar incidencia</h1>
    <form method="POST">

    <label for="estado">Estado de la incidencia:</label>

    <select name="estado" id="estado">

        <option value="Pendiente">Pendiente</option>

        <option value="En Proceso">En Proceso</option>

        <option value="Resuelta">Resuelta</option>

    </select>

    <br><br>

    <button type="submit">Guardar cambios</button>

</form>

</body>
</html>