<?php
session_start();
include 'conexiondb.php';

$conexion = conectar();
$id_usuario = $_SESSION['id_usuario'];

$query = "
SELECT p.id, p.tipo, p.nivel_requerido, p.ubicacion, p.fecha_hora, p.estado
FROM partidos p
WHERE p.organizador_id = ?
ORDER BY p.fecha_hora ASC";

$consulta = $conexion->prepare($query);
$consulta->bind_param("i", $id_usuario);
$consulta->execute();
$resultado = $consulta->get_result();

$partidos_creados = [];
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $partidos_creados[] = $row;
    }
}

$conexion->close();

header('Content-Type: application/json');
echo json_encode($partidos_creados);
?>
