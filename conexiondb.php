<?php
function conectar() {
    $nombreservidor = "localhost";
    $usuario = "racketlink";
    $contrasenia = "racketlink";
    $db = "racketlink";

    // Crear conexión
    $conexion = new mysqli($nombreservidor, $usuario, $contrasenia, $db);

    // Verificar conexión
    if ($conexion->connect_error) {
        die("Connection failed: " . $conexion->connect_error);
    }

    return $conexion;
}
?>