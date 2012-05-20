<?php
include_once "base.php";

$usuario = getUsuario();
if ($usuario == null)
{
    header("location: iniciarsesion.php?aviso=Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.");
    exit();
}

$id = getGet("id");
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

$usuarioReseva = ENUsuario::obtenerPorId($reserva->getIdUsuario());

baseSuperior("Reserva nº ".rellenar($reserva->getId(), '0', $RELLENO));
?>
<div id="externo">
    <div id="interno">
        <div id="reserva">
            <h3><span>Reserva nº <?php echo rellenar($reserva->getId(), '0', $RELLENO); ?></span></h3>
            <form id="formulario" action="operarborrarreserva.php" method="post" enctype="multipart/form-data" onsubmit="return confirmarBorrarReserva();">
                <table>
                    <tr>
                        <td class="columna1">Nº de reserva:</td>
                        <td class="columna2">
                            <input type="text" value="<?php echo rellenar($reserva->getId(), '0', $RELLENO); ?>" readonly="readonly" class="textinput" />
                            <input type="hidden" value="<?php echo $reserva->getId(); ?>" name="id" />
                        </td>
                    </tr>
                    <tr>
                        <td class="columna1">Pista:</td>
                        <td class="columna2"><input type="text" value="<?php echo $reserva->getIdPista(); ?>" readonly="readonly" class="textinput" /></td>
                    </tr>
                    <tr>
                        <td class="columna1">Horario:</td>
                        <td class="columna2"><input type="text" value="<?php echo $reserva->getFechaInicio()->format('H:i')." - ".$reserva->getFechaFin()->format('H:i'); ?>" name="" readonly="readonly" class="textinput" /></td>
                    </tr>
                    <tr>
                        <td class="columna1">Duración (minutos):</td>
                        <td class="columna2"><input type="text" value="<?php echo $reserva->getDuracion(); ?>" readonly="readonly" class="textinput" /></td>
                    </tr>
                    <tr>
                        <td class="columna1">Estado:</td>
                        <td class="columna2"><div class="textinputfake">
                            <?php
                                $clase = ($reserva->getEstado() ==  "Pendiente") ? "pendiente" : ($reserva->getEstado() == "Antigua" ? "antigua" : "encurso");
                                echo "<div class=\"$clase\">".$reserva->getEstado()."</div>";
                                if ($reserva->getEstado() == "Pendiente")
                                    echo $reserva->getCuentaAtrasString();
                            ?>        
                        </div></td>
                    </tr>
                    <tr>
                        <td class="columna1">Usuario:</td>
                        <td class="columna2"><div class="textinputfake">
                            <?php echo $usuarioReseva->getEmail(); ?> (<a href="datosusuario.php?id=<?php echo $usuarioReseva->getId(); ?>">Ver usuario</a>)
                        </div></td>
                    </tr>
                    <tr>
                        <td class="columna1">Fecha en la que<br />se realizó la reserva:&nbsp;&nbsp;</td>
                        <td class="columna2"><input type="text" value="<?php echo $reserva->getFechaRealizacion()->format('d/m/Y H:i:s'); ?>" readonly="readonly" class="textinput" /></td>
                    </tr>
                    <?php if ($usuario->getAdmin()) { ?>
                    <tr>
                        <td class="columna1">Tipo de reserva:</td>
                        <td class="columna2"><div class="textinputfake">
                            <input type="radio" name="tipo" value="" <?php echo ($reserva->getReservable() != 0) ? "checked=\"checked\"" : ""; ?> readonly="readonly" disabled='disabled' /> Reservada
                            <input type="radio" name="tipo" value="" <?php echo ($reserva->getReservable() == 0) ? "checked=\"checked\"" : ""; ?> readonly="readonly" disabled='disabled' /> Bloqueada
                        </div></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td class="columna1"></td>
                        <td class="columna2"><div class="textinputfake">
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
