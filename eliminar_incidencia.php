
<?php
require_once "conexion.php";

// RECOGEMOS EL ID QUE NOS LLEGA POR POST
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

// COMPROBAMOS QUE EL ID ES VÁLIDO
if (!$id) {
    die("El ID de la incidencia no es válido");
}

// PREPARAMOS LA CONSULTA PARA ELIMINAR LA INCIDENCIA
$sql = "DELETE FROM incidencias WHERE id = :id";

$stmt = $pdo->prepare($sql);

// EJECUTAMOS LA CONSULTA
$stmt->execute([
    ':id' => $id
]);

// COMPROBAMOS SI SE HA ELIMINADO ALGUNA FILA
if ($stmt->rowCount() > 0) {
    header("Location: index.php");
    exit;
} else {
    die("No se ha encontrado la incidencia que quieres eliminar");
}
