<?php
include_once "base.php";

$usuario = getUsuario();
if ($usuario == null)
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder reservar pista.";
    header("location: iniciarsesion.php");
    exit();
}

// Obtenemos el día, el día máximo, y las variables para ir avanzando en el bucle.
$now = new DateTime();
$now->setTime(0, 0, 0);
$dia = new DateTime();
/*TOCHECK IF WANTED
if (intval($dia->format('H')) >= 22)
{
    $dia->add(new DateInterval("P1D"));
}*/
if (getGet("dia") != "")
{
    $dia = DateTime::createFromFormat('d/m/Y', getGet("dia"));
    if ($dia == false)
        $dia = new DateTime();
}
$dia->setTime(0, 0, 0);
$intervalo = $dia->diff($dia);
$intervalo->d = $usuario->getAdmin() ? $PERIODORESERVA_ADMIN : $PERIODORESERVA;
$maximodia = new DateTime();
$maximodia->add($intervalo);
$maximodia->setTime(0, 0, 0);
$siguientemaximodia = clone $maximodia;
$siguientemaximodia->add(new DateInterval("P1D"));
if ($dia > $maximodia)
    $dia = $maximodia;
if ($dia < $now && !$usuario->getAdmin())
    $dia = $now;

$siguiente = clone $dia;
$siguiente->add(new DateInterval("P1D"));

$tiempoInicial = clone $dia;
$tiempoFinal = clone $dia;
$tiempoInicial->setTime($ABIERTODESDE, $ABIERTODESDEM);
$tiempoFinal->setTime($ABIERTOHASTA, $ABIERTOHASTAM);

if ($tiempoInicial >= $tiempoFinal)
    $tiempoFinal->add(new DateInterval("P1D"));
$intervalo = $tiempoInicial->diff($tiempoInicial);
$intervalo->i = $INTERVALO;

// Obtenemos las reservas de las 6 pistas para el día elegido.
$reservas = array();
$reservas[0] = ENReserva::obtenerPorPistaDia(1, $dia);
$reservas[1] = ENReserva::obtenerPorPistaDia(2, $dia);
$reservas[2] = ENReserva::obtenerPorPistaDia(3, $dia);
$reservas[3] = ENReserva::obtenerPorPistaDia(4, $dia);
$reservas[4] = ENReserva::obtenerPorPistaDia(5, $dia);
$reservas[5] = ENReserva::obtenerPorPistaDia(6, $dia);

if ($tiempoFinal->format('d') != $dia->format('d'))
{    
    $reservas[0] = array_merge($reservas[0], ENReserva::obtenerPorPistaDia(1, $siguiente));
    $reservas[1] = array_merge($reservas[1], ENReserva::obtenerPorPistaDia(2, $siguiente));
    $reservas[2] = array_merge($reservas[2], ENReserva::obtenerPorPistaDia(3, $siguiente));
    $reservas[3] = array_merge($reservas[3], ENReserva::obtenerPorPistaDia(4, $siguiente));
    $reservas[4] = array_merge($reservas[4], ENReserva::obtenerPorPistaDia(5, $siguiente));
    $reservas[5] = array_merge($reservas[5], ENReserva::obtenerPorPistaDia(6, $siguiente));
}

/**
 *
 * @param type $reservas
 * @param type $tiempo 
 * @return null si libre, objeto si hay una reserva que comienza justo en ese $tiempo, -1 si hay una reserva que forma parte de otra
 */
function determinarEstado($reservas, $tiempo)
{
    $estado = null;
    foreach ($reservas as $reserva)
    {
        if ($reserva->getFechaInicio() <= $tiempo && $tiempo < $reserva->getFechaFin())
        {
            if ($reserva->getFechaInicio() == $tiempo)
            {
                return $reserva;
            }
                
            return -1;
        }
    }
    return $estado;
}

baseSuperior("Reservar pista");

?>
                    <div id="reservar">
                        <h3><span>Reservar pista</span></h3>
                        <div id="resumenreserva">
                            <div id="datepicker"></div>
                            <form id="formulario" action="operarreserva.php" method="post" enctype="multipart/form-data" onsubmit="return validarReserva(this);">
                                <div><label>Día </label>
                                    <input type="text" value="<?php echo $dia->format('d/m/Y'); ?>" name="dia" readonly="readonly" style="width: 100px;" />
                                    <input type="hidden" value="<?php echo $dia->format('d/m/Y'); ?>" name="diaoculto" />
                                    <input type="hidden" value="0" name="scroll" />
                                </div>
                                <div><label>Pista </label><input type="text" value="" name="pista" readonly="readonly" style="width: 30px;" /></div>
                                <div><label>Desde las </label><input type="text" value="" name="desde" readonly="readonly" style="width: 50px;" /><label> hasta las </label><input type="text" value="" name="hasta" readonly="readonly" style="width: 50px;" /></div>
                                <div><label>Duración </label><input type="text" value="" name="duracion" readonly="readonly" style="width: 30px;" /><label> minutos</label></div>
                                <div><label>Precio </label><input type="text" value="" name="precio" readonly="readonly" style="width: 30px;" /><label> euros</label></div>
<?php if ($usuario->getAdmin()) { ?>
                                <div>
                                    <select name="tipo">
                                        <option value="0"><?php echo tipoString(0); ?></option>
                                        <option value="1"><?php echo tipoString(1); ?></option>
                                        <option value="2"><?php echo tipoString(2); ?></option>
                                        <option value="3"><?php echo tipoString(3); ?></option>
                                        <option value="4"><?php echo tipoString(4); ?></option>
                                    </select>
                                </div>
                                <div>Realizar la misma reserva también los próximos
                                
                                <select name="proximos">
                                    <?php
                                    for ($i = 0; $i < 31; $i++)
                                    {
                                        echo "<option value=\"$i\">$i</option>";
                                    }
                                    ?>
                                </select> 
                                
                                días</div>

                                <div>
                                    <label>Notas</label>
                                    <textarea name="notas" rows="5" cols="25"></textarea>
                                </div>
                                <div>
                                    <label>Usuario alternativo</label><br />
                                    <input type="text" readonly="readonly" value="" id="email" name="email" /> <a style="cursor: pointer;" onclick="$('#dialogo-seleccionar').dialog('open');"><img src="css/users.png" alt="Seleccionar usuario" title="Seleccionar usuario" /></a>
                                </div>

<?php } ?>
                                <div><input type="submit" value="Confirmar reserva" name="" class="freshbutton-big" /></div>
                            </form>
                        </div>
                        <div id="tablapistas">
                            <script type="text/javascript">
                                
                            function aux()
                            {
                                comprobanteDia(<?php echo "'".$dia->format('d/m/Y')."'"; ?>, <?php echo "'".ENReserva::comprobanteDia($dia)."'"; ?>);
                            }
                                
                            var isDown = false;
                                
                            $(document).ready(function(){
                                $(document).mousedown(function() { isDown = true; })
                                .mouseup(function() { isDown = false; });
                                setInterval(aux, 15000);
                                
                                <?php
                                
                                $scroll = getGet("scroll");
                                if ($scroll != "")
                                {
                                    echo "var mensajesHeight = 0;\n";
                                    //echo "if ($(\"#mensajes\").length > 0) {\n";
                                    //echo "    mensajesHeight = $(\"#mensajes\").height() + 10;\n";
                                    //echo "}\n";
                                    echo "$('html, body').animate({scrollTop: $scroll + mensajesHeight}, 1);\n";
                                }
                                
                                ?>
                            });
                                
                            var filasSeleccionadas = new Array();
                            var celdasSeleccionadas = new Array();
                            var pistaSeleccionada = 0;
                            var formulario = document.getElementById("formulario");
                            
                            function restaurarSeleccionadas()
                            {
                                for (var i in celdasSeleccionadas)
                                    celdasSeleccionadas[i].setAttribute("class", "libre");
                                celdasSeleccionadas = new Array();
                                filasSeleccionadas = new Array();
                                
                                pistaSeleccionada = 0;
                                
                                formulario.elements["pista"].value = "";
                                formulario.elements["desde"].value = "";
                                formulario.elements["hasta"].value = "";
                                formulario.elements["duracion"].value = "";
                                formulario.elements["precio"].value = "";
                            }
                            
                            function contains(a, obj)
                            {
                                for (var i = 0; i < a.length; i++)
                                {
                                    if (a[i] === obj)
                                    {
                                        return true;
                                    }
                                }
                                return false;
                            }

                            
                            function seleccionar(celda, pista, fila)
                            {
                                // Se comprueba si la nueva celda está adyacente a otra celda anterior.
                                var adjacente = false;
                                for (var i in filasSeleccionadas)
                                {
                                    if (filasSeleccionadas[i] == fila - 1 || filasSeleccionadas[i] == fila || filasSeleccionadas[i] == fila + 1)
                                    {
                                        adjacente = true;
                                        break;
                                    }
                                }
                                
                                // Si la nueva celda está en otra pista o no es adjacente, se restaura.
                                if (!adjacente || (pistaSeleccionada != 0 && pistaSeleccionada != pista) || (!contains(celdasSeleccionadas, celda) && (<?php echo $INTERVALO; ?>) * celdasSeleccionadas.length >= <?php echo $usuario->getAdmin() ? $MAXDURACION_ADMIN : $MAXDURACION; ?>))
                                    restaurarSeleccionadas();
                                pistaSeleccionada = pista;
                                formulario.elements["pista"].value = pistaSeleccionada;
                                
                                // Si la celda no estaba en la lista ya, se introduce en las listas y se actualiza el formulario.
                                if (!contains(celdasSeleccionadas, celda))
                                {
                                    celda.setAttribute("class", "seleccionado");
                                    celdasSeleccionadas.push(celda);
                                    filasSeleccionadas.push(fila);
                                    
                                    var minFila = 9999;
                                    var maxFila = 0;
                                    for (var i in filasSeleccionadas)
                                    {
                                        if (filasSeleccionadas[i] < minFila)
                                            minFila = filasSeleccionadas[i];
                                        if (filasSeleccionadas[i] > maxFila)
                                            maxFila = filasSeleccionadas[i];
                                    }
                                    
                                    var fecha = new Date(2012, 1, 1, <?php echo $ABIERTODESDE; ?>, <?php echo $ABIERTODESDEM; ?>, 0);
                                    fecha.setMinutes(fecha.getMinutes() + minFila * <?php echo $INTERVALO; ?>);
                                    formulario.elements["desde"].value = (fecha.getHours() < 10 ? "0" : "") + fecha.getHours() + ":" + (fecha.getMinutes() < 10 ? "0" : "") + fecha.getMinutes();
                                                                        
                                    if (fecha.getHours() >= 0 && fecha.getHours() < <?php echo $ABIERTODESDE; ?>)
                                    {
                                        formulario.elements["dia"].value = '<?php echo $siguiente->format('d/m/Y'); ?>';
                                    }
                                    else
                                    {
                                        formulario.elements["dia"].value = '<?php echo $dia->format('d/m/Y'); ?>';
                                    }
                                    
                                    fecha = new Date(2012, 1, 1, <?php echo $ABIERTODESDE; ?>, <?php echo $ABIERTODESDEM; ?>, 0);
                                    fecha.setMinutes(fecha.getMinutes() + maxFila * <?php echo $INTERVALO; ?> + <?php echo $INTERVALO; ?>);
                                    formulario.elements["hasta"].value = (fecha.getHours() < 10 ? "0" : "") + fecha.getHours() + ":" + (fecha.getMinutes() < 10 ? "0" : "") + fecha.getMinutes();
                                    
                                    formulario.elements["duracion"].value = <?php echo $INTERVALO; ?> * celdasSeleccionadas.length;
                                    formulario.elements["precio"].value = Math.ceil(<?php echo $INTERVALO; ?> * celdasSeleccionadas.length * (<?php echo $PRECIOHORA; ?>) / 60);
                                }
                            }
                            </script>
                            <table>
                                <tr class="filacabecera">
                                    <td class="hora esquina"></td>
                                    <td class="cabecera columnapista"><?php echo pistaString(1); ?></td>
                                    <td class="cabecera columnapista"><?php echo pistaString(2); ?></td>
                                    <td class="cabecera columnapista"><?php echo pistaString(3); ?></td>
                                    <td class="cabecera columnapista"><?php echo pistaString(4); ?></td>
                                    <td class="cabecera columnapista"><?php echo pistaString(5); ?></td>
                                    <td class="cabecera columnapista"><?php echo pistaString(6); ?></td>
                                </tr>
<?php
$fila = 0;
$diferencia = $tiempoFinal->diff($tiempoInicial, true);
$maxfilas = ($diferencia->i + $diferencia->h * 60 + $diferencia->d * 60 * 24) / $INTERVALO;
while ($tiempoInicial < $tiempoFinal)
{
    $tiempo = clone $tiempoInicial;
    echo "<tr>\n";
    echo "<td class=\"hora\">\n";
    echo $tiempo->format("H:i")." - "; $tiempo->add($intervalo); echo $tiempo->format("H:i");
    echo "</td>\n";
    for ($i = 0; $i < 6; $i++)
    {
        $estado = determinarEstado($reservas[$i], $tiempoInicial);
        if (is_object($estado))
        {
            $clase = tipoCss($estado->getTipo());
            $celdas = $estado->getDuracion() / $INTERVALO;
            if ($fila + $celdas >= $maxfilas)
                $celdas = $maxfilas - $fila;
            echo "<td class=\"$clase\" rowspan=\"$celdas\">";
            if ($usuario->getId() == $estado->getIdUsuario() || $usuario->getAdmin())
            {
                echo "<form id=\"borrar".$estado->getId()."\" action=\"operarborrarreserva.php\" method=\"post\">";
                echo "<input type=\"hidden\" name=\"retorno\" value=\"reservar.php?dia=".$dia->format('d/m/Y')."\" />";
                echo "<input type=\"hidden\" name=\"id\" value=\"".$estado->getId()."\" />";
                echo "<input type=\"hidden\" name=\"scroll\" value=\"".$estado->getId()."\" />";
                echo "</form>";
                echo "<a class=\"smallicon\" onclick=\"$('#dialog".$estado->getId()."').dialog('open');\"><img src=\"css/lupa.png\" alt=\"Ver reserva\" title=\"Ver reserva\" /></a>&nbsp;";
                echo "<a class=\"smallicon\" onclick=\"if (confirmarBorrarReserva()) { document.getElementById('borrar".$estado->getId()."').scroll.value = $(document).scrollTop(); document.getElementById('borrar".$estado->getId()."').submit(); }\"><img src=\"css/papelera.png\" alt=\"Borrar reserva\" title=\"Borrar reserva\" /></a>";
                
                echo "<div style=\"display: none;\" class=\"dialogoreserva\" id=\"dialog".$estado->getId()."\" title=\"Reserva nº ".$estado->getId()."\">";
                $cuerpo = "<tr>";
                $cuerpo = $cuerpo."<td class=\"guapo-label\">Nº de reserva</td>";
                $cuerpo = $cuerpo."<td class=\"guapo-input\"><input type=\"text\" readonly=\"readonly\" value=\"".rellenar($estado->getId(), '0', $RELLENO)."\" /></td>";
                $cuerpo = $cuerpo."</tr>";
                $cuerpo = $cuerpo."<tr>";
                $cuerpo = $cuerpo."<td class=\"guapo-label\">Pista</td>";
                $cuerpo = $cuerpo."<td class=\"guapo-input\"><input type=\"text\" readonly=\"readonly\" value=\"".$estado->getIdPista()."\" /></td>";
                $cuerpo = $cuerpo."</tr>";
                $cuerpo = $cuerpo."<tr>";
                $cuerpo = $cuerpo."<td class=\"guapo-label\">Día</td>";
                $cuerpo = $cuerpo."<td class=\"guapo-input\"><input type=\"text\" readonly=\"readonly\" value=\"".$estado->getFechaInicio()->format('d/m/Y')."\" /></td>";
                $cuerpo = $cuerpo."</tr>";
                $cuerpo = $cuerpo."<tr>";
                $cuerpo = $cuerpo."<td class=\"guapo-label\">Horario</td>";
                $cuerpo = $cuerpo."<td class=\"guapo-input\"><input type=\"text\" readonly=\"readonly\" value=\"".$estado->getFechaInicio()->format('H:i')." - ".$estado->getFechaFin()->format('H:i')."\" /></td>";
                $cuerpo = $cuerpo."</tr>";
                $cuerpo = $cuerpo."<tr>";
                $cuerpo = $cuerpo."<td class=\"guapo-label\">Duración</td>";
                $cuerpo = $cuerpo."<td class=\"guapo-input\"><input type=\"text\" readonly=\"readonly\" value=\"".$estado->getDuracion()." minutos\" /></td>";
                $cuerpo = $cuerpo."</tr>";
                $cuerpo = $cuerpo."<tr>";
                $cuerpo = $cuerpo."<td class=\"guapo-label\">Estado</td>";
                $cuerpo = $cuerpo."<td class=\"guapo-input\">";
                $clase = ($estado->getEstado() ==  "Pendiente") ? "pendiente2" : ($estado->getEstado() == "Finalizada" ? "finalizada2" : "encurso2");
                $cuerpo = $cuerpo."<span class=\"$clase\">".$estado->getEstado()."</span>\n";
                if ($estado->getEstado() == "Pendiente")
                $cuerpo = $cuerpo."<br /><span style=\"font-size: 0.8em\">(".$estado->getCuentaAtrasString().")</span>";
                $cuerpo = $cuerpo."</td>";
                $cuerpo = $cuerpo."</tr>";                
                $cuerpo = $cuerpo."<tr>";
                $cuerpo = $cuerpo."<td class=\"guapo-label\">Precio</td>";
                $cuerpo = $cuerpo."<td class=\"guapo-input\"><input type=\"text\" readonly=\"readonly\" value=\"".ceil($estado->getDuracion() * $PRECIOHORA / 60)."€ a pagar en ventanilla\" /></td>";
                $cuerpo = $cuerpo."</tr>";
                if ($usuario->getAdmin())
                {
                    $cuerpo = $cuerpo."<tr>";
                    $cuerpo = $cuerpo."<td class=\"guapo-label\">Cobrado</td>";
                    $cuerpo = $cuerpo."<td class=\"guapo-input\"><input id=\"cobrado".$estado->getId()."\" type=\"text\" readonly=\"readonly\" value=\"".$estado->getCobrado()."€\" /> <a href=\"#\" onclick=\"realizarCobro('".$estado->getId()."', prompt('Cantidad cobrada:', ''));\"><img src=\"css/dinero.png\" alt=\"Realizar cobro\" title=\"Realizar cobro\" /></a></td>";
                    $cuerpo = $cuerpo."</tr>";
                    $cuerpo = $cuerpo."<tr>";
                    $cuerpo = $cuerpo."<td class=\"guapo-label\">Notas</td>";
                    $cuerpo = $cuerpo."<td class=\"guapo-input\"><textarea readonly=\"readonly\" cols=\"20\" rows=\"2\">".nl2br($estado->getNotas())."</textarea></td>";
                    $cuerpo = $cuerpo."</tr>";
                    $cuerpo = $cuerpo."<tr>";
                    $cuerpo = $cuerpo."<td class=\"guapo-label\">Usuario de la reserva</td>";
                    $usuarioReseva = ENUsuario::obtenerPorId($estado->getIdUsuario());
                    $cuerpo = $cuerpo."<td class=\"guapo-input\"><input type=\"text\" readonly=\"readonly\" value=\"".$usuarioReseva->getEmail()."\" /> <a href=\"usuario.php?id=".$usuarioReseva->getId()."\"><img src=\"css/ficha_usuario.png\" alt=\"Ver usuario\" title=\"Ver usuario\" /></a></td>";
                    $cuerpo = $cuerpo."</tr>";
                }
                $cuerpo = $cuerpo."<tr>";
                $cuerpo = $cuerpo."<td class=\"guapo-label\">Fecha de reserva</td>";
                $cuerpo = $cuerpo."<td class=\"guapo-input\"><input type=\"text\" readonly=\"readonly\" value=\"".$estado->getFechaRealizacion()->format('d/m/Y H:i:s')."\" /></td>";
                $cuerpo = $cuerpo."</tr>";
                $cuerpo = "<table class=\"guapo-form\">".$cuerpo."</table>";
                echo $cuerpo;
                echo "</div>";
            }
            echo "</td>\n";
        }
        else if ($estado == null)
        {
            if ((new DateTime() < $tiempoInicial && $tiempoInicial <= $siguientemaximodia && $tiempo <= $siguientemaximodia) || $usuario->getAdmin())
                echo "<td class=\"libre\" onmousedown=\"seleccionar(this, ".($i + 1).", $fila);\" onmouseover=\"if (isDown) seleccionar(this, ".($i + 1).", $fila);\"></td>\n";
            else
                echo "<td class=\"noreservable\"></td>\n";
        }
    }
    echo "</tr>\n";
    $tiempoInicial = $tiempo;
    $fila++;
}
?>
                            </table>

                            <div id="tiempo">
                                <div id="TT_vitgrhthdcYBDIsU7AuDDDjDzKlULUa1kxEzzhCKglI"></div>
                                <script type="text/javascript" src="http://www.tutiempo.net/widget/eltiempo_vitgrhthdcYBDIsU7AuDDDjDzKlULUa1kxEzzhCKglI"></script>
                            </div>
                            
                            <div id="leyendareservar">
                                <div><div class="leyenda libre"></div>Libre</div>
                                <div><div class="leyenda ocupado"></div>Ocupado</div>
                                <div><div class="leyenda clase"></div>Ocupado por profesor</div>
                                <div><div class="leyenda noreservable"></div>No reservable</div>
                            </div>
                            
<?php
if ($usuario->getAdmin())
{
?>
                            <div style="display: none;" id="dialogo-seleccionar" title="Seleccionar usuario">
                                <div>
                                    <form id="busqueda-form" method="get" onsubmit="buscarUsuarios(this); return false;">
                                        Escribe a continuación el e-mail, nombre, DNI o teléfono del usuario y selecciona un usuario para el que quieras realizar la reserva.<br/><br/>
                                        <div><input id="filtro" type="text" autocomplete="off" name="filtro" value="" onkeyup="buscarUsuarios(null);" class="searchinput" title="nº de usuario, nombre, e-mail, DNI o teléfono" /></div>
                                    </form>
                                </div>
                                <br />
                                <div id="dialogo-seleccionar-contenedor" style="overflow: auto; width: 800px; height: 300px;"></div>
                            </div>

                            <script type="text/javascript">
                            $(window).load(function() {

                                $("#dialogo-seleccionar").dialog({
                                    resizable: true,
                                    autoOpen: false,
                                    width: 'auto',
                                    height: 'auto',
                                    modal: true,
                                    buttons: {
                                        "Cerrar": function() {
                                            $( this ).dialog( "close" );
                                        }
                                    }
                                });
                            });
                            </script>
<?php
}
?>
                        </div>
                    </div>
                    <script type="text/javascript">
                        
                    $.fx.speeds._default = 300;
                    
                    $(".dialogoreserva").dialog({
                        resizable: false,
                        autoOpen: false,
                        width: "auto",
                        height: "auto",
                        modal: true,
                        buttons: {
                            "Cerrar": function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    });
                        
                    jQuery(function($){
                            $.datepicker.regional['es'] = {
                                    closeText: 'Cerrar',
                                    prevText: '&#x3c;Ant',
                                    nextText: 'Sig&#x3e;',
                                    currentText: 'Hoy',
                                    monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                                    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                                    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
                                    'Jul','Ago','Sep','Oct','Nov','Dic'],
                                    dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
                                    dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
                                    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
                                    weekHeader: 'Sm',
                                    dateFormat: 'dd/mm/yy',
                                    firstDay: 1,
                                    isRTL: false,
                                    showMonthAfterYear: false,
                                    yearSuffix: ''};
                            $.datepicker.setDefaults($.datepicker.regional['es']);
                    });
                    
                    $(function() {
                        $( "#datepicker" ).datepicker({
                            minDate: <?php echo !$usuario->getAdmin() ? "0" : "-$PERIODOPASADO_ADMIN"; ?>, maxDate: '<?php echo $maximodia->format('d/m/Y'); ?>',
                            defaultDate: '<?php echo $dia->format('d/m/Y'); ?>',
                            showOtherMonths: true,
                            selectOtherMonths: true,
                            onSelect: function(dateText, inst) { window.location = "reservar.php?dia=" + dateText; }
                        });
                        $('.selector').datepicker({
                        });
                    });
                    </script>
<?php
baseInferior();
?>
