<!DOCTYPE html>
<html lang="en">
<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: inicioSesion.php"); 
    exit;
}

$usuarioLogeado = $_SESSION["usuario"];
$usuarios_path = "../../dataUsuarios/users.json";
$mensaje = ""; 


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar_usuario"])) {
    if (file_exists($usuarios_path)) {
        $usuarios = json_decode(file_get_contents($usuarios_path), true);

        $usuarios = array_filter($usuarios, function($usuario) use ($usuarioLogeado) {
            return $usuario['email'] !== $usuarioLogeado['email'];
        });

        file_put_contents($usuarios_path, json_encode(array_values($usuarios)));

        session_unset();
        session_destroy();

        $mensaje = "Tu cuenta ha sido eliminada exitosamente.";
        header("Location: inicioSesion.php"); 
        exit;
    } else {
        $mensaje = "No se pudo encontrar el archivo de usuarios.";
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del Usuario</title>
    <link rel="stylesheet" href="styleUsuarios.css">
</head>

<body>
    
    <div class="usuario-info">
       
        <div >
            <h2>Datos del Usuario Logeado</h2>

        </div>
        <div >
            <img src="<?php echo htmlspecialchars($usuarioLogeado['foto']); ?>" alt="Foto de <?php echo htmlspecialchars($usuarioLogeado['nombre']); ?>">

        </div>

       
        <div>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuarioLogeado['nombre']); ?></p>
        <p><strong>Apellido:</strong> <?php echo htmlspecialchars($usuarioLogeado['apellido']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($usuarioLogeado['email']); ?></p>
        <p><strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($usuarioLogeado['fechaNacimiento']); ?></p>
        
        </div>
    
        
    </div>
    <div class="menu">

        <div><h2>Eliminar Mi Cuenta</h2></div>
        <div>
        <form action="" method="POST">
                <p>¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.</p>
                
                <div class="botones">
                    <div>
                    <button type="submit" name="eliminar_usuario">Eliminar Cuenta</button>

                    </div>
                    <div>
                    <a href="logout.php">Cerrar Sesión</a>

                    </div>
                </div>
            </form>
        </div>
            
            

        
    </div>
    
    
    <?php if ($mensaje): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>
</body>
</html>
