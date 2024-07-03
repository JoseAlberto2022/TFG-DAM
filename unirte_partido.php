<?php
require_once 'conexiondb.php';
session_start();

$id_usuario = $_SESSION['id_usuario'];
$id_partido = $_POST['id_partido'];

$conexion = conectar();

// Obtener el nivel del usuario desde la base de datos
$sql = "SELECT nivel FROM usuarios WHERE Identificador = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$nivel_usuario = $usuario['nivel'];

// Verificar si el partido está disponible y obtener el nivel requerido
$sql = "SELECT nivel_requerido FROM partidos WHERE id = ? AND usuario_unido IS NULL";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_partido);
$stmt->execute();
$result = $stmt->get_result();
$partido = $result->fetch_assoc();

if ($result->num_rows > 0) {
    $nivel_partido = $partido['nivel_requerido'];

    if ($nivel_usuario == $nivel_partido) {
        // Unirse al partido y actualizar el estado a 'confirmado'
        $sql = "UPDATE partidos SET usuario_unido = ?, estado = 'confirmado' WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_partido);

        if ($stmt->execute()) {
            echo "Te has unido al partido exitosamente.";
        } else {
            echo "Error al unirte al partido: " . $conexion->error;
        }
    } else {
        echo "Tu nivel no coincide con el nivel requerido del partido.";
    }
} else {
    echo "El partido ya tiene un usuario unido o no está disponible.";
}

$stmt->close();
$conexion->close();
?>
