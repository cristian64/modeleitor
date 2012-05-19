<?php
include_once "base.php";

$usuario = getUsuario();
if ($usuario == null)
{
    header("location: iniciarsesion.php?aviso=Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.");
    exit();
}

$reservas = ENReserva::obtenerPorUsuario($usuario->getId(), $CANTIDAD_RESERVAS);

baseSuperior("Reservas");
?>
        <div id="reservas">
            <h3><span>Reservas</span></h3>
            <table>
                <tr class="cabecera">
                    <td>Nº de reserva</td>
                    <td>Pista</td>
                    <td>Día</td>
                    <td>Hora de inicio</td>
                    <td>Hora de fin</td>
                    <td>Duración<br />(minutos)</td>
                    <td>Estado</td>
                    <td>Fecha en la que<br />se realizó la reserva</td>
                </tr>
<?php
foreach ($reservas as $reserva)
{
    $clase = ($reserva->getEstado() ==  "Pendiente") ? "pendiente" : ($reserva->getEstado() == "Antigua" ? "antigua" : "encurso");
    echo "<tr class=\"$clase\" onclick=\"window.location = 'reserva.php?id=".$reserva->getId()."';\">\n";
    echo "<td>".rellenar($reserva->getId(), '0', $RELLENO)."</td>\n";
    echo "<td>".$reserva->getIdPista()."</td>\n";
    echo "<td>".$reserva->getFechaInicio()->format('d/m/Y')."</td>\n";
    echo "<td>".$reserva->getFechaInicio()->format('H:i')."</td>\n";
    echo "<td>".$reserva->getFechaFin()->format('H:i')."</td>\n";
    echo "<td>".$reserva->getDuracion()."</td>\n";
    echo "<td>".$reserva->getEstado()."</td>\n";
    echo "<td>".$reserva->getFechaRealizacion()->format('d/m/Y H:i:s')."</td>\n";
    echo "</tr>\n";
}
?>
            </table>
            <div><br />Sólo se muestran las últimas <?php echo $CANTIDAD_RESERVAS; ?> reservas.</div>
        </div>
<?php
baseInferior();
?>