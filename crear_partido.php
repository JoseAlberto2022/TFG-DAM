<?php
require_once 'conexiondb.php';  // Asegúrate de incluir el archivo correctamente
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo = $_POST['tipo'];
    $ubicacion = $_POST['ubicacion'];
    $fecha_hora = $_POST['fecha_hora'];
    $id_usuario = $_SESSION['id_usuario'];

    $conexion = conectar();  // Llama a la función conectar()

    // Obtener el nivel del usuario desde la base de datos
    $sql = "SELECT nivel FROM usuarios WHERE Identificador = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();
    $nivel_requerido = $usuario['nivel'];

    // Insertar el partido en la base de datos
    $sql = "INSERT INTO partidos (organizador_id, tipo, nivel_requerido, ubicacion, fecha_hora, estado) VALUES (?, ?, ?, ?, ?, 'pendiente')";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("isiss", $id_usuario, $tipo, $nivel_requerido, $ubicacion, $fecha_hora);

    if ($stmt->execute()) {
        echo "Partido creado exitosamente.";
    } else {
        echo "Error al crear el partido: " . $conexion->error;
    }

    $stmt->close();
    $conexion->close();
}
?>
