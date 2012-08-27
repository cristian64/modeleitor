<?php

require_once("minilibreria.php");

/**
 *
 * @param String $titulo Título (<title>) que tendrá la página.
 */
function baseSuperior($titulo)
{    
    if ($titulo == "")
        $titulo = "Inicio";
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Calzados JAM - <?php echo $titulo; ?></title>
        
        <link type="text/css" rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.23.custom.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/responsiveslides/responsiveslides.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/myMenu.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/freshbutton.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/mensajes.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/estilo.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/thickbox.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/google-translate.css" media="screen" />
        
        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="icon" type="image/png" href="favicon.png" />
        
        <script src="http://maps.google.com/maps/api/js?sensor=false&amp;language=es" type="text/javascript"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
        <script src="js/responsiveslides.min.js"></script>
        <script src="js/jquery.textbox-hinter.min.js" type="text/javascript"></script>
        <script src="js/mapa.js" type="text/javascript"></script>
        <script src="js/thickbox.js" type="text/javascript"></script>
        <script src="js/formularios.js" type="text/javascript"></script>
        <script src="js/cookies.js" type="text/javascript"></script>
        
        <!--<meta name="google-translate-customization" content="83036bbb495e9259-b92db425e1be685c-g15db1c5c55c82e4b-12" />-->
    </head>
    <body>
        <div id="contenedor">
            <div id="cabecera">
                <div id="contenido-cabecera">                    
                    <table id="tabla-cabecera">
                        <tr>
                            <td id="titulo">
                                <h1><a href="index.php"><span>Calzados JAM</span></a></h1>
                            </td>
                            <td class="estirar"></td>
                            <td id="busqueda">
                                <form method="GET" action="buscar"><input type="text" value="" placeholder="nº de referencia, descripción" <?php if (!esMovil()) echo "class=\"animacion\""; ?> /></form>
                            </td>
                            <td>
                                <?php
                                    $usuario = getUsuario();
                                    if ($usuario == null)
                                        echo "<a href=\"clientes\" class=\"btnazul\">Clientes</a>\n";
                                    else
                                        echo "<a href=\"cerrarsesion\" class=\"btnrojo\">Cerrar sesión</a>\n";
                                ?>
                            </td>
                            <td id="idioma">
                                <div id="google_translate_element"></div>
                                <script type="text/javascript">
                                    function googleTranslateElementInit() {
                                        new google.translate.TranslateElement({pageLanguage: 'es', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false}, 'google_translate_element');
                                    }
                                </script>
                                <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                            </td>
                        </tr>                    
                    </table>
                </div>
            </div>
            <div id="barra">
                
                <?php bloqueCategorias(); ?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        
                        $('.myMenu > li').bind('mouseover', openSubMenu);
                        $('.myMenu > li').bind('mouseout', closeSubMenu);
                        function openSubMenu() {
                            $(this).find('ul').show();
                        };
                        function closeSubMenu() {
                            $(this).find('ul').hide();
                        };
                    });
                </script>
                
                <div style="clear:both;"></div>
            </div>
            
            <div id="contenido">
                <div id="cuerpo">
                    <?php include('mensajes.php'); ?>
<?php
}

function bloqueCategorias()
{    
    echo "<div id=\"menu\">\n";
    echo "<ul class=\"myMenu\">\n";
    
    $categorias = ENCategoria::getByPadre(0);
    foreach ($categorias as $i)
    {
        $subcategorias = ENCategoria::getByPadre($i->getId());
        echo "    <li>\n";
        if (esMovil() && count($subcategorias) > 0)
            echo "         <a href=\"catalogo?categoria=".$i->getId()."\" class=\"firstLevel\" onclick=\"return false;\">".$i->getNombre()."</a>\n";
        else
            echo "         <a href=\"catalogo?categoria=".$i->getId()."\" class=\"firstLevel\">".$i->getNombre()."</a>\n";
            
        if (count($subcategorias) > 0)
        {
            echo "        <ul>\n";
            foreach ($subcategorias as $j)
            {
                echo "            <li><a href=\"catalogo?categoria=".$j->getId()."\">".$j->getNombre()."</a></li>\n";
            }
            echo "         </ul>\n";
        }
        
        echo "    </li>\n";
    }
    
    echo "<li><a href=\"#\" class=\"firstLevel\">Marcas</a><ul>\n";
    $marcas = ENMarca::get();
    foreach ($marcas as $i)
    {
        echo "<li><a href=\"catalogo?marca=".$i->getId()."\">".$i->getNombre()."</a></li>\n";
    }
    echo "</ul></li>\n";
    
    $usuario = getUsuario();
    if ($usuario != null && $usuario->getAdmin())
    {
        echo "<li><a href=\"\" class=\"firstLevel\" onclick=\"return false;\">Administración</a><ul>";
        echo "<li><a href=\"modelos\">Modelos</a></li>";
        echo "<li><a href=\"categorias\">Categorías</a></li>";
        echo "<li><a href=\"marcas\">Marcas</a></li>";
        echo "<li><a href=\"fabricantes\">Fabricantes</a></li>";
        echo "<li><a href=\"usuarios\">Usuarios</a></li>";
        echo "<li><a href=\"shell\">Shell</a></li>";
        echo "</ul></li>\n";
    }

    echo "</ul>\n";
    echo "</div>\n";
}

function baseInferior()
{
?>
                </div>
            </div>
            <div id="barra-pie">
                <a href="http://www.calzadosjam.es" class="btnpie"><img src="css/casa.png" alt="" /> Página de inicio</a>
                <a href="mapa" class="btnpie"><img src="css/mundo.png" alt="" /> Mapa</a>
                <a href="contacto" class="btnpie"><img src="css/sobre.png" alt="" /> Contacto</a>
                <a href="privacidad" class="btnpie"><img src="css/candado.png" alt="" /> Política de privacidad y datos</a>
                <a href="condiciones" class="btnpieultimo"><img src="css/condiciones.png" alt="" /> Condiciones de uso</a>
            </div>
            <div id="pie">
                <img src="css/calzadosjam_logo.png" alt="Calzados JAM logo" />
            </div>
        </div>
        
    </body>
</html>
<?php
}
?>
