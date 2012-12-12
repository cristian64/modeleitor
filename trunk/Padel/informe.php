<?php
include_once "base.php";

$usuario = getUsuario();
if ($usuario == null)
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.";
    header("location: iniciarsesion.php");
    exit();
}

if (!$usuario->getAdmin())
{
    header("location: index.php");
    exit();
}

if ($usuario->getId() != 1 && $usuario->getId() != 2 && $usuario->getId() != 25 && $usuario->getId() != 30)
{
    header("location: index.php");
    exit();
}

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=informe_".date("Y-m-d_H-i-s").".csv");
header("Pragma: no-cache");
header("Expires: 0");

echo "id,email,nombre,dni,dirección,teléfono,sexo,fecha de registro,disponibilidad,categoría,reservas totales,fecha de la última reserva,días transcurridos desde la última reserva,\n";

$usuarios = ENUsuario::obtenerTodos(0, 9999999, "");
foreach ($usuarios as $i) {
    echo $i->getId().",";
    echo "\"".$i->getEmail()."\",";
    echo "\"".$i->getNombre()."\",";
    echo "\"".$i->getDni()."\",";
    echo "\"".$i->getDireccion()."\",";
    echo "\"".$i->getTelefono()."\",";
    echo "\"".$i->getSexo()."\",";
    echo "\"".$i->getFechaRegistro()->format("Y-m-d H-i-s")."\",";
    echo "\"".$i->getDisponibilidad()."\",";
    echo ",";//echo "\"".$i->getCategoria()."\",";
    echo ",";//echo "\"".$i->getReservasTotales()."\",";
    echo ",";//echo "\"".$i->getUltimaReserva()."\",";
    echo ",";//echo "\"".$i->getDiasTranscurridos()."\",";
    echo "\n";
}

?>
