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
                            <map name="img1map" id="img1map">
                                    <area href="#img1map-area1" shape="rect" coords="100,100,200,200" alt="" />
                                    <area href="#img1map-area2" shape="rect" coords="300,100,400,200" alt="" />
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
                        
							Sliderman.effect({name: 'devtrix01', cols: 10, rows: 5, fade: true, order: 'swirl', delay: 50});
							Sliderman.effect({name: 'devtrix02', cols: 10, rows: 5, fade: true, order: 'swirl', road: 'TL', reverse: true, delay: 50});
							Sliderman.effect({name: 'devtrix03', cols: 1, rows: 1, duration: 500, fade: true});
							Sliderman.effect({name: 'devtrix04', cols: 1, rows: 6, duration: 500, fade: true, top: true});
							Sliderman.effect({name: 'devtrix05', cols: 10, rows: 1, duration: 500, fade: true, left: true});
							Sliderman.effect({name: 'devtrix06', cols: 9, rows: 3, delay: 50, duration: 500, fade: true, right: true});
							Sliderman.effect({name: 'devtrix07', cols: 9, rows: 3, delay: 50, chess: true, duration: 500, fade: true, bottom: true});
							Sliderman.effect({name: 'devtrix08', cols: 6, rows: 3, delay: 70, duration: 400, move: true, top: true});
							Sliderman.effect({name: 'devtrix09', cols: 8, rows: 4, delay: 70, duration: 400, move: true, top: true, road: 'TL', order: 'straight'});
							Sliderman.effect({name: 'devtrix10', cols: 10, rows: 5, delay: 40, duration: 500, fade: true, road: 'TL', order: 'straight_stairs'});
							Sliderman.effect({name: 'devtrix11', cols: 10, rows: 5, delay: 40, duration: 500, fade: true, road: 'BR', order: 'straight_stairs'});
							Sliderman.effect({name: 'devtrix12', cols: 10, rows: 5, delay: 10, fade: true, order: 'straight_stairs'});
							Sliderman.effect({name: 'devtrix13', cols: 10, rows: 5, delay: 50, duration: 500, fade: true, road: 'TR', order: 'snake'});
							Sliderman.effect({name: 'devtrix14', cols: 10, rows: 5, delay: 30, duration: 500, fade: true, road: 'RB', order: 'snake'});
							Sliderman.effect({name: 'devtrix15', cols: 10, rows: 5, delay: 30, duration: 500, fade: true, road: 'LT', order: 'snake'});
							Sliderman.effect({name: 'devtrix16', cols: 6, rows: 1, duration: 400, fade: true, move: true, left: true});
							Sliderman.effect({name: 'devtrix17', cols: 1, rows: 4, duration: 400, fade: true, move: true, top: true});
							Sliderman.effect({name: 'devtrix18', cols: 10, rows: 5, fade: true, delay: 10, duration: 400});
							Sliderman.effect({name: 'devtrix19', fade: true, duration: 500, move: true, top: true});
							Sliderman.effect({name: 'devtrix20', fade: true, duration: 400, move: true, left: true});
							Sliderman.effect({name: 'devtrix21', fade: true, duration: 400, move: true, right: true});
							Sliderman.effect({name: 'devtrix22', fade: true, duration: 500, move: true, bottom: true});
							Sliderman.effect({name: 'devtrix23', cols: 10, delay: 100, duration: 400, order: 'straight', bottom: true, road: 'RB', fade: true});
							Sliderman.effect({name: 'devtrix24', rows: 8, delay: 100, duration: 400, order: 'straight', left: true, fade: true, chess: true});
							Sliderman.effect({name: 'devtrix25', cols: 10, delay: 100, duration: 500, order: 'straight', right: true, move: true, zoom: true, fade: true});
							Sliderman.effect({name: 'devtrix26', rows: 7, cols: 14, fade: true, easing: 'swing', order: 'cross', delay: 100, duration: 400});
							Sliderman.effect({name: 'devtrix27', rows: 7, cols: 14, fade: true, easing: 'swing', order: 'cross', delay: 100, duration: 400, reverse: true});
							Sliderman.effect({name: 'devtrix28', rows: 7, cols: 14, fade: true, easing: 'swing', order: 'rectangle', delay: 200, duration: 1000});
							Sliderman.effect({name: 'devtrix29', rows: 7, cols: 14, fade: true, easing: 'swing', order: 'rectangle', delay: 200, duration: 1000, reverse: true});
							Sliderman.effect({name: 'devtrix30', rows: 7, cols: 10, zoom: true, move: true, right: true, easing: 'swing', order: 'circle', delay: 150, duration: 800});
							Sliderman.effect({name: 'devtrix31', rows: 7, cols: 10, zoom: true, move: true, left: true, easing: 'swing', order: 'circle', delay: 150, duration: 800, reverse: true});
							Sliderman.effect({name: 'devtrix32', rows: 7, cols: 1, zoom: true, move: true, bottom: true, easing: 'bounce', order: 'circle', delay: 150, duration: 800});
							Sliderman.effect({name: 'devtrix33', rows: 7, cols: 1, zoom: true, move: true, top: true, easing: 'bounce', order: 'circle', delay: 150, duration: 800, reverse: true});

							// we don't want the default presets (fade,move,stairs,blinds,rain) to appear, so passing an array of the effect we created above
							devtrixEffects = ['devtrix01','devtrix02','devtrix03','devtrix04','devtrix05','devtrix06','devtrix07','devtrix08','devtrix09','devtrix10','devtrix11','devtrix12','devtrix13','devtrix14','devtrix15','devtrix16','devtrix17','devtrix18','devtrix19','devtrix20','devtrix21','devtrix22','devtrix23','devtrix24','devtrix25','devtrix26','devtrix27','devtrix28','devtrix29','devtrix30','devtrix31','devtrix32','devtrix33'];

                            var demoSlider_2 = Sliderman.slider({container: 'SliderName_2', width: 900, height: 450, effects: devtrixEffects,
                                    display: {
                                            autoplay: 3000,
                                            loading: {background: '#000000', opacity: 0.5, image: 'css/loading.gif'},
                                            buttons: {hide: true, opacity: 1, prev: {className: 'SliderNamePrev_2', label: ''}, next: {className: 'SliderNameNext_2', label: ''}},
                                            description: {hide: true, background: '#000000', opacity: 0.4, height: 50, position: 'bottom'},
                                            navigation: {container: 'SliderNameNavigation_2', label: '<img src="css/clear.gif" alt="" />'}
                                    }
                            });
                    </script>

                    <div style="clear:both;"></div>
            </div>
            <div style="clear:both;"></div>
        </div>
            <?php if ($usuario == null) { ?>
            <a href="iniciarsesion.php" class="freshbutton-big freshbutton-extrabig"><span>Iniciar sesión<br /><small>para reservar pista</small></span></a>&nbsp;&nbsp;&nbsp;
            <a href="registrarse.php" class="freshbutton-big freshbutton-extrabig"><span>Registrarse<br /><small>para reservar pista</small></span></a>
            <?php } ?>
            <table class="bloques">
                <tr>
                    <td><div class="bloque">6 pistas de padel recién construidas</div></td>
                    <td><div class="bloque">Vesturarios y cafetería dentro del recinto</td>
                    <td><div class="bloque">Vereda Santa Teresa, Matola (Elche, Alicante)</div></td>
                </tr>
            </table>
    </div></div>

<?php
baseInferior();
?>
