<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexiondb.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - RacketLink</title>
    <link rel="stylesheet" href="css/inicio.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
</head>
<body>
    <div class="bienvenida">
        <?php if (isset($_SESSION['usuario'])) : ?>
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h1>
        <?php endif; ?> 
    </div>
    <?php include 'barradenavegacion.php'; ?>
    
    <div class="container">
        <div class="main-content">
            <div class="image-section">
                <img src="Img/saludo.jpg" alt="Partidos de RacketLink">
                <a href="ver_partidos.php" class="btn">Ver Partidos Disponibles</a>
            </div>
        </div>
    </div>

    <!-- <script src="js/codigoinicio.js"></script> -->
</body>
</html>