<?php
    require_once 'minilibreria.php';
    
    if (getUsuario() != null)
    {
        header("location: index.php");
        exit();
    }
    
    require_once('recaptchalib.php');
    
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
    $categoria = getPost("categoria");
    $disponibilidad = 0;
    $array_disponibilidad = $_POST['disponibilidad'];
    if (!empty($array_disponibilidad)) {
        for ($i = 0; $i < count($array_disponibilidad); $i++) {
            $disponibilidad += $array_disponibilidad[$i];
        }
    }
    
    $_SESSION["registro_nombre"] = $nombre;
    $_SESSION["registro_contrasena"] = $contrasena;
    $_SESSION["registro_contrasena2"] = $contrasena2;
    $_SESSION["registro_email"] = $email;
    $_SESSION["registro_sexo"] = $sexo;
    $_SESSION["registro_dni"] = $dni;
    $_SESSION["registro_direccion"] = $direccion;
    $_SESSION["registro_telefono"] = $telefono;
    $_SESSION["registro_categoria"] = $categoria;
    $_SESSION["registro_disponibilidad"] = $disponibilidad;

    $verify = recaptcha_check_answer($PRIVATEKEY, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
    if (!$verify->is_valid)
    {
        $_SESSION["mensaje_error"] = "El código de seguridad no se introdujo correctamente";
        header("location: registrarse.php");
        exit();
    }

    // Se comprueban los parámetros.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $_SESSION["mensaje_error"] = "El e-mail $email no tiene un formato adecuado";
        header("location: registrarse.php");
        exit();
    }
    else
    {
        $usuario = ENUsuario::obtenerPorEmail($email);
        if ($usuario != null)
        {
            $_SESSION["mensaje_error"] = "Ya existe un usuario con el e-mail $email";
            header("location: registrarse.php");
            exit();
        }
        else
        {
            if (strlen($nombre) > $kMaxNombre || strlen($nombre) < $kMinNombre)
            {
                $_SESSION["mensaje_error"] = "El nombre debe tener entre $kMinNombre y $kMaxNombre";
                header("location: registrarse.php");
                exit();
            }
            else
            {
                if ($contrasena != $contrasena2)
                {
                    $_SESSION["mensaje_error"] = "Las contraseñas no coinciden";
                    header("location: registrarse.php");
                    exit();
                }
                else
                {
                    if (strlen($contrasena) > $kMaxContrasena || strlen($contrasena) < $kMinContrasena)
                    {
                        $_SESSION["mensaje_error"] = "La contraseña debe tener entre $kMinContrasena y $kMaxContrasena";
                        header("location: registrarse.php");
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
    $nuevo->setCategoria($categoria);
    $nuevo->setDisponibilidad($disponibilidad);
    $registrado = $nuevo->guardar();

    if ($registrado)
    {
        $_SESSION["mensaje_exito"] = "Usuario registrado correctamente";
        $_SESSION["usuario"] = serialize($nuevo);
        
        $_SESSION["registro_nombre"] = "";
        $_SESSION["registro_contrasena"] = "";
        $_SESSION["registro_contrasena2"] = "";
        $_SESSION["registro_email"] = "";
        $_SESSION["registro_sexo"] = "";
        $_SESSION["registro_dni"] = "";
        $_SESSION["registro_direccion"] = "";
        $_SESSION["registro_telefono"] = "";
        $_SESSION["registro_categoria"] = "";
        $_SESSION["registro_disponibilidad"] = "";
        
        header("location: index.php");
    }
    else
    {
        $_SESSION["mensaje_error"] = "Ocurrió un fallo al registrarse";
        header("location: registrarse.php");
    }
?>
