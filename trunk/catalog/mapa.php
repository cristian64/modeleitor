<?php

include_once "base.php";

baseSuperior("Mapa");
?>

    <div id="mapa">
        <div id="mapaapi"></div>
    </div>
    <script type="text/javascript">
        GoogleMaps3("mapaapi");
    </script>
        
<?php baseInferior(); ?>
