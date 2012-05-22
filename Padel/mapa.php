<?php
include_once "base.php";
baseSuperior("");
?>
        <div id="mapa">
            <h3><span>Mapa</span></h3>
            <div id="mapalinks">
                <a href="mapagrande.php" class="freshbutton-lightblue">Ver mapa en pantalla completa</a>
            </div>
            <div id="mapaapi"></div>
        </div>
        <script type="text/javascript">
            GoogleMaps3(false, "mapaapi");
        </script>
<?php
baseInferior();
?>
