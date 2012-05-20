<?php
include_once "minilibreria.php";

$usuario = getUsuario();
if ($usuario == null)
{
    header("location: iniciarsesion.php?aviso=Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.");
    exit();
}

$id = getPost("id");
if ($id == "")
{
    header("location: index.php?aviso=No existe la reserva que se buscaba");
    exit();
}

$reserva = ENReserva::obtenerPorId($id);
if ($reserva == null)
{
    header("location: index.php?aviso=No existe la reserva nº $id");
    exit();
}

if ($reserva->getIdUsuario() != $usuario->getId() && !$usuario->getAdmin())
{
    header("location: index.php?aviso=La reserva que se busca no existe en tu lista de resevas");
    exit();
}

if ($reserva->getEstado() != "Pendiente" && !$usuario->getAdmin())
{
    header("location: reserva.php?id=$id&error=No se puede cancelar la reserva porque ha transcurrido mucho tiempo");
    exit();
}

if (ENReserva::borrarPorId($reserva->getId()))
{
    header("location: reservas.php?exito=La reserva ha sido cancelada correctamente");
    exit();
}

header("location: reservas.php?error=Ocurrió un error al borrar la reserva");
