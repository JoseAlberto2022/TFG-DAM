<?php
session_start();
include 'conexiondb.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$conexion = conectar();
$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_partido = $_POST['id_partido'];

    // Verificar que el usuario sea el organizador del partido
    $consulta = $conexion->prepare("SELECT id FROM partidos WHERE id = ? AND organizador_id = ?");
    $consulta->bind_param("ii", $id_partido, $id_usuario);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        // Eliminar el partido
        $consulta = $conexion->prepare("DELETE FROM partidos WHERE id = ?");
        $consulta->bind_param("i", $id_partido);
        $consulta->execute();
        echo json_encode(['status' => 'success', 'message' => 'Partido cancelado correctamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para cancelar este partido']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}

$conexion->close();
?>
