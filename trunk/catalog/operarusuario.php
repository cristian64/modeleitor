<?php
include_once "minilibreria.php";

$usuario = getUsuario();
if ($usuario == null)
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.";
    header("location: .");
    exit();
}

if (!$usuario->getAdmin())
{
    $_SESSION["mensaje_aviso"] = "Área restringida a administradores.";
    header("location: .");
    exit();
}

$operacion = getPost("op");
switch ($operacion)
{
    case "eliminar":
        eliminar();
        break;
    case "anadir":
        anadir();
        break;
    case "editar":
        editar();
        break;
}

function eliminar()
{
    $id = getPost("id");
    if ($id != "")
    {
        $usuario = ENUsuario::getById($id);
        if ($usuario != null)
        {
            if ($usuario->delete())
            {
                $_SESSION["mensaje_exito"] = "El usuario $id ha sido eliminado correctamente";
                header("location: usuarios");
                exit();
            }
        }
    }
    
    $_SESSION["mensaje_error"] = "No se pudo eliminar el usuario $id";
    header("location: usuarios");
    exit();
}

function editar()
{
    $id = getPost("id");
    
    $usuario = ENUsuario::getById($id);
    if ($usuario != null)
    {
        //$usuario->setEmail(getPost("email"));
        $usuario->setNombre(getPost("nombre"));
        $usuario->setCif(getPost("cif"));
        $usuario->setDireccion(getPost("direccion"));
        $usuario->setTelefono(getPost("telefono"));
        $activoAntes = $usuario->getActivo();
        $usuario->setActivo(getPost("activo") == "yes");
        $contrasena = getPost("contrasena");
        $contrasena2 = getPost("contrasena2");
        $cambiada = false;
        if (strlen($contrasena) > 0 && $contrasena == $contrasena2)
        {
            $usuario->setContrasena(sha512($contrasena));
            $cambiada = true;
        }
        
        if ($usuario->update())
        {
            $_SESSION["mensaje_exito"] = "El usuario $id ha sido guardado correctamente";
            if ($cambiada)
                $_SESSION["mensaje_info"] = "La contraseña también se ha cambiado correctamente";
            if (!$activoAntes && $usuario->getActivo())
            {
                if (emailActivado($usuario))
                {
                    if (getSession("mensaje_info") == "")
                        $_SESSION["mensaje_info"] = "Se ha enviado un e-mail al cliente notificando la activación";
                    else
                        $_SESSION["mensaje_info"] = "\nSe ha enviado un e-mail al cliente notificando la activación";
                }
            }
            header("location: usuarios");
            exit();
        }
        else
        {
            $_SESSION["mensaje_error"] = "No se pudo guardar el usuario $id";
            header("location: usuarios");
            exit();
        }        
    }
    else
    {
        $_SESSION["mensaje_error"] = "No se pudo encontrar el usuario $id";
        header("location: usuarios");
        exit();
    }
}

function anadir()
{
    $fabricante = new ENFabricante();
    $fabricante->setNombre(getPost("nombre"));
    $fabricante->setDescripcion(getPost("descripcion"));
    $fabricante->setEmail(getPost("email"));
    $fabricante->setTelefono(getPost("telefono"));
    if ($fabricante->save())
    {
        $_SESSION["mensaje_exito"] = "El fabricante ha sido añadida correctamente";
        header("location: fabricantes");
        exit();
    }
    else
    {
        $_SESSION["mensaje_error"] = "No se pudo añadir el fabricante";
        header("location: fabricantes");
        exit();
    }
}


?>


















