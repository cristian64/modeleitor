<?php
    require_once 'minilibreria.php';
    
    if (getUsuario() != null)
    {
        header("location: index.php");
        exit();
    }
    
    $ip = $_SERVER['REMOTE_ADDR'];
    if (ENAcceso::isGranted($ip))
    {        
        // Procesar parametros e intentar identificar al usuario.
        $email = getPost("email");
        $contrasena = getPost("contrasena");
        $usuario = ENUsuario::getByEmail($email);
        if ($usuario != null)
        {
            if ($usuario->getContrasena() == sha512($contrasena))
            {
                if (!$usuario->getActivo())
                {
                    $_SESSION["mensaje_error"] = "No se pudo iniciar sesión. Tu cuenta está a la espera de ser activada por un administrador.";
                }
                else
                {
                    $_SESSION["usuario"] = serialize($usuario);

                    // Comprobamos si hay que recordar el usuario.
                    if (getPost("recordar") == "on")
                    {
                        // Guardamos el usuario y la contraseña en una cookie.
                        setcookie("email", $email, time() + (30 * 86400));
                        setcookie("contrasena", sha512($contrasena), time() + (30 * 86400));
                    }
                    $_SESSION["mensaje_exito"] = "Sesión iniciada correctamente";
                }
                
                ENAcceso::save($ip, $usuario->getId(), true);
                
                header("location: index.php");
                exit();
            }
        }
        
        ENAcceso::save($ip, $usuario != null ? $usuario->getId() : 0, false);
        
        $_SESSION["mensaje_error"] = "Usuario o contraseña incorrecta";
        header("location: clientes");
        exit();
    }
    else
    {
        $_SESSION["mensaje_error"] = "Se ha superado el número máximo de intentos. Vuelve a intentarlo en unos minutos.";
        header("location: clientes");
        exit();
    }

?>
