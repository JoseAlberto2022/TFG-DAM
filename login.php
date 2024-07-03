<?php
require_once 'conexiondb.php';  // Incluir el archivo de conexión

// Iniciar sesión o reanudar la sesión existente
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    $conexion = conectar();  // Llama a la función conectar()

    // Preparar y ejecutar la consulta
    $consulta = $conexion->prepare("SELECT Identificador, usuario, contrasena FROM usuarios WHERE email = ?");
    $consulta->bind_param("s", $email);
    $consulta->execute();
    $resultado = $consulta->get_result();
    
    // Verificar si se encontró el usuario
    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        
        // Verificar la contraseña
        if ($contrasena == $usuario['contrasena']) {
            // Si la contraseña es correcta, iniciar sesión
            $_SESSION['id_usuario'] = $usuario['Identificador'];
            $_SESSION['usuario'] = $usuario['usuario'];
            $_SESSION['email'] = $email;

            // Redireccionar al usuario a la página de inicio
            header("Location: inicio.php");
            exit;
        } else {
            $error = "La contraseña es incorrecta.";
        }
    } else {
        $error = "No se encontró el usuario.";
    }
    $consulta->close();
    $conexion->close();
}

// Cerrar la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - RacketLink</title>
    <link rel="stylesheet" href="css/login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
</head>
<body>

    <div class="contienelogin">
        <h1>RacketLink</h1>
        <!-- <i class='bx bx-tennis-ball' ></i> -->
        
        <form action="login.php" method="POST">
            <div class="input-box">
                <input type="text" name="email" placeholder="Usuario"required>
                <i class='bx bx-user'></i>
            </div>
            <div class="input-box">
                <input type="password" name="contrasena" placeholder="Contraseña"required>
                <i class='bx bx-lock-alt'></i>
            </div>
            <div class="remember-forgot">
                <label><input type="checkbox">Recordar mi usuario</label>
                <a href="#">¿Has olvidado tu contraseña?</a>
            </div>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <button type="submit" class="btn">Iniciar sesión</button>
            <div class="register-link"><p>¿No tienes una cuenta? <a href="registro.php">Registrate</a></div></p>
        </form>
    </div>
</body>
</html>

