<?php
include_once "minilibreria.php";

$usuario = getUsuario();
if ($usuario == null || !$usuario->getAdmin())
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.";
    header("location: iniciarsesion.php");
    exit();
}

$id = getGet("id");
if ($id == "")
{
    $_SESSION["mensaje_aviso"] = "No existe usuario que se buscaba";
    header("location: index.php");
    exit();
}

$u = ENUsuario::obtenerPorId($id);
if ($u == null)
{
    $_SESSION["mensaje_aviso"] = "No existe el usuario nº $id";
    header("location: usuarios.php");
    exit();
}

if ($usuario->getId() == $id)
{
    $_SESSION["mensaje_aviso"] = "No puedes eliminarte a ti mismo";
    header("location: usuario.php");
    exit();
}

if (ENUsuario::borrarPorId($id))
{
    $_SESSION["mensaje_exito"] = "El usuario ha sido eliminado correctamente";
    header("location: index.php");
    exit();
}

$_SESSION["mensaje_error"] = "Ocurrió un error al eliminar el usuario";
header("location: index.php");
