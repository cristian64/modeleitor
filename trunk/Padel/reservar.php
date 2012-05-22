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
if (intval($dia->format('H')) >= 22)
{
    $dia->add(new DateInterval("P1D"));
}
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
if ($dia > $maximodia)
    $dia = $maximodia;
if ($dia < $now && !$usuario->getAdmin())
    $dia = $now;

$siguiente = clone $dia;
$siguiente->add(new DateInterval("P1D"));

$tiempoInicial = clone $dia;
$tiempoFinal = clone $dia;
$tiempoInicial->setTime($ABIERTODESDE, 0);
$tiempoFinal->setTime($ABIERTOHASTA, 0);

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
 * @return 0 si libre, 1 si ocupada, 2 si no reservable, un objeto ENReserva si coincide con la primera celda, para poder usar rowspan
 */
function determinarEstado($reservas, $tiempo)
{
    $estado = 0;
    foreach ($reservas as $reserva)
    {
        if ($reserva->getFechaInicio() <= $tiempo && $tiempo < $reserva->getFechaFin())
        {
            if ($reserva->getFechaInicio() == $tiempo)
            {
                return $reserva;
            }
                
            $estado = $reserva->getReservable() ? 1 : 2;
            break;
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
                                </div>
                                <div><label>Pista </label><input type="text" value="" name="pista" readonly="readonly" style="width: 30px;" /></div>
                                <div><label>Desde las </label><input type="text" value="" name="desde" readonly="readonly" style="width: 50px;" /><label> hasta las </label><input type="text" value="" name="hasta" readonly="readonly" style="width: 50px;" /></div>
                                <div><label>Duración </label><input type="text" value="" name="duracion" readonly="readonly" style="width: 30px;" /><label> minutos</label></div>
                                <div><label>Precio </label><input type="text" value="" name="precio" readonly="readonly" style="width: 30px;" /><label> euros</label></div>
<?php if ($usuario->getAdmin()) { ?>
                                <div>
                                    <input type="radio" name="reservable" value="1" checked="checked"/> Reservar
                                    <input type="radio" name="reservable" value="0" /> Bloquear
                                </div>
<?php } ?>
                                <div><input type="submit" value="Confirmar reserva" name="" class="freshbutton-big" /></div>
                            </form>
                            <div id="tiempo">
                                <div id="TT_tyswbxdhtIEjjFaA7fujzzDDD9lA1A2lLY1tkciIq1j"></div>
                            </div>
                        </div>
                        <div id="tablapistas">
                            <script type="text/javascript">
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
                                    
                                    var fecha = new Date(2012, 1, 1, <?php echo $ABIERTODESDE; ?>, 0, 0);
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
                                    
                                    fecha = new Date(2012, 1, 1, <?php echo $ABIERTODESDE; ?>, 0, 0);
                                    fecha.setMinutes(fecha.getMinutes() + maxFila * <?php echo $INTERVALO; ?> + <?php echo $INTERVALO; ?>);
                                    formulario.elements["hasta"].value = (fecha.getHours() < 10 ? "0" : "") + fecha.getHours() + ":" + (fecha.getMinutes() < 10 ? "0" : "") + fecha.getMinutes();
                                    
                                    formulario.elements["duracion"].value = <?php echo $INTERVALO; ?> * celdasSeleccionadas.length;
                                    formulario.elements["precio"].value = <?php echo $INTERVALO; ?> * celdasSeleccionadas.length * (<?php echo $PRECIOHORA; ?>) / 60;
                                }
                            }
                            </script>
                            <table>
                                <tr class="filacabecera">
                                    <td class="hora esquina"></td>
                                    <td class="cabecera">Pista 1</td>
                                    <td class="cabecera">Pista 2</td>
                                    <td class="cabecera">Pista 3</td>
                                    <td class="cabecera">Pista 4</td>
                                    <td class="cabecera">Pista 5</td>
                                    <td class="cabecera">Pista 6</td>
                                </tr>
<?php
$fila = 0;
while ($tiempoInicial < $tiempoFinal)
{
    $tiempo = clone $tiempoInicial;
    echo "<tr>\n";
    echo "<td class=\"hora\" nowrap=\"nowrap\">\n";
    echo $tiempo->format("H:i")." - "; $tiempo->add($intervalo); echo $tiempo->format("H:i");
    echo "</td>\n";
    for ($i = 0; $i < 6; $i++)
    {
        $estado = determinarEstado($reservas[$i], $tiempoInicial);
        if (is_object($estado))
        {
            $clase = $estado->getReservable() ? "ocupado" : "noreservable";
            $celdas = $estado->getDuracion() / $INTERVALO;
            echo "<td class=\"$clase\" rowspan=\"$celdas\">";
            if ($usuario->getAdmin())
                echo "<a href=\"reserva.php?id=".$estado->getId()."\"><img src=\"css/lupa.png\" alt=\"Ver reserva\" title=\"Ver reserva\" /></a>";
            echo "</td>\n";
        }
        else if ($estado == 0)
        {
            if ($tiempoInicial > new DateTime() || $usuario->getAdmin())
                echo "<td class=\"libre\" onclick=\"seleccionar(this, ".($i + 1).", $fila);\">".$tiempoInicial->format('d/m/Y H:i')."</td>\n";
            else
                echo "<td class=\"noreservable\">".$tiempoInicial->format('d/m/Y H:i')."</td>\n";
        }
    }
    echo "</tr>\n";
    $tiempoInicial = $tiempo;
    $fila++;
}
?>
                            </table>
                            <div id="leyenda">
                                <div class="libre"></div>Libre
                                <div class="ocupado"></div>Ocupado
                                <div class="noreservable"></div>No reservable
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
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
                            onSelect: function(dateText, inst) { window.location = "reservar.php?dia=" + dateText; }
                        });
                        $('.selector').datepicker({
                        });
                    });
                    </script>
                    <script type="text/javascript" src="http://www.tutiempo.net/widget/eltiempo_tyswbxdhtIEjjFaA7fujzzDDD9lA1A2lLY1tkciIq1j"></script>
<?php
baseInferior();
?>
