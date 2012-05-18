<?php
include_once "base.php";
baseSuperior("Reservar pista");
?>
                    <div id="reservar">
                        <h3><span>Reservar pista</span></h3>
                        <div id="resumenreserva">
                            <div id="datepicker"></div>
                            <form action="operarregistro.php" method="post" enctype="multipart/form-data" onsubmit="return validarReserva(this);">
                                <div><label>Día: </label><input type="text" value="11/02/2012" name="dia" style="width: 100px;" /></div>
                                <div><label>Pista: </label><input type="text" value="3" name="pista" style="width: 30px;" /></div>
                                <div><label>Desde las </label><input type="text" value="10:30" name="desde" style="width: 50px;" /><label> hasta las </label><input type="text" value="12:30" name="hasta" style="width: 50px;" /></div>
                                <div><label>Duración: </label><input type="text" value="60" name="duracion" style="width: 30px;" /><label> minutos</label></div>
                                <div><input type="submit" value="Confirmar reserva" name="" class="freshbutton-big" /></div>
                            </form>
                            <div id="tiempo">
                                <div id="TT_tyswbxdhtIEjjFaA7fujzzDDD9lA1A2lLY1tkciIq1j"></div>
                                <script type="text/javascript" src="http://www.tutiempo.net/widget/eltiempo_tyswbxdhtIEjjFaA7fujzzDDD9lA1A2lLY1tkciIq1j"></script>
                            </div>
                        </div>
                        <div id="tablapistas">
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
$tiempoInicial = new DateTime();
$tiempoFinal = new DateTime();
$tiempoInicial->setTime(8, 0);
$tiempoFinal->setTime(23, 59, 59);

$intervalo = $tiempoInicial->diff($tiempoInicial);
$intervalo->i = 30;

while ($tiempoInicial < $tiempoFinal)
{
?>
                                <tr>
                                    <td><?php echo $tiempoInicial->format("H:i")." - "; $tiempoInicial->add($intervalo); echo $tiempoInicial->format("H:i"); ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
<?php
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
                            minDate: 0, maxDate: "+7D"
                        });
                    });
                    </script>
<?php
baseInferior();
?>
