
<?php
require_once "conexion.php";

// RECOGEMOS EL ID QUE NOS LLEGA POR POST
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

// PREPARAMOS LA CONSULTA PARA ELIMINAR LA INCIDENCIA
$sql = "DELETE FROM incidencias WHERE id = :id";

$stmt = $pdo->prepare($sql);

// EJECUTAMOS LA CONSULTA
$stmt->execute([
    ':id' => $id
]);

// COMPROBAMOS QUE EL ID ES VÁLIDO
if (!$stmt) {
    die("El ID de la incidencia no es válido");
}


// COMPROBAMOS SI SE HA ELIMINADO ALGUNA FILA
if ($stmt->rowCount() > 0) {
    header("Location: index.php");
    exit;
} else {
    die("No se ha encontrado la incidencia que quieres eliminar");
}
