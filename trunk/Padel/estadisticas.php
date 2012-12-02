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
    $normales = 0;
    $no_reservables = 0;
    $clases1p = 0;
    $clases2p = 0;
    $clases3p = 0;
    $cobrado = 0;
    foreach ($reservasMes as $i)
    {
        $cobrado += $i->getCobrado();
        switch ($i->getTipo()) {
            case 0: $normales++; break;
            case 1: $no_reservables++; break;
            case 2: $clases1p++; break;
            case 3: $clases2p++; break;
            case 4: $clases3p++; break;
        }
    }
?>
            <table class="guapo-tabla" style="float: left; width: auto; margin: 20px;">
                <tr>
                    <th colspan="2"><?php echo mesToStr($mes)." $ano"; ?></th>
                </tr>
                <tr>
                    <td>Reservas normales</td><td class="guapo-centro"><?php echo $normales; ?></td>
                </tr>
                <tr>
                    <td>No reservables</td><td class="guapo-centro"><?php echo $no_reservables; ?></td>
                </tr>
                <tr>
                    <td>Clases de 1 persona</td><td class="guapo-centro"><?php echo $clases1p; ?></td>
                </tr>
                <tr>
                    <td>Clases de 2 personas</td><td class="guapo-centro"><?php echo $clases2p; ?></td>
                </tr>
                <tr>
                    <td>Clases de 3 personas</td><td class="guapo-centro"><?php echo $clases3p; ?></td>
                </tr>
                <tr>
                    <td>Total cobrado</td><td class="guapo-centro"><?php echo $cobrado; ?>€</td>
                </tr>
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
        </div>
<?php
baseInferior();
?>
