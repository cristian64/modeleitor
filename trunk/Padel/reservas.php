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

$filtro = filtrarCadena(getGet("filtro"));
$reservas = ENReserva::obtenerPendientes($filtro);

baseSuperior("Reservas");
?>
        <div id="reservas">
            <div id="busqueda">
                <form action="reservas.php" method="get">
                    <div><input type="text" name="filtro" value="<?php echo $filtro; ?>" class="searchinput" title="nº de reserva o e-mail de usuario" /></div>
                </form>
            </div>
            <script type="text/javascript">
                $(document).ready(
                    function() {
                        textboxHint("busqueda");
                    });
            </script>
            <h3><span>Reservas</span></h3>
            <table>
                <tr class="filacabecera">
                    <td class="cabecera">Nº de reserva</td>
                    <td class="cabecera">Usuario</td>
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
    $reservaUsuario = ENUsuario::obtenerPorId($reserva->getIdUsuario());
    echo "<td><a href=\"usuario.php?id=".$reservaUsuario->getId()."\">".$reservaUsuario->getEmail()."</a></td>\n";
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
    echo "<tr><td colspan=\"9\"><br /><br /><br />No hay reservas con estos criterios de búsqueda<br /><br /><br /><br /></td>";
}
?>
            </table>
            <div id="leyenda">
                <div class="pendiente"></div>Pendiente
                <div class="encurso"></div>En curso
                <div class="finalizada"></div>Finalizada
            </div>
            <div><br />Sólo se muestran reservas desde 2 días atrás y en adelante</div>
        </div>
<?php
baseInferior();
?>
