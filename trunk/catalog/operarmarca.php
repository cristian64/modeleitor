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
        $marca = ENMarca::getById($id);
        if ($marca != null)
        {
            if ($marca->delete())
            {
                $_SESSION["mensaje_exito"] = "La marca $id ha sido eliminada correctamente";
                header("location: marcas");
                exit();
            }
        }
    }
    
    $_SESSION["mensaje_error"] = "No se pudo eliminar la marca $id";
    header("location: marcas");
    exit();
}

function editar()
{
    $id = getPost("id");
    
    $marca = ENMarca::getById($id);
    if ($marca != null)
    {
        $marca->setNombre(getPost("nombre"));
        if ($marca->saveLogo($_FILES["logo"]))
        {
            $_SESSION["mensaje_info"] = "El logo ha sido cambiado correctamente";
        }
        
        if ($marca->update())
        {
            $_SESSION["mensaje_exito"] = "La marca $id ha sido guardada correctamente";
            header("location: marcas");
            exit();
        }
        else
        {
            $_SESSION["mensaje_error"] = "No se pudo guardar la marca $id";
            header("location: marcas");
            exit();
        }        
    }
    else
    {
        $_SESSION["mensaje_error"] = "No se pudo encontrar la marca $id";
        header("location: marcas");
        exit();
    }
}

function anadir()
{
    $marca = new ENMarca();
    $marca->setNombre(getPost("nombre"));
    if ($marca->save())
    {
        $marca->saveLogo($_FILES["logo"]);        
        if ($marca->update())
        {
            $_SESSION["mensaje_exito"] = "La marca ha sido añadida correctamente";
            header("location: marcas");
            exit();
        }
    }
    $_SESSION["mensaje_error"] = "No se pudo añadir la marca";
    header("location: marcas");
    exit();
}


?>


















