<?php

require_once 'config.php';

try {
    $pdo = new PDO($dsn, $usuario, $password, $opciones);

   // echo "Conexión establecida correctamente";

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}