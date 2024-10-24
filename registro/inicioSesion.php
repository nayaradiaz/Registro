<!DOCTYPE html>
<html lang="en">
<?php
session_start();

$usuarios = [];
$errores = [];

$usuarios_path = "../../dataUsuarios/users.json";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];

    if (file_exists($usuarios_path)) {
        $usuarios = json_decode(file_get_contents($usuarios_path), true);
    } else {
        $errores[] = "No hay usuarios registrados.";
    }

    foreach ($usuarios as $usuario) {
        if ($usuario['email'] === $email) {
            if (password_verify($contraseña, $usuario['password'])) {
                $_SESSION["usuario"] = $usuario;
                header("Location: inicio.php");
                exit;
            }
        }
    }

    $errores[] = "Email o contraseña incorrectos.";
    $_SESSION["errores"] = $errores;
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio Sesión</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <form action="" method="POST">
        <div>
            <h1>Iniciar Sesión</h1>
        </div>
        <div class="formularios">
            <div>
                <input type="email" name="email"  placeholder="Email">
            </div>
            <div>
                <input type="password" name="contraseña" placeholder="Contraseña">
            </div>
            <div>
                <input type="checkbox" name="recuerdame"> Recuerdame
            </div>
            <div>
                <button class="btn">Enviar</button>
            </div>
            <div>
            <?php
                if (!empty($_SESSION["errores"])) {
                    echo '<div class="error">' . implode('<br>', $_SESSION["errores"]) . '</div>';
                    unset($_SESSION["errores"]);  
                }
            ?>
            </div>
        </div>
    </form>
</body>
</html>
