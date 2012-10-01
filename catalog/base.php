<?php

require_once("minilibreria.php");

/**
 *
 * @param String $titulo Título (<title>) que tendrá la página.
 */
function baseSuperior($titulo)
{
    $esMovil = esMovil();
    if ($titulo == "")
        $titulo = "Inicio";
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="description" content="Almacén y fábrica de calzado nacional. Venta Mayorista. Entrega inmediata. Nuestros productos han sido elaborados con materias primas nacionales." />
		<meta name="keywords" content="almacen calzado, fabrica calzado, venta al por mayor, calzado piel, calzados jam, venta mayorista calzado, calzado español" />
		<meta name="author" content="Calzados JAM" />
		<meta name="geo.position" content="38.278123,-0.719726">
		<meta name="geo.placename" content="Elche, Alicante, Comunidad Valenciana, España">
		<meta name="geo.region" content="es-es">
		<meta charset="UTF-8" />
		
        <title>Calzados JAM. Almacén y fábrica de calzado Español - <?php echo $titulo; ?></title>
        <link type="text/css" rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.23.custom.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/responsiveslides/responsiveslides.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/myMenu.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/freshbutton.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/estilo.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/mensajes.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/reveal.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/google-translate.css" media="screen" />
        
        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="icon" type="image/png" href="favicon.png" />
     
        <script type="text/javascript" src="https://apis.google.com/js/plusone.js">
          {lang: 'es', parsetags: 'explicit'}
        </script>
        <script src="http://maps.google.com/maps/api/js?sensor=false&amp;language=es" type="text/javascript"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/tinymce/jquery.tinymce.js"></script>
        <script src="js/jquery.plugins.js" type="text/javascript"></script>
        <script src="js/jquery.reveal.js" type="text/javascript"></script>
        <script src="js/jquery.numeric.js" type="text/javascript"></script>
        <script src="js/responsiveslides.min.js"></script>
        <script src="js/jquery.textbox-hinter.min.js" type="text/javascript"></script>
        <script src="js/mapa.js" type="text/javascript"></script>
        <script src="js/formularios.js" type="text/javascript"></script>
        <script src="js/cookies.js" type="text/javascript"></script>
        
        <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-8410217-1']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

        </script>
<?php if (!$esMovil) { ?>
		<!--Start of Zopim Live Chat Script-->
		<script type="text/javascript">
		window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
		d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
		_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
		$.src='//cdn.zopim.com/?yAnJYqtfG8LYFeycDZ1mqr1YcB1CKT0B';z.t=+new Date;$.
		type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
		</script>
		<script type="text/javascript">$zopim(function() {
		$zopim.livechat.setLanguage('es');
		});
		</script>
		<!--End of Zopim Live Chat Script-->
<?php } ?>
        <meta name="google-translate-customization" content="83036bbb495e9259-b92db425e1be685c-g15db1c5c55c82e4b-12" />
    </head>
    <body>
        <div id="contenedor">
            <div id="cabecera">
                <div id="contenido-cabecera">                    
                    <table id="tabla-cabecera">
                        <tr>
                            <td id="titulo">
                                <h1><a href="."><span>Calzados JAM. Almacén y fábrica de calzado nacional. Calzados al por Mayor.</span></a></h1>
                            </td>
                            <td class="estirar"></td>
                            <td id="busqueda">
                                <form method="GET" action="catalogo"><input id="busqueda-input" type="text" title="Introduce el nº de referencia o descripción del modelo" value="<?php echo htmlspecialchars(getGet("busqueda")); ?>" name="busqueda" /></form>
                            </td>
                            <td>
                                <?php
                                    $usuario = getUsuario();
                                    if ($usuario == null)
                                    {
                                        echo "<a href=\"clientes\" class=\"btnazul\">Clientes</a>\n";
                                        echo "<a href=\"crearcuenta\" class=\"btnverde\">Nuevo cliente</a>\n";
                                    }
                                    else
                                        echo "<a href=\"cerrarsesion\" class=\"btnrojo\" title=\"Sesión iniciada como ".$usuario->getEmail()."\">Cerrar sesión</a>\n";
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
                        
                        $("#busqueda-input").focus(function(){
                            this.select();
                        });
                        
                        $('.myMenu > li').bind('mouseover', openSubMenu);
                        $('.myMenu > li').bind('mouseout', closeSubMenu);
                        function openSubMenu() {
                            $(this).find('ul').show();
                        };
                        function closeSubMenu() {
                            $(this).find('ul').hide();
                        };
                        
                        gapi.plusone.go();
                    });
                </script>
                
                <div style="clear:both;"></div>
            </div>
            <?php include('mensajes.php'); ?>
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
        $subcategorias = ENCategoria::getByPadre($i->getId());
        echo "    <li>\n";
        if (esMovil() && count($subcategorias) > 0)
            echo "         <a href=\"catalogo?categoria=".$i->getId()."-".rawurlencode($i->getNombre())."\" class=\"firstLevel\" onclick=\"return false;\">".htmlspecialchars($i->getNombre())."</a>\n";
        else
            echo "         <a href=\"catalogo?categoria=".$i->getId()."-".rawurlencode($i->getNombre())."\" class=\"firstLevel\">".htmlspecialchars($i->getNombre())."</a>\n";
            
        if (count($subcategorias) > 0)
        {
            echo "        <ul>\n";
            foreach ($subcategorias as $j)
            {
                echo "            <li><a href=\"catalogo?categoria=".$j->getId()."-".rawurlencode($j->getNombre())."-".$i->getNombre()."\">".htmlspecialchars($j->getNombre())."</a></li>\n";
            }
            echo "         </ul>\n";
        }
        
        echo "    </li>\n";
    }
    
    echo "<li><a href=\"#\" class=\"firstLevel\">Marcas</a><ul>\n";
    $marcas = ENMarca::get();
    foreach ($marcas as $i)
    {
        if (ENModelo::countByMarca($i->getId()) > 0 && $i->getId() != 0)
            echo "<li class=\"horizontal\"><a href=\"catalogo?marca=".$i->getId()."-".rawurlencode($i->getNombre())."\">".htmlspecialchars($i->getNombre())."</a></li>\n";
    }
    echo "<li class=\"horizontal\"><a href=\"catalogo?marca=0\">Otras marcas</a></li>\n";
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
        echo "<li><a href=\"precios\">Listado de precios</a></li>";
        echo "<li><a href=\"shell\">Shell</a></li>";
        echo "</ul></li>\n";
    }

    echo "</ul>\n";
    echo "<div style=\"vertical-align: middle; text-align: right; padding: 10px;\">";
    echo "<div class=\"g-plusone\" data-href=\"http://www.calzadosjam.es\"></div>";
    echo "</div>\n";
    echo "</div>\n";
}

function baseInferior()
{
?>
                </div>
            </div>
            <div id="barra-pie">
                <a href="http://www.calzadosjam.es" class="btnpie"><img src="css/casa.png" alt="Inicio" /> Página de inicio</a>
                <a href="mapa" class="btnpie"><img src="css/mundo.png" alt="Mapa" /> Mapa</a>
                <a href="contacto" class="btnpie"><img src="css/sobre.png" alt="Contacto" /> Contacto</a>
                <a href="privacidad" class="btnpie"><img src="css/candado.png" alt="Privacidad" /> Política de privacidad y datos</a>
                <a href="condiciones" class="btnpieultimo"><img src="css/condiciones.png" alt="Condiciones" /> Condiciones de uso</a>
            </div>
            <div id="pie">
                <a href="http://www.calzadosjam.es"><img src="css/calzadosjam_logo.png" alt="Calzados JAM logo" /></a>
            </div>
        </div>
        
    </body>
</html>
<?php
}
?>
