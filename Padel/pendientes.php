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
    $_SESSION["mensaje_error"] = "Esta sección necesita privilegios de administrador";
    header("location: index.php");
    exit();
}

$reservas = ENReserva::obtenerPendientes();

baseSuperior("Reservas pendientes");
?>
        <div id="reservas">
            <h3><span>Reservas pendientes</span></h3>
            <table>
                <tr class="filacabecera">
                    <td class="cabecera">Nº de reserva</td>
                    <td class="cabecera">Pista</td>
                    <td class="cabecera">Día</td>
                    <td class="cabecera">Hora de inicio</td>
                    <td class="cabecera">Hora de fin</td>
                    <td class="cabecera">Duración<br />(minutos)</td>
                    <td class="cabecera">Precio<br />(a pagar en ventanilla)</td>
                    <td class="cabecera">Estado</td>
                    <td class="cabecera">Fecha en la que<br />se realizó la reserva</td>
                </tr>
<?php
foreach ($reservas as $reserva)
{
    if (!$reserva->getReservable())
        continue;
        
    $clase = ($reserva->getEstado() ==  "Pendiente") ? "pendiente" : ($reserva->getEstado() == "Finalizada" ? "finalizada" : "encurso");
    echo "<tr class=\"$clase\" onclick=\"window.location = 'reserva.php?id=".$reserva->getId()."';\">\n";
    echo "<td>".rellenar($reserva->getId(), '0', $RELLENO)."</td>\n";
    echo "<td>".$reserva->getIdPista()."</td>\n";
    echo "<td>".$reserva->getFechaInicio()->format('d/m/Y')."</td>\n";
    echo "<td>".$reserva->getFechaInicio()->format('H:i')."</td>\n";
    echo "<td>".$reserva->getFechaFin()->format('H:i')."</td>\n";
    echo "<td>".$reserva->getDuracion()."</td>\n";
    echo "<td>".ceil($reserva->getDuracion() * $PRECIOHORA / 60)."€</td>\n";
    echo "<td>".$reserva->getEstado()."</td>\n";
    echo "<td>".$reserva->getFechaRealizacion()->format('d/m/Y H:i:s')."</td>\n";
    echo "</tr>\n";
}
if (count($reservas) == 0)
{
    echo "<tr><td colspan=\"9\"><br /><br /><br />No hay reservas pendientes<br /><br /><br /><br /></td>";
}
?>
            </table>
            <div id="leyenda">
                <div class="pendiente"></div>Pendiente
                <div class="encurso"></div>En curso
                <div class="finalizada"></div>Finalizada
            </div>
            <div><br />Sólo se muestran las reservas del día de ayer en adelante</div>
        </div>
<?php
baseInferior();
?>
