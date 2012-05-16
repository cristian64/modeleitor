<?php
include_once "base.php";
baseSuperior("");
?>
		<div id="mapa">
            <h3><span>Mapa</span></h3>
            <div id="mapaapi"></div>
            <div id="mapalinks">
                <a href="mapagrande.php">Ver mapa en pantalla completa</a>
            </div>
        </div>
        <script type="text/javascript">
            GoogleMaps3(false);
        </script>
<?php
baseInferior();
?>
