<?php
include_once "base.php";

$usuario = getUsuario();
if ($usuario == null)
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.";
    header("location: iniciarsesion.php");
    exit();
}

$id = getGet("id");
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

$usuarioReseva = ENUsuario::obtenerPorId($reserva->getIdUsuario());

baseSuperior("Reserva nº ".rellenar($reserva->getId(), '0', $RELLENO));
?>
<div id="externo">
    <div id="interno">
        <div id="reserva">
            <h3><span>Reserva nº <?php echo rellenar($reserva->getId(), '0', $RELLENO); ?></span></h3>
            <form id="formulario" action="operarborrarreserva.php" method="post" enctype="multipart/form-data" onsubmit="return confirmarBorrarReserva();">
                <table class="guapo-form">
                    <tr>
                        <td class="guapo-label">Nº de reserva</td>
                        <td class="guapo-input">
                            <input type="text" value="<?php echo rellenar($reserva->getId(), '0', $RELLENO); ?>" readonly="readonly" />
                            <input type="hidden" value="<?php echo $reserva->getId(); ?>" name="id" />
                            <input type="hidden" value="reservar.php" name="retorno" />
                        </td>
                    </tr>
                    <tr>
                        <td class="guapo-label">Pista</td>
                        <td class="guapo-input"><input type="text" value="<?php echo $reserva->getIdPista(); ?>" readonly="readonly" /></td>
                    </tr>
                    <tr>
                        <td class="guapo-label">Día</td>
                        <td class="guapo-input"><input type="text" value="<?php echo $reserva->getFechaInicio()->format('d/m/Y'); ?>" name="" readonly="readonly" /></td>
                    </tr>
                    <tr>
                        <td class="guapo-label">Horario</td>
                        <td class="guapo-input"><input type="text" value="<?php echo $reserva->getFechaInicio()->format('H:i')." - ".$reserva->getFechaFin()->format('H:i'); ?>" name="" readonly="readonly" /></td>
                    </tr>
                    <tr>
                        <td class="guapo-label">Duración</td>
                        <td class="guapo-input"><input type="text" value="<?php echo $reserva->getDuracion(); ?> minutos" readonly="readonly" /></td>
                    </tr>
                    <tr>
                        <td class="guapo-label">Precio</td>
                        <td class="guapo-input"><input type="text" value="<?php echo ceil($reserva->getDuracion() * $PRECIOHORA / 60); ?>€ a pagar en ventanilla" readonly="readonly" /></td>
                    </tr>
                    <tr>
                        <td class="guapo-label">Cobrado</td>
                        <td class="guapo-input"><input type="text" value="<?php echo $reserva->getCobrado(); ?>€" readonly="readonly" /></td>
                    </tr>
                    <tr>
                        <td class="guapo-label">Estado</td>
                        <td class="guapo-input"><div class="textinputfake">
                            <?php
                                $clase = ($reserva->getEstado() ==  "Pendiente") ? "pendiente" : ($reserva->getEstado() == "Finalizada" ? "finalizada" : "encurso");
                                echo "<span class=\"$clase\">".$reserva->getEstado()."</span>\n";
                                if ($reserva->getEstado() == "Pendiente")
                                    echo "<span style=\"font-size: 0.8em\">(".$reserva->getCuentaAtrasString().")</span>";
                            ?>        
                        </div></td>
                    </tr>
                    <?php if ($usuario->getAdmin()) { ?>
                    <tr>
                        <td class="guapo-label">Usuario</td>
                        <td class="guapo-input"><div class="textinputfake">
                            <?php echo $usuarioReseva->getEmail(); ?> (<a href="usuario.php?id=<?php echo $usuarioReseva->getId(); ?>">Ver usuario</a>)
                        </div></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td class="guapo-label">Fecha en la que se realizó la reserva</td>
                        <td class="guapo-input"><input type="text" value="<?php echo $reserva->getFechaRealizacion()->format('d/m/Y H:i:s'); ?>" readonly="readonly" /></td>
                    </tr>
                    <?php if ($usuario->getAdmin()) { ?>
                    <tr>
                        <td class="guapo-label">Tipo de reserva</td>
                        <td class="columna2"><div class="textinputfake"><div class="leyenda <?php echo tipoCss($reserva->getTipo()); ?>"></div><?php echo tipoString($reserva->getTipo()); ?></div></td>
                    </tr>
                    <tr>
                        <td class="guapo-label">Notas</td>
                        <td class="guapo-input"><textarea readonly="readonly" rows="6" cols="30"><?php echo $reserva->getNotas(); ?></textarea></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td class="guapo-label"></td>
                        <td class="guapo-input"><div class="textinputfake">
                            <a class="freshbutton-blue" onclick="print();">Imprimir reserva</a>
                            <?php if ($reserva->getEstado() == "Pendiente" || $usuario->getAdmin()) { ?>
                            <a class="freshbutton-red" onclick="if (confirmarBorrarReserva()) document.getElementById('formulario').submit();">Borrar reserva</a>
                            <?php } else { ?>
                            <a class="freshbutton-disabled">Borrar reserva</a>
                            <?php } ?>
                        </div></td>
                    </tr> 
               </table>
            </form>
        </div>
    </div>
</div>
<?php
baseInferior();
?>
