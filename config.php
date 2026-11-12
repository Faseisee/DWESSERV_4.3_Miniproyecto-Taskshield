<?php
$dsn = 'mysql:host=localhost;dbname=miniproyectotaskshield;charset=utf8mb4';
$usuario = 'root';
$password = '';
// Opciones de configuración del driver PDO
$opciones = [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lanza excepciones
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch por defecto
PDO::ATTR_EMULATE_PREPARES => false, // Prepares nativos
];
