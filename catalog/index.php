<?php

include_once "base.php";

baseSuperior("Inicio");
?>

    <div id="externo">
        <div id="interno">
    <div id="slidershow">
        <?php
            $imagenes = getDirectoryList("slidershow/");
            foreach ($imagenes as $i)
            {
                echo "<img src=\"slidershow/".$i."\" alt=\"\" />\n";
            }
        ?>
    </div>
    </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function() { $('#slidershow').hide(); });
        
    $(window).load(function() {
        
        $('#slidershow').show();
        
        $('#slidershow').orbit({
             //animation: 'fade',                  // fade, horizontal-slide, vertical-slide, horizontal-push
             animationSpeed: 300,                // how fast animtions are
             timer: true, 			 // true or false to have the timer
             advanceSpeed: 5000, 		 // if timer is enabled, time between transitions 
             pauseOnHover: false, 		 // if you hover pauses the slider
             startClockOnMouseOut: true, 	 // if clock should start on MouseOut
             startClockOnMouseOutAfter: 50, 	 // how long after MouseOut should the timer start again
             directionalNav: true, 		 // manual advancing directional navs
             captions: false, 			 // do you want captions?
             //captionAnimation: 'fade', 		 // fade, slideOpen, none
             //captionAnimationSpeed: 800, 	 // if so how quickly should they animate in
             bullets: false,			 // true or false to activate the bullet navigation
             bulletThumbs: true,		 // thumbnails for the bullets
             bulletThumbLocation: '../css/orbit'		 // location from this file where thumbs will be
             //afterSlideChange: function(){} 	 // empty function 
            });
    });
    </script>
        
<?php baseInferior(); ?>
