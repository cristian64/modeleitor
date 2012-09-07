<?php
    require_once 'minilibreria.php';

    $usuario = getUsuario();
if ($usuario == null)
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.";
    header("location: iniciarsesion.php");
    exit();
}

if (!$usuario->getAdmin())
{
    $_SESSION["mensaje_error"] = "Esta sección necesita privilegios de administrador";
    header("location: index.php");
    exit();
}

$reserva = ENReserva::obtenerPorId(getPost("id"));

if ($reserva != null)
{
    $cobrado = getPost("cobrado");
    if (is_numeric($cobrado))
    {
        $reserva->setCobrado($cobrado);
        $reserva->actualizarCobro();
        echo "OK";
    }
}

?>
