<?php
include_once "minilibreria.php";

$usuario = getUsuario();
if ($usuario == null)
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.";
    header("location: iniciarsesion.php");
    exit();
}

    
$scroll = getPost("scroll");
if ($scroll != "")
    $scroll = "&scroll=".$scroll;

$retorno = getPost("retorno");
if ($retorno == "")
    $retorno = "index.php";
else
    $retorno = $retorno.$scroll;
        

$id = getPost("id");
if ($id == "")
{
    $_SESSION["mensaje_aviso"] = "No existe la reserva que se buscaba";
    header("location: index.php");
    exit();
}

$reserva = ENReserva::obtenerPorId($id);
if ($reserva == null)
{
    $_SESSION["mensaje_aviso"] = "No existe la reserva nº $id";
    header("location: index.php");
    exit();
}

if ($reserva->getIdUsuario() != $usuario->getId() && !$usuario->getAdmin())
{
    $_SESSION["mensaje_aviso"] = "La reserva que se busca no existe en tu lista de resevas";
    header("location: index.php");
    exit();
}

if ($reserva->getEstado() != "Pendiente" && !$usuario->getAdmin())
{
    $_SESSION["mensaje_error"] = "No se puede cancelar la reserva porque ha transcurrido mucho tiempo";
    header("location: $retorno");
    exit();
}

if (ENReserva::borrarPorId($reserva->getId()))
{
    $_SESSION["mensaje_exito"] = "La reserva ha sido cancelada correctamente";
    $usuarioReserva = ENUsuario::obtenerPorId($reserva->getIdUsuario());
    if ($reserva->getTipo() == 0)
    {
        emailCancelarReserva("beatriz@autofima.com", $usuarioReserva, $reserva);
        emailCancelarReserva("Santiago@autofima.com", $usuarioReserva, $reserva);
        emailCancelarReserva("emihyundai@hotmail.com", $usuarioReserva, $reserva);
        emailCancelarReserva("fran@padelelche.com", $usuarioReserva, $reserva);
    }
    header("location: $retorno");
    exit();
}

$_SESSION["mensaje_error"] = "Ocurrió un error al borrar la reserva";
header("location: $retorno");
