<?php
    require_once 'minilibreria.php';
    
    if (getUsuario() != null)
    {
        header("location: index.php");
        exit();
    }
    
    require_once('recaptchalib.php');

    $kMinContrasena = 4;
    $kMaxContrasena = 50;
    
    // Se procesan los parámetros que llegan por post.
    $nombre = $_POST["nombre"];
    $contrasena = $_POST["contrasena"];
    $contrasena2 = $_POST["contrasena2"];
    $email = $_POST["email"];
    $sexo = ($_POST["sexo"] == "hombre") ? "hombre" : "mujer";
    $dni = $_POST["dni"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];

    $verify = recaptcha_check_answer($PRIVATEKEY, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
    if (!$verify->is_valid)
    {
        header("location: registrarse.php?error=El código de seguridad no se introdujo correctamente");
        exit();
    }

    // Se comprueban los parámetros.
    $usuario = ENUsuario::obtenerPorEmail($email);
    if ($usuario != null)
    {
        header("location: registrarse.php?error=Ya existe un usuario con el nombre $email.");
        exit();
    }
    else
    {
        if ($contrasena != $contrasena2)
        {
            header("location: registrarse.php?error=Las contraseñas no coinciden.");
            exit();
        }
        else
        {
            if (strlen($contrasena) > $kMaxContrasena || strlen($contrasena) < $kMinContrasena)
            {
                header("location: registrarse.php?error=La contraseñas debe tener entre $kMinContrasena y $kMaxContrasena");
                exit();
            }
        }
    }

    $nuevo = new ENUsuario;
    $nuevo->setEmail($email);
    $nuevo->setNombre($nombre);
    $nuevo->setContrasena(sha1($contrasena));
    $nuevo->setSexo($sexo);
    $nuevo->setDni($dni);
    $nuevo->setDireccion($direccion);
    $nuevo->setTelefono($telefono);
    $registrado = $nuevo->guardar();

    if ($registrado)
    {
        $_SESSION["usuario"] = serialize($nuevo);
        header("location: index.php?exito=Usuario registrado correctamente");
    }
    else
    {
        header("location: registrarse.php?exito=Ocurrió un fallo al registrarse");
    }
?>
