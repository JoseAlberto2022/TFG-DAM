<?php
require_once 'conexiondb.php';  // Asegúrate de incluir el archivo correctamente
session_start();

$conexion = conectar();  // Llama a la función conectar()

$sql = "SELECT p.id, p.tipo, p.nivel_requerido, p.ubicacion, p.fecha_hora, u.usuario as organizador 
        FROM partidos p
        JOIN usuarios u ON p.organizador_id = u.Identificador
        WHERE p.estado = 'pendiente' AND p.usuario_unido IS NULL";
$resultado = $conexion->query($sql);

$partidos = [];
while ($row = $resultado->fetch_assoc()) {
    $partidos[] = $row;
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partidos Disponibles - RacketLink</title>
    <link rel="stylesheet" href="css/verpartidos.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
</head>
<body>

    <?php include 'barradenavegacion.php'; ?>
    <div class="container">
        <h2>Partidos Disponibles</h2>
        <div class="partidos-list">
            <?php if (count($partidos) > 0): ?>
                <?php foreach ($partidos as $partido): ?>
                    <div class="partido">
                        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($partido['tipo']); ?></p>
                        <p><strong>Nivel Requerido:</strong> <?php echo htmlspecialchars($partido['nivel_requerido']); ?></p>
                        <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($partido['ubicacion']); ?></p>
                        <p><strong>Fecha y Hora:</strong> <?php echo htmlspecialchars($partido['fecha_hora']); ?></p>
                        <p><strong>Organizador:</strong> <?php echo htmlspecialchars($partido['organizador']); ?></p>
                        <form action="unirte_partido.php" method="POST">
                            <input type="hidden" name="id_partido" value="<?php echo $partido['id']; ?>">
                            <button type="submit" class="btn">Unirse al Partido</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay partidos disponibles en este momento.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
