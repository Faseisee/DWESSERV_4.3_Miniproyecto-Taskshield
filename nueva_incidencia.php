<?php
require "conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Aquí procesaremos los datos del formulario
    // echo "Datos enviados";
    //RECOGEMOS LOS CAMPOS DEL FORMULARIO CON EL filter_input()
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS);
    $prioridad = filter_input(INPUT_POST, 'prioridad', FILTER_SANITIZE_SPECIAL_CHARS);
   try {

    if (!$titulo) {
        throw new Exception("El título es obligatorio");
    }

    if (!$descripcion) {
        throw new Exception("La descripción es obligatoria");
    }

    if (!$prioridad) {
        throw new Exception("Debes seleccionar una prioridad");
    }

    if (!$email) {
        throw new Exception("El correo electrónico no es válido");
    }


    //UNA VEZ QUE TOODOS LOS CAMPOS ESTAN COMPROBADOS Y RELLENOS CORRECTAMENTE VAMOS A PREPARAR LA CONSULTA A NUESTRA BDD 
    $sql = "INSERT INTO incidencias (titulo, descripcion, prioridad, email_cliente)
        VALUES (:titulo, :descripcion, :prioridad, :email)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo' => $titulo,
        ':descripcion' => $descripcion,
        ':prioridad' => $prioridad,
        ':email' => $email
    ]);

    // RECUPERAMOS EL ID
        $id = $pdo->lastInsertId();

    // REDIRIGIMOS A NUESTRO index.php
        header("Location: index.php?id=" . $id);
        exit;
} catch (Exception $e) {

    echo '<div style="color: red;">'.$e->getMessage().'</div>';

}
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Incidencia</title>
</head>
<body>
    <h1>Entrada nueva Incidencia</h1>
    <form method="POST">

    <label for="titulo">Introduce el titulo de la incidencia</label><br>
    <input type="text" id="titulo" name="titulo"><br><br>
    <label for="descripcion">Introduce una breve descripcion de la incidencia</label><br>
   <textarea id="descripcion" name="descripcion"></textarea><br><br>
    <label for="prioridad">Seleccione la prioridad de la incidencia</label>
        <select id="prioridad" name="prioridad">
            <option value="Baja">Baja</option>
            <option value="Media">Media</option>
            <option value="Alta">Alta</option>
        </select><br><br>
    <label for="email">Introduzca el email del cliente</label><br>
    <input type="email" id="email" name="email">
    <br><br><button type="submit">Crear incidencia</button>
    </form>
</body>
</html>
