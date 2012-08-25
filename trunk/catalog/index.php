<?php

include_once "base.php";

baseSuperior("Inicio");
?>

    <div id="externo">
        <div id="interno">
    <ul class="rslides rslides1">
        <?php
            $imagenes = getDirectoryList("slidershow/");
            foreach ($imagenes as $i)
            {
                if (strpos($i,'jpg') !== false || strpos($i,'png') !== false)
                    echo "<img src=\"slidershow/".$i."\" alt=\"\" />\n";
            }
        ?>
    </ul>
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
