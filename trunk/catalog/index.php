<?php

include_once "base.php";

baseSuperior("Inicio");
$imagenes = getDirectoryList("slidershow/");
?>

    <div id="externo">
        <div id="interno">
            <ul class="rslides rslides1">        
                <?php
                    foreach ($imagenes as $i)
                    {
                        echo "<li><img src=\"slidershow/".$i."\" alt=\"\" /></li>\n";
                    }
                ?>
            </ul>
        </div>
    </div>
    <div id="textos">
        <div class="texto">
            <div class="cabecera-texto">Quiénes somos</div>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
        </div>
        <div class="separador-textos"></div>
        <div class="texto">
            <div class="cabecera-texto">Historia</div>
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </div>
        <div class="separador-textos"></div>
        <div class="texto">
            <div class="cabecera-texto">Fabricación nacional</div>
            Nam sit amet euismod nibh. Etiam nec ligula nec lorem luctus lacinia eget eu leo. Donec lectus eros, auctor at bibendum id, faucibus et libero. Sed in quam sed nisi imperdiet venenatis nec sit amet lorem.
        </div>
    </div>

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
        
<?php baseInferior(); ?>
