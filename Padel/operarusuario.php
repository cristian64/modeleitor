<?php
    require_once 'minilibreria.php';
    
    $usuario = getUsuario();
    if ($usuario == null)
    {
        $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder guardar los datos.";
        header("location: iniciarsesion.php");
        exit();
    }
    
    $kMinContrasena = 3;
    $kMaxContrasena = 100;
    $kMinNombre = 4;
    $kMaxNombre = 100;
    
    // Se procesan los parámetros que llegan por post.
    $id = getPost("id");
    $nombre = getPost("nombre");
    $contrasena3 = getPost("contrasena3");
    $contrasena = getPost("contrasena");
    $contrasena2 = getPost("contrasena2");
    $email = getPost("email");
    $sexo = (getPost("sexo") == "hombre") ? "hombre" : "mujer";
    $dni = getPost("dni");
    $admin = getPost("admin") == "1" ? true : false;
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
    
    $op = getPost("op");
    if ($op == "anadir" && $usuario->getAdmin())
    {
        $u = new ENUsuario();
        $u->setEmail($email);
        $u->setNombre($nombre);
        $u->setSexo($sexo);
        $u->setContrasena(sha1($contrasena));
        $u->setDni($dni);
        $u->setDireccion($direccion);
        $u->setTelefono($telefono);
        $u->setCategoria($categoria);
        $u->setDisponibilidad($disponibilidad);
                
        // Se comprueban los parámetros.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $_SESSION["mensaje_error"] = "El e-mail $email no tiene un formato adecuado";
            header("location: usuarios.php");
            exit();
        }
        else
        {
            $otrouser = ENUsuario::obtenerPorEmail($email);
            if ($otrouser != null && $otrouser->getId() != $u->getId())
            {
                $_SESSION["mensaje_error"] = "Ya existe un usuario con el e-mail $email";
                header("location: usuarios.php");
                exit();
            }
            else
            {       
                $guardar = $u->guardar();
                if ($guardar)
                {
                    $_SESSION["mensaje_exito"] = "Usuario añadido correctamente";
                    header ("location: usuario.php?id=".$u->getId());
                }
                else
                {
                    $_SESSION["mensaje_error"] = "No se pudo guardar el usuario";
                    header ("location: usuarios.php");
                }
            }
        }
        exit();
    }
    
    $u = ENUsuario::obtenerPorId($id);
    if ($u == null)
    {
        $_SESSION["mensaje_error"] = "No se ha encontrado el usuario";
        header("location: index.php");
        exit();
    }
    
    if (!$usuario->getAdmin() && $u->getId() != $usuario->getId())
    {
        $_SESSION["mensaje_error"] = "No tienes acceso a este usuario";
        header("location: index.php");
        exit();
    }

    // Se comprueban los parámetros.
    //if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    //{
    //    $_SESSION["mensaje_error"] = "El e-mail $email no tiene un formato adecuado";
    //    header("location: usuario.php?id=$id");
    //    exit();
    //}
    //else
    //{
        //$otrouser = ENUsuario::obtenerPorEmail($email);
        //if ($otrouser != null && $otrouser->getId() != $u->getId())
        //{
        //    $_SESSION["mensaje_error"] = "Ya existe un usuario con el e-mail $email";
        //    header("location: usuario.php?id=$id");
        //    exit();
        //}
        //else
        //{
            if (strlen($nombre) > $kMaxNombre || strlen($nombre) < $kMinNombre)
            {
                $_SESSION["mensaje_error"] = "El nombre debe tener entre $kMinNombre y $kMaxNombre";
                header("location: usuario.php?id=$id");
                exit();
            }
            else
            {
                if ($contrasena != "" || $contrasena2 != "" || $contrasena3 != "")
                {
                    if (sha1($contrasena3) != $u->getContrasena() && $usuario->getId() == $u->getId())
                    {
                        $_SESSION["mensaje_error"] = "La contraseña anterior no es correcta";
                        header("location: usuario.php?id=$id");
                        exit();
                    }
                    else
                    {
                        if ($contrasena != $contrasena2)
                        {
                            $_SESSION["mensaje_error"] = "La nueva contraseña no coincide con la repetición";
                            header("location: usuario.php?id=$id");
                            exit();
                        }
                        else
                        {
                            if (strlen($contrasena) > $kMaxContrasena || strlen($contrasena) < $kMinContrasena)
                            {
                                $_SESSION["mensaje_error"] = "La nueva contraseña debe tener entre $kMinContrasena y $kMaxContrasena";
                                header("location: usuario.php?id=$id");
                                exit();
                            }
                        }
                    }
                }
            }
        //}
    //}

    //$u->setEmail($email);
    $u->setNombre($nombre);
    $u->setSexo($sexo);
    if ($contrasena != "")
        $u->setContrasena(sha1($contrasena));
    if ($usuario->getAdmin() && $usuario->getId() != $u->getId())
        $u->setAdmin($admin);
    $u->setDni($dni);
    $u->setDireccion($direccion);
    $u->setTelefono($telefono);
    $u->setCategoria($categoria);
    $u->setDisponibilidad($disponibilidad);
    $actualizado = $u->actualizar();

    if ($actualizado)
    {
        $_SESSION["mensaje_exito"] = "Usuario guardado correctamente";
        if ($usuario->getId() == $u->getId())
            $_SESSION["usuario"] = serialize($u);
        header("location: usuario.php?id=$id");
    }
    else
    {
        $_SESSION["mensaje_error"] = "Ocurrió un fallo guardar los datos";
        header("location: usuario.php?id=$id");
    }
?>
