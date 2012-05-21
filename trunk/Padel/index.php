<?php
include_once "base.php";

$usuario = getUsuario();
if ($usuario != null)
    baseSuperior("Inicio", true);
else
    baseSuperior("Inicio", false);
?>
    <div id="externo"><div id="interno">
        <div id="inicio">
            <div id="slider_container_2">
                    <div id="SliderName_2" class="SliderName_2">
                            <img src="fotos/1.png" width="900" height="450" alt="Pista 1" title="Pista 1" usemap="#img1map" />
                            <map name="img1map">
                                    <area href="#img1map-area1" shape="rect" coords="100,100,200,200" />
                                    <area href="#img1map-area2" shape="rect" coords="300,100,400,200" />
                            </map>
                            <div class="SliderName_2Description">Pista <strong>1</strong></div>
                            <img src="fotos/2.png" width="900" height="450" alt="Pista 2" title="Pista 2" />
                            <div class="SliderName_2Description">Pista <strong>2</strong></div>
                            <img src="fotos/3.png" width="900" height="450" alt="Pista 3" title="Pista 3" />
                            <div class="SliderName_2Description">Pista <strong>3</strong></div>
                            <img src="fotos/4.png" width="900" height="450" alt="Pista 4" title="Pista 4" />
                            <div class="SliderName_2Description">Pista <strong>4</strong></div>
                    </div>
                    <div style="clear:both;"></div>
                    <div id="SliderNameNavigation_2"></div>
                    <div style="clear:both;"></div>

                    <script type="text/javascript">
                            effectsDemo2 = 'rain,stairs,fade';
                            var demoSlider_2 = Sliderman.slider({container: 'SliderName_2', width: 900, height: 450, effects: effectsDemo2,
                                    display: {
                                            autoplay: 3000,
                                            loading: {background: '#000000', opacity: 0.5, image: 'css/loading.gif'},
                                            buttons: {hide: true, opacity: 1, prev: {className: 'SliderNamePrev_2', label: ''}, next: {className: 'SliderNameNext_2', label: ''}},
                                            description: {hide: true, background: '#000000', opacity: 0.4, height: 50, position: 'bottom'},
                                            navigation: {container: 'SliderNameNavigation_2', label: '<img src="css/clear.gif" />'}
                                    }
                            });
                    </script>

                    <div style="clear:both;"></div>
            </div>
            <div style="clear:both;"></div>
        </div>
            <?php if ($usuario == null) { ?>
            <a href="iniciarsesion.php" class="freshbutton-big freshbutton-extrabig"><div>Iniciar sesión<br><small>para reservar pista</small></div></a>&nbsp;&nbsp;&nbsp;
            <a href="registrarse.php" class="freshbutton-big freshbutton-extrabig"><div>Registrarse<br><small>para reservar pista</small></div></a>
            <?php } ?>
            <table class="bloques">
                <tr>
                    <td><div class="bloque">6 pistas de padel recién construidas</div></td>
                    <td><div class="bloque"><?php echo $PRECIOHORA; ?>€ por hora</div></td>
                    <td><div class="bloque">Vereda Santa Teresa, Matola (Elche, Alicante)</div></td>
                </tr>
            </table>
    </div></div>

<?php
baseInferior();
?>
