<?php
session_start();
include 'conexiondb.php';

$conexion = conectar();
$id_usuario = $_SESSION['id_usuario'];

$query = "
SELECT p.id, p.tipo, p.nivel_requerido, p.ubicacion, p.fecha_hora, p.estado
FROM partidos p
WHERE (p.usuario_unido = ? OR p.organizador_id = ?)
AND p.estado = 'confirmado'
ORDER BY p.fecha_hora ASC";

$consulta = $conexion->prepare($query);
$consulta->bind_param("ii", $id_usuario, $id_usuario);
$consulta->execute();
$resultado = $consulta->get_result();

$partidos_confirmados = [];
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $partidos_confirmados[] = $row;
    }
} else {
    error_log("No se encontraron partidos confirmados para el usuario: $id_usuario");
}

$conexion->close();

header('Content-Type: application/json');
echo json_encode($partidos_confirmados);
?>
