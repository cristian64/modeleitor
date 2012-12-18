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

$ahora = new DateTime();
$ahora = $ahora->getTimestamp();
$usuarios = ENUsuario::obtenerTodos(0, 9999999, "");
foreach ($usuarios as $i) {
    
    $reservas = ENReserva::obtenerPorUsuario($i->getId(), 9999999);
    $ultimaReserva = count($reservas) > 0 ? $reservas[0] : null;
    
    echo $i->getId().",";
    echo "\"".$i->getEmail()."\",";
    echo "\"".$i->getNombre()."\",";
    echo "\"".$i->getDni()."\",";
    echo "\"".$i->getDireccion()."\",";
    echo "\"".$i->getTelefono()."\",";
    echo "\"".$i->getSexo()."\",";
    echo "\"".$i->getFechaRegistro()->format("Y-m-d H-i-s")."\",";
    echo "\"".disponibilidadString($i->getDisponibilidad())."\",";
    echo "\"".$i->getCategoria()."\",";
    echo "\"".count($reservas)."\",";
    if ($ultimaReserva != null) {
        echo "\"".$ultimaReserva->getFechaInicio()->format("Y-m-d H-i-s")."\",";
        echo "\"".round(($ahora - $ultimaReserva->getFechaInicio()->getTimestamp()) / (60 * 60 * 24), 0)."\",";
    } else {
        echo ",";
        echo ",";
    }
    echo "\n";
}

?>
