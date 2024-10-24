<?php

session_start();

$nombre = $apellido = $contraseña = $compContraseña = $email = $fecha = '';
$usuarios = [];
$errores = [];  
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /* Validación de campos */

    // Validar nombre
    if(empty($_POST["nombre"])){
        $errores["errorNombre"] = "Campo nombre vacío";
    } else {
        $nombre = test_input($_POST["nombre"]);
        if (!preg_match("/^(?=.{3,18}$)[a-zñA-ZÑ](\s?[a-zñA-ZÑ])*$/", $nombre)) {
            $errores["errorNombre"] = "El nombre contiene caracteres inválidos";
        } else {
            $_SESSION["nombre"] = $nombre;
        }
    }

    // Validar apellidos
    if (empty($_POST["apellido"])) {
        $errores["errorApellido"] = "Campo apellido vacío";
    } else {
        $apellido = test_input($_POST["apellido"]);
        if (!preg_match("/^(?=.{3,36}$)[a-zñA-ZÑ](\s?[a-zñA-ZÑ])*$/", $apellido)) {
            $errores["errorApellido"] = "Los apellidos contienen caracteres inválidos";
        } else {
            $_SESSION["apellido"] = $apellido;
        }
    }

    // Validar contraseña
    if (empty($_POST["contraseña"])) {
        $errores["errorContraseña"] = "Campo contraseña vacío";
    } else {
        $contraseña = test_input($_POST["contraseña"]);
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\S]{10,}$/', $contraseña)) {
            $errores["errorContraseña"] = "La contraseña debe tener al menos 10 caracteres, una mayúscula, un número y un carácter especial.";
        }
    }

    // Validar confirmación de contraseña
    if (empty($_POST["compContraseña"])) {
        $errores["errorCompContraseña"] = "Campo confirmación de contraseña vacío";
    } else {
        $compContraseña = test_input($_POST["compContraseña"]);
        if ($contraseña === $compContraseña) {
            $_SESSION["contraseña"] = password_hash($compContraseña, PASSWORD_DEFAULT);
        } else {
            $errores["errorCompContraseña"] = "Las contraseñas no coinciden";
        }
    }

    // Validar email
    if (empty($_POST["correo"])) {
        $errores["errorCorreo"] = "Campo email vacío";
    } else {
        $email = test_input($_POST["correo"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores["errorCorreo"] = "Formato de email incorrecto";
        } else {
            $_SESSION["correo"] = $email;
        }
    }

    // Validar fecha de nacimiento
    if (empty($_POST["fechaNac"]) || !DateTime::createFromFormat('Y-m-d', $_POST["fechaNac"])) {
        $errores["errorFecha"] = "Campo fecha vacío o formato no válido";
    } else {
        $fecha = test_input($_POST["fechaNac"]);
        $fechaEnviada = new DateTime($fecha);
        $fecha_actual = new DateTime(date('Y-m-d'));
        if ($fecha_actual > $fechaEnviada) {
            $_SESSION["fecha"] = $fecha;
        } else {
            $errores["errorFecha"] = "La fecha no puede ser futura";
        }
    }

    // Si no hay errores, proceder a verificar el correo
    if (empty($errores)) {
        // Comprobar si el correo ya está registrado
        $usuarios_path = "../../dataUsuarios/users.json";
        if (file_exists($usuarios_path)) {
            $usuarios = json_decode(file_get_contents($usuarios_path), true);
            foreach ($usuarios as $usuario) {
                if ($usuario['email'] === $_SESSION["correo"]) {
                    $errores["errorCorreo"] = "El correo electrónico ya está en uso.";
                    break; // Salir del bucle si encontramos un duplicado
                }
            }
        }

        if (empty($errores)) {
            if (!file_exists('../../dataUsuarios')) {
                mkdir('../../dataUsuarios', 0777, true);
            }

            // Validar imagen
            $target_dir = "../registroImg/";
            $target_file = $target_dir . uniqid() . '-' . basename($_FILES["foto"]["name"]); // Nombres únicos para las imágenes
            $uploadOk = 1;

            if ($_FILES["foto"]["error"] == 0) {
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["foto"]["tmp_name"]);
                if ($check !== false) {
                    if ($_FILES["foto"]["size"] > 500000) {
                        $errores["errorImagen"] = "La imagen es demasiado grande.";
                        $uploadOk = 0;
                    }
                    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                        $errores["errorImagen"] = "Solo se permiten formatos JPG, JPEG, PNG y GIF.";
                        $uploadOk = 0;
                    }
                    if ($uploadOk && !move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                        $errores["errorImagen"] = "Error al subir la imagen.";
                    }
                } else {
                    $errores["errorImagen"] = "El archivo no es una imagen.";
                }
            } else {
                $target_file = $target_dir . "default.png"; 
            }

            if (empty($errores)) {
                $nuevo_usuario = [
                    "nombre" => $_SESSION["nombre"],
                    "apellido" => $_SESSION["apellido"],
                    "password" => $_SESSION["contraseña"],  
                    "email" => $_SESSION["correo"],
                    "fechaNacimiento" => $_SESSION["fecha"],
                    "foto" => $target_file  
                ];

                $usuarios[] = $nuevo_usuario;

                file_put_contents($usuarios_path, json_encode($usuarios, JSON_PRETTY_PRINT));

                header('Location: inicioSesion.php');
                exit();
            }else {
                $_SESSION["errores"] = $errores; 
                var_dump($errores);
                header('Location: registrarse.php');
                exit();
            }
        }else {
            $_SESSION["errores"] = $errores; 
            var_dump($errores);
            header('Location: registrarse.php');
            exit();
        }
    } else {
        $_SESSION["errores"] = $errores; 
        var_dump($errores);
        header('Location: registrarse.php');
        exit();
    }
    
}

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>
