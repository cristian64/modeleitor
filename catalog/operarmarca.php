<?php
include_once "minilibreria.php";

$usuario = getUsuario();
if ($usuario == null)
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.";
    header("location: index.php");
    exit();
}

if (!$usuario->getAdmin())
{
    $_SESSION["mensaje_aviso"] = "Área restringida a administradores.";
    header("location: index.php");
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
    $id_padre = getPost("id_padre");
    if (intval($id_padre) == 0 || ENCategoria::getById($id_padre) != null)
    {
        $categoria = new ENCategoria();
        $categoria->setIdPadre($id_padre);
        $categoria->setNombre(getPost("nombre"));
        $categoria->setDescripcion(getPost("descripcion"));
        $categoria->setZindex(getPost("zindex"));
        $categoria->setMostrar(getPost("mostrar") == "yes");
        if ($categoria->save())
        {
            $_SESSION["mensaje_exito"] = "La categoría ha sido añadida correctamente";
            header("location: marcas");
            exit();
        }
        else
        {
            $_SESSION["mensaje_error"] = "No se pudo añadir la categoría";
            header("location: marcas");
            exit();
        }        
    }
    else
    {
        $_SESSION["mensaje_error"] = "No se pudo encontrar la categoría padre $id_padre";
        header("location: marcas");
        exit();
    }
}


?>


















