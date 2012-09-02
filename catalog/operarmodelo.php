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
        $modelo = ENModelo::getById($id);
        if ($modelo != null)
        {
            if ($modelo->delete())
            {
                $_SESSION["mensaje_exito"] = "El modelo $id ha sido eliminado correctamente";
                header("location: modelos");
                exit();
            }
        }
    }
    
    $_SESSION["mensaje_error"] = "No se pudo eliminar el modelo $id";
    header("location: modelos");
    exit();
}

function editar()
{
    $id = getPost("id");
    
    $modelo = ENModelo::getById($id);
    if ($modelo != null)
    {
        $modelo->setReferencia(getPost("referencia"));
        $modelo->setNombre(getPost("nombre"));
        $modelo->setTallaMenor(getPost("talla_menor"));
        $modelo->setTallaMayor(getPost("talla_mayor"));
        $modelo->setPrecio(getPost("precio"));
        $modelo->setPrecioOferta(getPost("precio_oferta"));
        $modelo->setDescripcion($_POST["descripcion"]);
        $modelo->setIdFabricante(getPost("id_fabricante"));
        $modelo->setIdMarca(getPost("id_marca"));
        $modelo->setPrioridad(getPost("prioridad"));
        
        if (ENFabricante::getById($modelo->getIdFabricante()) == null)
            $modelo->setIdFabricante(0);
        if (ENMarca::getById($modelo->getIdMarca()) == null)
            $modelo->setIdMarca(0);
            
        $modelo->setOferta(getPost("oferta") == "on");
        $modelo->setDescatalogado(getPost("descatalogado") == "on");
            
        if ($modelo->update())
        {
            $modelo->saveFoto($_FILES["foto"]);
            if ($modelo->update())
            {
                $categorias = $_POST["categorias"];
                if (is_array($categorias))
                {
                    $categoriasAux = array();
                    foreach ($categorias as $c)
                    {
                        if (ENCategoria::getById($c) != null)
                        {
                            $categoriasAux[] = $c;
                        }
                    }
                    $modelo->setCategoriasToDB($categoriasAux);
                }
                
                $_SESSION["mensaje_exito"] = "El modelo ha sido guardado correctamente";
                header("location: modelo?id=".$modelo->getId());
                exit();
            }
        }
        $_SESSION["mensaje_error"] = "No se pudo guardar el modelo";
        header("location: modelo?id=".$modelo->getId());
        exit();    
    }
    else
    {
        $_SESSION["mensaje_error"] = "No se pudo encontrar el modelo $id";
        header("location: modelos");
        exit();
    }
}

function anadir()
{
    $modelo = new ENModelo();
    $modelo->setReferencia(getPost("referencia"));
    $modelo->setNombre(getPost("nombre"));
    $modelo->setTallaMenor(getPost("talla_menor"));
    $modelo->setTallaMayor(getPost("talla_mayor"));
    $modelo->setPrecio(getPost("precio"));
    $modelo->setPrecioOferta(getPost("precio_oferta"));
    $modelo->setDescripcion(getPost("descripcion"));
    $modelo->setIdFabricante(getPost("id_fabricante"));
    $modelo->setIdMarca(getPost("id_marca"));
    $modelo->setPrioridad(getPost("prioridad"));
    
    if (ENFabricante::getById($modelo->getIdFabricante()) == null)
        $modelo->setIdFabricante(0);
    if (ENMarca::getById($modelo->getIdMarca()) == null)
        $modelo->setIdMarca(0);
        
    $modelo->setOferta(getPost("oferta") == "on");
    $modelo->setDescatalogado(getPost("descatalogado") == "on");
        
    if ($modelo->save())
    {
        $modelo->saveFoto($_FILES["foto"]);
        if ($modelo->update())
        {
            $categorias = $_POST["categorias"];
            if (is_array($categorias))
            {
                $categoriasAux = array();
                foreach ($categorias as $c)
                {
                    if (ENCategoria::getById($c) != null)
                    {
                        $categoriasAux[] = $c;
                    }
                }
                $modelo->setCategoriasToDB($categoriasAux);
            }
            
            $_SESSION["mensaje_exito"] = "El modelo ha sido añadido correctamente";
            header("location: modelos");
            //TODO
            //header("location: modelo?id=".$modelo->getId());
            exit();
        }
    }
    $_SESSION["mensaje_error"] = "No se pudo añadir el modelo";
    header("location: nuevomodelo");
    exit();
}


?>


















