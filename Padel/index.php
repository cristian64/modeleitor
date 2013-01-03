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
            <ul class="rslides rslides1">
                <?php
                    $fotos = getDirectoryList("portada/");
                    shuffle($fotos);
                    foreach ($fotos as $i)
                    {
                        if (strpos($i, 'thumb') == false)
                            echo "<li><img src=\"portada/".$i."\" alt=\"\" /></li>\n";
                    }
                ?>
            </ul>
            
        </div>
            <?php if ($usuario == null) { ?>
            <a href="iniciarsesion.php" class="freshbutton-big freshbutton-extrabig"><span>Iniciar sesión<br /><small>para reservar pista</small></span></a>&nbsp;&nbsp;&nbsp;
            <a href="registrarse.php" class="freshbutton-big freshbutton-extrabig"><span>Registrarse<br /><small>para reservar pista</small></span></a>
            <?php } ?>
            <table class="bloques">
                <tr>
                    <td><div class="bloque">6 pistas de padel recién construidas en Matola</div></td>
                    <td><div class="bloque">Pista y menú en el restaurante por 10€</td>
                    <td><div class="bloque">Servicio de fisioterapia</div></td>
                </tr>
            </table>
    </div></div>
    
    <script type="text/javascript">
    $(function () {
        $(".rslides").responsiveSlides({
  auto: true,             // Boolean: Animate automatically, true or false
  speed: 1000,            // Integer: Speed of the transition, in milliseconds
  timeout: 4000,          // Integer: Time between slide transitions, in milliseconds
  pager: false,           // Boolean: Show pager, true or false
  nav: true,             // Boolean: Show navigation, true or false
  random: false,          // Boolean: Randomize the order of the slides, true or false
  pause: false,           // Boolean: Pause on hover, true or false
  pauseControls: false,   // Boolean: Pause when hovering controls, true or false
  prevText: "Anterior",   // String: Text for the "previous" button
  nextText: "Siguiente",       // String: Text for the "next" button
  maxwidth: "",           // Integer: Max-width of the slideshow, in pixels
  controls: ".rslides1",           // Selector: Where controls should be appended to, default is after the 'ul'
  namespace: "rslides",   // String: change the default namespace used
  before: function(){},   // Function: Before callback
  after: function(){}     // Function: After callback
});
    });
    </script>

<?php
baseInferior();
?>
