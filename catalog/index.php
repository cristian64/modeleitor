<?php

include_once "base.php";

baseSuperior("Inicio");
$imagenes = getDirectoryList("img/slidershow/");
shuffle($imagenes);
$imagenes = array_slice($imagenes, 0, 5);
if (rand(0, 3) == 0)
    array_unshift($imagenes, "s1.jpg");
else
    array_unshift($imagenes, "s2.jpg");
$imagenes = array_unique($imagenes);
?>

    <div id="externo">
        <div id="interno">
            <ul class="rslides rslides1">
                <?php
                    foreach ($imagenes as $i)
                    {
                        echo "<li><img src=\"img/slidershow/".$i."\" alt=\"\" /></li>\n";
                    }
                ?>
            </ul>
        </div>
    </div>
    <div id="textos">
        <div class="texto">
            <div class="cabecera-texto">Quiénes somos</div>
            Calzados JAM es un almacén y fábrica de calzado nacional, donde fabricamos y distribuimos las últimas tendencias en moda española. También nos suministran otras fábricas de Elche, Elda, Almansa, Yecla, etc. Nuestros clientes son las tiendas y almacenes de toda Europa.  Disponemos de todo tipo de calzado y también podemos fabricar modelos adaptándonos a sus necesidades. Estamos especializados en la venta mayorista, surtido libre y la entrega en 24h.
            <div class="texto-transportes"><a href="http://www.seur.es"><img src="css/seur.png" alt="SEUR" /></a><a href="http://www.envialia-urgente.com"><img src="css/envialia.png" alt="Enviália" /></a><a href="http://www.martinehijos.com"><img src="css/martinehijos.png" alt="Martín e Hijos" /></a></div>
        </div>
        <div class="separador-textos"></div>
        <div class="texto">
            <div class="cabecera-texto">Cómo</div>            
            Atendemos pedidos en el almacén, por teléfono o e-mail. Los clientes pueden decidir entre cajas o surtido libre, donde pueden comprar aquellas tallas que deseen y la cantidad que necesiten.
        </div>
        <div class="separador-textos"></div>
        <div class="texto">
            <div class="cabecera-texto">Fabricación nacional</div>
            Nuestros productos han sido elaborados con materias primas nacionales, dando trabajo a un número elevado de personas, y usted con su adquisición contribuye a todo ello.
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
