<?php
    require_once 'minilibreria.php';

    if (getUsuario() != null)
    {
        header("location: index.php");
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

    header("location: iniciarsesion.php?error=Usuario o contraseña incorrecta");
    exit();

?>
