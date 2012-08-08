<?php
include_once "base.php";
baseSuperior("");
?>
        <div id="mapa">
            <h3><span>Mapa</span></h3>
            <div id="mapaapi"></div>
        </div>
        <script type="text/javascript">
            GoogleMaps3(false, "mapaapi");
        </script>
<?php
baseInferior();
?>
