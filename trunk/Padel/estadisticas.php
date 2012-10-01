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

function imprimirModulo($mes, $ano)
{
    $reservasMes = ENReserva::obtenerPorMesAno($mes, $ano);
    $no_reservables = 0;
    $cobrado = 0;
    foreach ($reservasMes as $i)
    {
        $cobrado += $i->getCobrado();
        if (!$i->getReservable())
            $no_reservables++;
    }
?>
            <table class="guapo-tabla" style="float: left; width: auto; margin: 20px;">
                <tr>
                    <th colspan="2"><?php echo mesToStr($mes)." $ano"; ?></th>
                </tr>
                <tr>
                    <td>Reservas</td><td class="guapo-centro"><?php echo count($reservasMes) - $no_reservables; ?></td>
                </tr>
                <tr>
                    <td>No reservables</td><td class="guapo-centro"><?php echo $no_reservables; ?></td>
                </tr>
                <tr>
                    <td>Cobrado</td><td class="guapo-centro"><?php echo $cobrado; ?>€</td>
                </tr>
                <!--<tr>
                    <td>Bonos de 4 clases 1 personas</td><td class="guapo-centro">0</td>
                </tr>
                <tr>
                    <td>Bonos de 4 clases 2 personas</td><td class="guapo-centro">0</td>
                </tr>
                <tr>
                    <td>Bonos de 4 clases 3 personas</td><td class="guapo-centro">0</td>
                </tr>
                <tr>
                    <td>Bonos de 4 clases 4 personas</td><td class="guapo-centro">0</td>
                </tr>-->
            </table>
<?php
}

baseSuperior("Estadísticas");
?>
        <div id="estadisticas">
            <h3><span>Estadísticas</span></h3>
            
            <?php 
            $mesActual = date('m');
            $anoActual = date('Y');
            for ($i = 4; $i >= 1; $i--)
            {
                imprimirModulo(($mesActual - $i < 1) ? ($mesActual - $i + 12) : ($mesActual - $i), ($mesActual - $i < 1) ? ($anoActual - 1) : $anoActual);
            }
            imprimirModulo($mesActual, $anoActual);
            ?>
            
            <table style="display: none;">
                <tr>
                    <td>
                        <div id="lineas">
                            <div><span class="descripcion">Reservas totales: </span><span class="dato"><?php echo ENReserva::contar(); ?></span></div>
                            <div><span class="descripcion">Reservas para hoy: </span><span class="dato"><?php echo ENReserva::contarHoy(); ?></span></div>
                            <div><span class="descripcion">Reservas durante los últimos 7 días: </span><span class="dato"><?php echo ENReserva::contarUltimos7(); ?></span></div>
                            <div><span class="descripcion">Reservas durante los próximos 7 días: </span><span class="dato"><?php echo ENReserva::contarProximos7(); ?></span></div>
                            <div><span class="descripcion">Tiempo total de reservas: </span><span class="dato"><?php echo ENReserva::totalMinutos(); ?> minutos</span></div>
                            <div><span class="descripcion">Tiempo medio por reserva: </span><span class="dato"><?php echo round(ENReserva::totalMinutos() / ENReserva::contar()); ?> minutos por reserva</span></div>
                            <div><span class="descripcion">Usuarios registrados: </span><span class="dato"><?php echo ENUsuario::contar(); ?></span></div>
                            <div><span class="descripcion">Usuarios registrados durante los últimos 7 días: </span><span class="dato"><?php echo ENUsuario::contarUltimos7(); ?></span></div>                            
                        </div>
                    </td>
                    <td>
                        
                    </td>
                </tr>
            </table>
        </div>
<?php
baseInferior();
?>
