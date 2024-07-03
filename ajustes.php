<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexiondb.php';
$conexion = conectar();  // Llama a la función conectar()

// Obtener la información del usuario
$id_usuario = $_SESSION['id_usuario'];
$consulta = $conexion->prepare("SELECT nombre, usuario, email, telefono, contrasena FROM usuarios WHERE Identificador = ?");
$consulta->bind_param("i", $id_usuario);
$consulta->execute();
$resultado = $consulta->get_result();
$usuario = $resultado->fetch_assoc();

// Comprobar si el formulario ha sido enviado para actualizar la información del perfil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $nombre_usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    // Actualizar la información en la base de datos
    $actualizar = $conexion->prepare("UPDATE usuarios SET nombre = ?, usuario = ?, email = ?, telefono = ? WHERE Identificador = ?");
    $actualizar->bind_param("ssssi", $nombre, $nombre_usuario, $email, $telefono, $id_usuario);
    $actualizar->execute();
    $actualizar->close();

    // Actualizar la información en la sesión
    $_SESSION['usuario'] = $nombre_usuario;

    // Cambio de contraseña
    if (!empty($_POST['contrasena_actual']) && !empty($_POST['nueva_contrasena']) && !empty($_POST['confirmar_contrasena'])) {
        $contrasena_actual = $_POST['contrasena_actual'];
        $nueva_contrasena = $_POST['nueva_contrasena'];
        $confirmar_contrasena = $_POST['confirmar_contrasena'];

        if ($nueva_contrasena === $confirmar_contrasena) {
            if ($contrasena_actual === $usuario['contrasena']) {
                $actualizar = $conexion->prepare("UPDATE usuarios SET contrasena = ? WHERE Identificador = ?");
                $actualizar->bind_param("si", $nueva_contrasena, $id_usuario);
                $actualizar->execute();
                $actualizar->close();
                $_SESSION['mensaje'] = "Contraseña actualizada correctamente";
            } else {
                $_SESSION['mensaje'] = "La contraseña actual no es correcta";
            }
        } else {
            $_SESSION['mensaje'] = "Las nuevas contraseñas no coinciden";
        }
    } else {
        $_SESSION['mensaje'] = "Datos actualizados correctamente";
    }


    // Recargar la página para mostrar los cambios
    header("Location: ajustes.php");
    exit();
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - RacketLink</title>
    <link rel="stylesheet" href="css/ajustes.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<?php include 'barradenavegacion.php'; ?>
    <div class="profile-container">
        <h1>Ajustes de Usuario</h1>
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<p class='mensaje'>" . $_SESSION['mensaje'] . "</p>";
            unset($_SESSION['mensaje']);
        }
        ?>
        <form action="ajustes.php" method="POST">
            <div class="input-box">
                <div class="input-field">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                    <i class='bx bx-user'></i>
                </div>
                <div class="input-field">
                    <label>Nombre de Usuario</label>
                    <input type="text" name="usuario" value="<?php echo htmlspecialchars($usuario['usuario']); ?>" required>
                    <i class='bx bx-user-circle'></i>
                </div>
                <div class="input-field">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                    <i class='bx bx-envelope'></i>
                </div>
                <div class="input-field">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>
                    <i class='bx bx-phone'></i>
                </div>
                <div class="input-field">
                    <label>Contraseña Actual</label>
                    <input type="password" name="contrasena_actual" placeholder="Contraseña actual">
                    <i class='bx bx-lock'></i>
                </div>
                <div class="input-field">
                    <label>Nueva Contraseña</label>
                    <input type="password" name="nueva_contrasena" placeholder="Nueva contraseña">
                    <i class='bx bx-lock-alt'></i>
                </div>
                <div class="input-field">
                    <label>Confirmar Nueva Contraseña</label>
                    <input type="password" name="confirmar_contrasena" placeholder="Confirmar nueva contraseña">
                    <i class='bx bx-lock-alt'></i>
                </div>
            </div>
            <button type="submit" class="btn">Actualizar Perfil</button>
        </form>
    </div>
</body>
</html>
