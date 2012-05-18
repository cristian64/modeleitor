<?php
    require_once 'minilibreria.php';

    if (isset($_SESSION["usuario"]))
    {
        header("location: index.php");
        exit();
    }
        
    // Procesar parametros e intentar identificar al usuario.
    $email = $_POST["email"];
    $contrasena = $_POST["contrasena"];
    $usuario = ENUsuario::obtenerPorEmail($email);
    if ($usuario != null)
    {
        if ($usuario->getContrasena() == sha1($contrasena))
        {
            $_SESSION["usuario"] = serialize($usuario);

            // Comprobamos si hay que recordar el usuario.
            if ($_POST["recordar"] == "on")
            {
                // Guardamos el usuario y la contraseña en una cookie.
                setcookie("email", $email, time() + (30 * 86400));
                setcookie("contrasena", $contrasena, time() + (30 * 86400));
            }
            header("location: index.php");
            exit();
        }
    }

    header("location: iniciarsesion.php?error=Usuario o contraseña incorrecta");
    exit();

?>