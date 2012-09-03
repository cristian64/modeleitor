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
        $fabricante = ENFabricante::getById($id);
        if ($fabricante != null)
        {
            if ($fabricante->delete())
            {
                $_SESSION["mensaje_exito"] = "El fabricante $id ha sido eliminado correctamente";
                header("location: fabricantes");
                exit();
            }
        }
    }
    
    $_SESSION["mensaje_error"] = "No se pudo eliminar el fabricante $id";
    header("location: fabricantes");
    exit();
}

function editar()
{
    $id = getPost("id");
    
    $fabricante = ENFabricante::getById($id);
    if ($fabricante != null)
    {
        $fabricante->setNombre(getPost("nombre"));
        $fabricante->setDescripcion(getPost("descripcion"));
        $fabricante->setEmail(getPost("email"));
        $fabricante->setTelefono(getPost("telefono"));
        
        if ($fabricante->update())
        {
            $_SESSION["mensaje_exito"] = "El fabricante $id ha sido guardado correctamente";
            header("location: fabricantes");
            exit();
        }
        else
        {
            $_SESSION["mensaje_error"] = "No se pudo guardar el fabricante $id";
            header("location: fabricantes");
            exit();
        }        
    }
    else
    {
        $_SESSION["mensaje_error"] = "No se pudo encontrar el fabricante $id";
        header("location: fabricantes");
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


















