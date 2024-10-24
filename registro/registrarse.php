<?php

session_start();

isset($_SESSION['nombre']) ? $nombre = $_SESSION['nombre'] :  $nombre = '';
isset($_SESSION['correo']) ? $email = $_SESSION['correo'] :   $email = '';
isset($_SESSION['apellido']) ? $apellido = $_SESSION['apellido'] :   $apellido = '';
isset($_SESSION['fecha']) ? $fecha = $_SESSION['fecha'] :   $fecha = '';
isset($_SESSION['imagen']) ? $imagen = $_SESSION['imagen'] :   $imagen = '';
$errores = isset($_SESSION['errores']) ? $_SESSION['errores'] : [];





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="funcionalidad.php" method="POST" autocomplete="off" enctype="multipart/form-data">
        <div>
            <h1>Registro</h1>
        </div>
        <div class="formularios">
            <div class="form-group">
                <div>
                    <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>">

                </div>
                <div>
                    <span class="error" >
                        <?php if (isset($errores["errorNombre"])) echo $errores["errorNombre"]; ?>

                    </span>
                </div>
                
            </div>
            <div class="form-group">
                <div>
                <input type="text" name="apellido" placeholder="Apellidos" value="<?php echo $apellido; ?>">

                </div>
                <div>
                <span class="error">
                    <?php
                     if (isset($errores["errorApellido"])) echo $errores["errorApellido"];

                    ?>
                </span>
                </div>
                
            </div>
            <div class="form-group">
                <div>
                <input type="password" name="contraseña" placeholder="Contraseña">

                </div>
                <div>
                <span class="error">
                    <?php
                    if (isset($errores["errorContraseña"])) echo $errores["errorContraseña"]; 
                    ?>
                </span>
                </div>
                
            </div>
            <div class="form-group">
                <div>
                <input type="password" name="compContraseña" placeholder="Comprobar Contraseña">

                </div>
                <div>
                <span class="error">
                    <?php
                    if (isset($errores["errorCompContraseña"])) echo $errores["errorCompContraseña"];
                    ?>
                </span>
                </div>
                
            </div>
            <div class="form-group">
                <div>
                <input type="email" name="correo" placeholder="Email" value="<?php echo $email; ?>">

                </div>
                <div>
                    <span class="error">
                        <?php
                        if (isset($errores["errorCorreo"])) echo $errores["errorCorreo"];
                        ?>
                    </span>
                </div>
                
            </div>
            <div class="form-group">
                <div>
                <input type="date" name="fechaNac" value="<?php echo $fecha; ?>">

                </div>
                <div>
                <span class="error">
                    <?php
                         if (isset($errores["errorFecha"])) echo $errores["errorFecha"];

                    ?>
                </span>
                </div>
                
            </div>
            <div class="form-group">
                <div>
                <input type="file" name="foto" value="<?php echo $imagen; ?>">

                </div>
                <div>
                <span class="error">
    <?php if (isset($errores["errorImagen"])) echo $errores["errorImagen"]; ?>
</span>

                </div>

                
            </div>


            <div>
                <button class="btn" type="submit" name="enviarRegistro">Enviar</button>

            </div>
        </div>



    </form>
</body>

</html>

<?php

unset($_SESSION['errores']);

?>