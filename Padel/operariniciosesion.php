<?php
    require_once 'minilibreria.php';

    ENIntentos::limpiar();
    
    if (getUsuario() != null)
    {
        header("location: index.php");
        exit();
    }
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $intentos = ENIntentos::contar($ip);
    if ($intentos >= ENIntentos::$MAXINTENTOS)
    {
        $_SESSION["mensaje_error"] = "Se ha superado el número máximo de intentos. Espera ".ENIntentos::$MINUTOS." minutos para poder probar otra vez.";
        header("location: iniciarsesion.php");
        exit();
    }
        
    // Procesar parametros e intentar identificar al usuario.
    $email = getPost("email");
    $contrasena = getPost("contrasena");
    $usuario = ENUsuario::obtenerPorEmail($email);
    if ($usuario != null)
    {
        if ($usuario->getContrasena() == sha1($contrasena))
        {
            $_SESSION["usuario"] = serialize($usuario);

            // Comprobamos si hay que recordar el usuario.
            if (getPost("recordar") == "on")
            {
                // Guardamos el usuario y la contraseña en una cookie.
                setcookie("email", $email, time() + (30 * 86400));
                setcookie("contrasena", sha1($contrasena), time() + (30 * 86400));
            }
            header("location: index.php");
            exit();
        }
    }

    ENIntentos::guardar($ip);
    
    
    $_SESSION["mensaje_error"] = "Usuario o contraseña incorrecta";
    
    if ($intentos + 1 == ENIntentos::$MAXINTENTOS)
        $_SESSION["mensaje_error"] = "Usuario o contraseña incorrecta. Se ha superado el número máximo de intentos. Espera ".ENIntentos::$MINUTOS." minutos para poder probar otra vez.";
    else if ($intentos + 2 == ENIntentos::$MAXINTENTOS)
        $_SESSION["mensaje_error"] = "Usuario o contraseña incorrecta. Sólo queda un último intento.";
    
    header("location: iniciarsesion.php");
    exit();

?>
