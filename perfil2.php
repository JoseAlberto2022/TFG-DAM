<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexiondb.php';

// Obtener la información del usuario
$id_usuario = $_SESSION['id_usuario'];
$consulta = $conexion->prepare("SELECT nombre, usuario, email, telefono FROM usuarios WHERE Identificador = ?");
$consulta->bind_param("i", $id_usuario);
$consulta->execute();
$resultado = $consulta->get_result();
$usuario = $resultado->fetch_assoc();

// Cerrar la conexión a la base de datos
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - RacketLink</title>
    <link rel="stylesheet" href="css/perfil.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
</head>
<body>
<?php include 'barradenavegacion.php'; ?>
    <div class="profile-container">
        <div class="tabs">
            <button class="tablinks" onclick="openTab(event, 'Perfil')" id="defaultOpen">Perfil</button>
            <button class="tablinks" onclick="openTab(event, 'CrearPartidos')">Crear Partidos</button>
            <button class="tablinks" onclick="openTab(event, 'VerPartidos')">Ver Partidos</button>
            <button class="tablinks" onclick="openTab(event, 'ReservarClases')">Reservar Clases</button>
        </div>

        <div id="Perfil" class="tabcontent">
            <h2>Perfil de Usuario</h2>
            <p>Nombre: <?php echo htmlspecialchars($usuario['nombre']); ?></p>
            <p>Nombre de Usuario: <?php echo htmlspecialchars($usuario['usuario']); ?></p>
            <p>Email: <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p>Teléfono: <?php echo htmlspecialchars($usuario['telefono']); ?></p>
        </div>

        <div id="CrearPartidos" class="tabcontent">
            <h2>Crear Partidos</h2>
            <!-- Formulario para crear partidos -->
            <form action="crear_partido.php" method="POST">
                <div class="input-field">
                    <label>Fecha del Partido</label>
                    <input type="date" name="fecha" required>
                </div>
                <div class="input-field">
                    <label>Hora del Partido</label>
                    <input type="time" name="hora" required>
                </div>
                <div class="input-field">
                    <label>Ubicación</label>
                    <input type="text" name="ubicacion" required>
                </div>
                <button type="submit" class="btn">Crear Partido</button>
            </form>
        </div>

        <div id="VerPartidos" class="tabcontent">
            <h2>Partidos a los que te has unido</h2>
            <!-- Aquí mostraríamos los partidos a los que el usuario se ha unido -->
            <!-- Por simplicidad, mostramos un mensaje de ejemplo -->
            <p>No te has unido a ningún partido aún.</p>
        </div>

        <div id="ReservarClases" class="tabcontent">
            <h2>Reservar Clases con Entrenadores</h2>
            <!-- Aquí podríamos tener un formulario para reservar clases -->
            <!-- Por simplicidad, mostramos un mensaje de ejemplo -->
            <p>Reserva tus clases con nuestros entrenadores profesionales.</p>
        </div>
    </div>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Abre la pestaña predeterminada
        document.getElementById("defaultOpen").click();
    </script>
</body>
</html>