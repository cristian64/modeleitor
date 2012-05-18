<?php
    require_once 'minilibreria.php';
    
    if (getUsuario() != null)
    {
        header("location: index.php");
        exit();
    }
    
    require_once('recaptchalib.php');

    $verify = recaptcha_check_answer($PRIVATEKEY, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
    if (!$verify->is_valid)
    {
        header("location: registrarse.php?error=El código de seguridad no se introdujo correctamente");
        exit();
    }
    
    $kMinContrasena = 3;
    $kMaxContrasena = 100;
    $kMinNombre = 4;
    $kMaxNombre = 100;
    
    // Se procesan los parámetros que llegan por post.
    $nombre = getPost("nombre");
    $contrasena = getPost("contrasena");
    $contrasena2 = getPost("contrasena2");
    $email = getPost("email");
    $sexo = (getPost("sexo") == "hombre") ? "hombre" : "mujer";
    $dni = getPost("dni");
    $direccion = getPost("direccion");
    $telefono = getPost("telefono");

    // Se comprueban los parámetros.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        header("location: registrarse.php?error=El e-mail $email no tiene un formato adecuado.");
        exit();
    }
    else
    {
        $usuario = ENUsuario::obtenerPorEmail($email);
        if ($usuario != null)
        {
            header("location: registrarse.php?error=Ya existe un usuario con el e-mail $email.");
            exit();
        }
        else
        {
            if (strlen($nombre) > $kMaxNombre || strlen($nombre) < $kMinNombre)
            {
                header("location: registrarse.php?error=El nombre debe tener entre $kMinNombre y $kMaxNombre");
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
                        header("location: registrarse.php?error=La contraseña debe tener entre $kMinContrasena y $kMaxContrasena");
                        exit();
                    }
                }
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
        header("location: registrarse.php?error=Ocurrió un fallo al registrarse");
    }
?>
