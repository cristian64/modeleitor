<?php

require_once("minilibreria.php");

/**
 *
 * @param String $titulo Título (<title>) que tendrá la página.
 */
function baseSuperior($titulo, $mostrarmenu = true)
{
    if ($titulo == "")
        $titulo = "Inicio";
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Calzados JAM - <?php echo $titulo; ?></title>
        
        <link type="text/css" rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.23.custom.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/orbit/orbit-1.2.3.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/myMenu.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/freshbutton.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/mensajes.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/estilo.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/thickbox.css" media="screen" />
        
        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="icon" type="image/png" href="favicon.png" />
        
        <script src="http://maps.google.com/maps/api/js?sensor=false&amp;language=es" type="text/javascript"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
        <script src="js/jquery.orbit-1.2.3.min.js"></script>
        <script src="js/jquery.textbox-hinter.min.js" type="text/javascript"></script>
        <script src="js/mapa.js" type="text/javascript"></script>
        <script src="js/thickbox.js" type="text/javascript"></script>
        <script src="js/formularios.js" type="text/javascript"></script>
        <script src="js/cookies.js" type="text/javascript"></script>
        
        <meta name="google-translate-customization" content="83036bbb495e9259-b92db425e1be685c-g15db1c5c55c82e4b-12" />
    </head>
    <body>
        <div id="contenedor">
            <div id="cabecera">
                <div id="busquedarapida"><form id="busquedarapidaform" method="GET" action="buscar"><input type="text" value="" placeholder="nº de referencia o descripción" /></form><div style="clear:both;"></div></div>
            </div>
            <div id="barra">
                
                <div id="titulo">
                    <h1><a href="http://www.calzadosjam.es"><span>Calzados JAM</span></a></h1>
                </div>
                
                <?php bloqueCategorias(); ?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('.myMenu > li').bind('mouseover', openSubMenu);
                        $('.myMenu > li').bind('mouseout', closeSubMenu);
                        function openSubMenu() {
                            $(this).find('ul').css('visibility', 'visible');
                        };
                        function closeSubMenu() {
                            $(this).find('ul').css('visibility', 'hidden');
                        };
                    });
                    
                    $("#busquedarapidaform").hide();
                    
                    var mostradaBusquedaRapida = false;
                    function mostrarBusquedaRapida()
                    {
                        if (!mostradaBusquedaRapida)
                            $("#busquedarapidaform").show("blind");                        
                        else
                            $("#busquedarapidaform").hide("blind");
                            
                        mostradaBusquedaRapida = !mostradaBusquedaRapida;
                    }
                </script>
                
                <div style="clear:both;"></div>
            </div>
            
            <div id="contenido">
                <div id="cuerpo">
<?php
}

function bloqueCategorias()
{
    echo "<div id=\"menu\">\n";
    echo "<ul class=\"myMenu\">\n";

    $categorias = ENCategoria::getByPadre(0);
    foreach ($categorias as $i)
    {
        echo "    <li>\n";
        echo "         <a href=\"#\" class=\"firstLevel\">".$i->getNombre()."</a>\n";
        $subcategorias = ENCategoria::getByPadre($i->getId());
        if (count($subcategorias) > 0)
        {
            echo "        <ul>\n";
            foreach ($subcategorias as $j)
            {
                echo "            <li><a href=\"#\">".$j->getNombre()."</a></li>\n";
            }
            echo "         </ul>\n";
        }
        
        echo "    </li>\n";
    }
    echo "    <li><a href=\"#\" class=\"firstLevel expandirbuscar\" onclick=\"mostrarBusquedaRapida();\">Buscar</a></li>\n";
    echo "    <li><a href=\"iniciarsesion\" class=\"firstLevel expandirbuscar expandiracceso\">Iniciar sesión</a></li>\n";

    echo "</ul>\n";
    echo "</div>\n";
}

function baseInferior()
{
?>
                </div>

            </div>
            <div id="pie">
                <div id="google_translate_element"></div>
                <script type="text/javascript">
                    function googleTranslateElementInit() {
                    new google.translate.TranslateElement({pageLanguage: 'es', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false}, 'google_translate_element');
                    }
                </script>
                <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
            </div>
        </div>
        
    </body>
</html>
<?php
}
?>
