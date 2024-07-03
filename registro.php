<?php
include 'conexiondb.php';  // Asegúrate de que el archivo de conexión esté correctamente configurado

session_start();

// Cerrar cualquier sesión existente
session_unset();
session_destroy();

// Reanudar la sesión para el nuevo registro
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];
    $nivel = $_POST['nivel'];
    // $terminos = isset($_POST['terminos']);  // Esto será true si el checkbox está marcado

    // Verificar las contraseñas y los términos
    if ($contrasena == $confirmar_contrasena) {
        $conexion = conectar();
        // Cifrar la contraseña antes de almacenarla
        // $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Insertar los datos en la base de datos
        $consulta = $conexion->prepare("INSERT INTO usuarios (nombre, usuario, email, telefono, contrasena, nivel) VALUES (?, ?, ?, ?, ?, ?)");
        $consulta->bind_param("ssssss", $nombre, $usuario, $email, $telefono, $contrasena, $nivel);
        $consulta->execute();
        

        if ($consulta->affected_rows === 1) {
            // Obtener el ID del usuario recién creado
            $Identificador = $conexion->insert_id;
            
            // Iniciar sesión automáticamente
            $_SESSION['id_usuario'] = $Identificador;
            $_SESSION['usuario'] = $usuario;

            // Redireccionar al perfil del usuario
            header("Location: inicio.php");
            exit;
        } else {
            echo "Error al registrar el usuario.";
        }
        $consulta->close();
        $conexion->close();
        } else {
            echo "Las contraseñas no coinciden.";
        }
    }



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - RacketLink</title>
    <link rel="stylesheet" href="css/registro.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
    <style>
        /* Estilo para el ícono de interrogación */
        .help-icon {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }

        /* Estilo para la ventana emergente */
        .help-popup {
            display: none;
            position: absolute;
            background-color: black;
            color: white;
            border: 1px solid #ccc;
            padding: 10px;
            z-index: 1000;
            width: 200px;
            bottom: 30px; /* Ajusta esta propiedad según sea necesario */
        }
    </style>
    
</head>
<body>

    <div class="contieneregistro">
        <form action="registro.php" method="POST">
            <h1>Crea tu cuenta</h1>
            
            <div class="input-box">
                <div class="input-field">
                    <input type="text" name="nombre" placeholder="Nombre completo" required>
                    <i class='bx bx-user-circle' ></i>
                </div>
                <div class="input-field">
                    <input type="text" name="usuario" placeholder="Usuario" required>
                    <i class='bx bx-user-circle' ></i>
                </div>
            </div>

            <div class="input-box">
                <div class="input-field">
                    <input type="email" name="email" placeholder="email" required>
                    <i class='bx bx-envelope'></i>
                </div>
                <div class="input-field">
                    <input type="number" name="telefono" placeholder="Numero de telefono" required>
                    <i class='bx bx-phone' ></i>
                </div>
            </div>
            <div class="input-box">
                <div class="input-field">
                    <input type="password"  name="contrasena" placeholder="Contraseña" required>
                    <i class='bx bx-lock'></i>
                </div>
                <div class="input-field">
                    <input type="password" name="confirmar_contrasena" placeholder="Confirmar contraseña" required>
                    <i class='bx bx-lock'></i>
                </div>
            </div>
            <div class="input-box">
                <div class="input-field">
                    <input type="number" name="nivel" placeholder="Nivel del usuario?" required>
                    <i class='bx bx-lock'></i>
                    <span class="help-icon" onclick="togglePopup()">¿?</span>
                    <div id="helpPopup" class="help-popup">
                    <p>Aquí deberemos incluir el nivel de tenis con el que nos sintamos identificados. Desde 0 que es principiante total, Hasta 10, que correspondería a jugador semiprofesional.</p>
                    </div><br>
                </div>
            </div>
                <label><input type="checkbox">Acepto los terminos y condiciones</label>
                <button type="submit" class="boton">Registrarme</button>
        </form>
    </div>
    <script>
        // Función para mostrar/ocultar la ventana emergente
        function togglePopup() {
            var popup = document.getElementById("helpPopup");
            if (popup.style.display === "none") {
                popup.style.display = "block";
            } else {
                popup.style.display = "none";
            }
        }
    </script>
</body>
</html>