<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexiondb.php';

$conexion = conectar();  // Llama a la función conectar

// Obtener la información del usuario
$id_usuario = $_SESSION['id_usuario'];
$consulta = $conexion->prepare("SELECT nombre, usuario, email, telefono, nivel FROM usuarios WHERE Identificador = ?");
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
            <button class="tablinks" onclick="openTab(event, 'VerPartidos')">Mis Partidos</button>
            <button class="tablinks" onclick="openTab(event, 'PartidosCreados')">Partidos Creados</button>
            <button class="tablinks" onclick="openTab(event, 'ReservarClases')">Reservar Clases</button>
        </div>

        <div id="Perfil" class="tabcontent">
            <h2>Perfil de Usuario</h2>
            <p>Nombre: <?php echo htmlspecialchars($usuario['nombre']); ?></p>
            <p>Nombre de Usuario: <?php echo htmlspecialchars($usuario['usuario']); ?></p>
            <p>Mi Nivel: <?php echo htmlspecialchars($usuario['nivel']); ?></p>
        </div>

        <div id="CrearPartidos" class="tabcontent">
            <h2>Crear Partidos</h2>
            <form action="crear_partido.php" method="POST">
                <div class="container">
                    <div class="input-field">
                        <label>Tipo de Partido</label>
                        <select name="tipo" required>
                            <option value="individual">Individual</option>
                            <option value="dobles">Dobles</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <label>Ubicación</label>
                        <input type="text" name="ubicacion" required>
                    </div>
                    <div class="input-field">
                        <label>Fecha y Hora</label>
                        <input type="datetime-local" name="fecha_hora" required>
                    </div>
                    <button type="submit" class="btn">Crear Partido</button>
                </div>
            </form>
        </div>

        <div id="VerPartidos" class="tabcontent">
            <h2>Partidos Confirmados</h2>
            <div id="partidos-list"></div>
        </div>

        <div id="PartidosCreados" class="tabcontent">
            <h2>Partidos Creados</h2>
            <div id="partidos-creados-list"></div>
        </div>

        <div id="ReservarClases" class="tabcontent">
            <h2>Reservar Clases con Entrenadores</h2>
            <div id="clases-list"></div>
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

        document.getElementById("defaultOpen").click();

        // Lógica para cargar notificaciones de partidos confirmados
        fetch('notificaciones.php')
            .then(response => response.json())
            .then(data => {
                console.log("Partidos confirmados recibidos:", data);
                const partidosList = document.getElementById('partidos-list');
                partidosList.innerHTML = '';
                if (data.length === 0) {
                    partidosList.innerHTML = '<p>No se encontraron partidos confirmados.</p>';
                } else {
                    data.forEach(partido => {
                        const div = document.createElement('div');
                        div.className = 'partido';
                        div.innerHTML = `
                            <p>ID: ${partido.id}</p>
                            <p>Tipo: ${partido.tipo}</p>
                            <p>Nivel: ${partido.nivel_requerido}</p>
                            <p>Ubicación: ${partido.ubicacion}</p>
                            <p>Fecha y Hora: ${partido.fecha_hora}</p>
                            <p>Estado: ${partido.estado}</p>
                        `;
                        partidosList.appendChild(div);
                    });
                }
            })
            .catch(error => {
                console.error("Error al cargar los partidos confirmados:", error);
            });

             // Lógica para cargar partidos creados
        fetch('partidos_creados.php')
            .then(response => response.json())
            .then(data => {
                console.log("Partidos creados recibidos:", data);
                const partidosCreadosList = document.getElementById('partidos-creados-list');
                partidosCreadosList.innerHTML = '';
                if (data.length === 0) {
                    partidosCreadosList.innerHTML = '<p>No se encontraron partidos creados.</p>';
                } else {
                    data.forEach(partido => {
                        const div = document.createElement('div');
                        div.className = 'partido-creado';
                        div.innerHTML = `
                            <p>ID: ${partido.id}</p>
                            <p>Tipo: ${partido.tipo}</p>
                            <p>Nivel: ${partido.nivel_requerido}</p>
                            <p>Ubicación: ${partido.ubicacion}</p>
                            <p>Fecha y Hora: ${partido.fecha_hora}</p>
                            <p>Estado: ${partido.estado}</p>
                            <button class="btn-cancelar" data-id="${partido.id}">Cancelar</button>
                        `;
                        partidosCreadosList.appendChild(div);
                    });
                }
            })
            .catch(error => {
                console.error("Error al cargar los partidos creados:", error);
            });

        document.addEventListener('click', function (e) {
            if (e.target && e.target.className === 'btn-cancelar') {
                const id_partido = e.target.getAttribute('data-id');
                fetch('cancelar_partido.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id_partido: id_partido })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });

        // Lógica para cargar clases
        fetch('clases.php')
            .then(response => response.json())
            .then(data => {
                const clasesList = document.getElementById('clases-list');
                clasesList.innerHTML = '';
                data.forEach(clase => {
                    const div = document.createElement('div');
                    div.className = 'clase';
                    div.innerHTML = `Clase: ${clase.nombre} con ${clase.profesor}`;
                    clasesList.appendChild(div);
                });
            });
    </script>
</body>
</html>
