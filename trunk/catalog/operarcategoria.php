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

borrar("bloqueCategorias.php");
borrar("bloqueCategoriasAdmin.php");

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
        $categoria = ENCategoria::getById($id);
        if ($categoria != null)
        {
            if ($categoria->delete())
            {
                $_SESSION["mensaje_exito"] = "La categoría $id ha sido eliminada correctamente";
                header("location: categorias");
                exit();
            }
        }
    }
    
    $_SESSION["mensaje_error"] = "No se pudo eliminar la categoría $id";
    header("location: categorias");
    exit();
}

function editar()
{
    $id = getPost("id");
    
    $categoria = ENCategoria::getById($id);
    if ($categoria != null)
    {
        $categoria->setNombre(getPost("nombre"));
        $categoria->setDescripcion(getPost("descripcion"));
        $categoria->setZindex(getPost("zindex"));
        $categoria->setMostrar(getPost("mostrar") == "yes");
        if ($categoria->update())
        {
            $_SESSION["mensaje_exito"] = "La categoría $id ha sido guardada correctamente";
            header("location: categorias");
            exit();
        }
        else
        {
            $_SESSION["mensaje_error"] = "No se pudo guardar la categoría $id";
            header("location: categorias");
            exit();
        }        
    }
    else
    {
        $_SESSION["mensaje_error"] = "No se pudo encontrar la categoría $id";
        header("location: categorias");
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
            header("location: categorias");
            exit();
        }
        else
        {
            $_SESSION["mensaje_error"] = "No se pudo añadir la categoría";
            header("location: categorias");
            exit();
        }        
    }
    else
    {
        $_SESSION["mensaje_error"] = "No se pudo encontrar la categoría padre $id_padre";
        header("location: categorias");
        exit();
    }
}


?>


















