<?php
include_once "base.php";

if (!isset($_SESSION["usuario"]))
{
    header("location: iniciarsesion.php?aviso=Tu sesión ha caducado. Debes iniciar sesión antes de poder reservar pista.");
    exit();
}

baseSuperior("Reservar pista");

// Obtenemos el día, el día máximo, y las variables para ir avanzando en el bucle.
$now = new DateTime();
$now->setTime(0, 0, 0);
$dia = new DateTime();
if (isset($_GET["dia"]))
{
    $dia = DateTime::createFromFormat('d/m/Y', $_GET["dia"]);
    if ($dia == false)
        $dia = new DateTime();
}
$dia->setTime(0, 0, 0);
$intervalo = $dia->diff($dia);
$intervalo->d = $PERIODORESERVA;
$maximodia = new DateTime();
$maximodia->add($intervalo);
$maximodia->setTime(0, 0, 0);
if ($dia > $maximodia)
    $dia = $maximodia;
if ($dia < $now)
    $dia = $now;
$tiempoInicial = clone $dia;
$tiempoFinal = clone $dia;
$tiempoInicial->setTime(8, 0);
$tiempoFinal->setTime(23, 59, 59);
$intervalo = $tiempoInicial->diff($tiempoInicial);
$intervalo->i = 30;

// Obtenemos las reservas de las 6 pistas para el día elegido.
$reservas = array();
$reservas[0] = ENReserva::obtenerPorPistaDia(1, $dia);
$reservas[1] = ENReserva::obtenerPorPistaDia(2, $dia);
$reservas[2] = ENReserva::obtenerPorPistaDia(3, $dia);
$reservas[3] = ENReserva::obtenerPorPistaDia(4, $dia);
$reservas[4] = ENReserva::obtenerPorPistaDia(5, $dia);
$reservas[5] = ENReserva::obtenerPorPistaDia(6, $dia);

/**
 *
 * @param type $reservas
 * @param type $tiempo 
 * @return 0 si libre, 1 si ocupada, 2 si no reservable
 */
function determinarEstado($reservas, $tiempo)
{
    $estado = 0;
    foreach ($reservas as $reserva)
    {
        if ($reserva->getFechaInicio() <= $tiempo && $tiempo < $reserva->getFechaFin())
        {
            $estado = $reserva->getReservable() ? 1 : 2;
            break;
        }
    }
    return $estado;
}

?>
                    <div id="reservar">
                        <h3><span>Reservar pista</span></h3>
                        <div id="resumenreserva">
                            <div id="datepicker"></div>
                            <form id="formulario" action="operarreserva.php" method="post" enctype="multipart/form-data" onsubmit="return validarReserva(this);">
                                <div><label>Día: </label><input type="text" value="<?php echo $dia->format('d/m/Y'); ?>" name="dia" readonly="readonly" style="width: 100px;" /></div>
                                <div><label>Pista: </label><input type="text" value="" name="pista" readonly="readonly" style="width: 30px;" /></div>
                                <div><label>Desde las </label><input type="text" value="" name="desde" readonly="readonly" style="width: 50px;" /><label> hasta las </label><input type="text" value="" name="hasta" readonly="readonly" style="width: 50px;" /></div>
                                <div><label>Duración: </label><input type="text" value="" name="duracion" readonly="readonly" style="width: 30px;" /><label> minutos</label></div>
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
                                if (!adjacente || (pistaSeleccionada != 0 && pistaSeleccionada != pista) || (!contains(celdasSeleccionadas, celda) && 30 * celdasSeleccionadas.length >= <?php echo $MAXDURACION; ?>))
                                    restaurarSeleccionadas();
                                pistaSeleccionada = pista;
                                formulario.elements["pista"].value = pistaSeleccionada;
                                
                                // Si la celda no estaba en la lista ya, se introduce en las listas y se actualiza el formulario.
                                if (!contains(celdasSeleccionadas, celda))
                                {
                                    celda.setAttribute("class", "seleccionado");
                                    celdasSeleccionadas.push(celda);
                                    filasSeleccionadas.push(fila);
                                    
                                    var minFila = 31;
                                    var maxFila = 0;
                                    for (var i in filasSeleccionadas)
                                    {
                                        if (filasSeleccionadas[i] < minFila)
                                            minFila = filasSeleccionadas[i];
                                        if (filasSeleccionadas[i] > maxFila)
                                            maxFila = filasSeleccionadas[i];
                                    }
                                    
                                    var fecha = new Date(2012, 1, 1, 8, 0, 0);
                                    fecha.setMinutes(fecha.getMinutes() + minFila * 30);
                                    formulario.elements["desde"].value = (fecha.getHours() < 10 ? "0" : "") + fecha.getHours() + ":" + (fecha.getMinutes() < 10 ? "0" : "") + fecha.getMinutes();
                                    
                                    fecha = new Date(2012, 1, 1, 8, 0, 0);
                                    fecha.setMinutes(fecha.getMinutes() + maxFila * 30 + 30);
                                    formulario.elements["hasta"].value = (fecha.getHours() < 10 ? "0" : "") + fecha.getHours() + ":" + (fecha.getMinutes() < 10 ? "0" : "") + fecha.getMinutes();
                                    
                                    formulario.elements["duracion"].value = 30 * celdasSeleccionadas.length;
                                }
                            }
                            </script>
                            <table>
                                <tr>
                                    <td class="hora"></td>
                                    <td>Pista 1</td>
                                    <td>Pista 2</td>
                                    <td>Pista 3</td>
                                    <td>Pista 4</td>
                                    <td>Pista 5</td>
                                    <td>Pista 6</td>
                                </tr>
<?php
$fila = 0;
while ($tiempoInicial < $tiempoFinal)
{
    $tiempo = clone $tiempoInicial;
    echo "<tr>\n";
    echo "<td>\n";
    echo $tiempo->format("H:i")." - "; $tiempo->add($intervalo); echo $tiempo->format("H:i");
    echo "</td>\n";
    for ($i = 0; $i < 6; $i++)
    {
        $estado = determinarEstado($reservas[$i], $tiempoInicial);
        $clase = $estado == 0 ? "libre" : ($estado == 1 ? "ocupado" : "noreservable");
        if ($estado == 0)
            echo "<td class=\"$clase\" onclick=\"seleccionar(this, ".($i + 1).", $fila);\"></td>\n";
        else
            echo "<td class=\"$clase\"></td>\n";
    }
    echo "</tr>\n";
    $tiempoInicial = $tiempo;
    $fila++;
}
?>
                            </table>
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
                            minDate: 0, maxDate: '<?php echo $maximodia->format('d/m/Y'); ?>',
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