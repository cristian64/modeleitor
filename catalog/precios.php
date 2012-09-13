<?php

include_once "base.php";

$usuario = getUsuario();
if ($usuario == null)
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.";
    header("location: .");
    exit();
}

if (!$usuario->getAdmin())
{
    $_SESSION["mensaje_aviso"] = "Área restringida a administradores.";
    header("location: .");
    exit();
}
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
        })();
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);

        </script>
        
        <!--<meta name="google-translate-customization" content="83036bbb495e9259-b92db425e1be685c-g15db1c5c55c82e4b-12" />-->
        <style>
            
        table
        {
            border-collapse: collapse;
        }
        td
        {
            border: 1px solid #000;
            padding: 0 5px;
        }
        </style>
    </head>
    <body style="font-size: 8pt; font-family: Calibri, Arial;">
        <img style="margin-bottom: 10px;" src="css/calzadosjam_logo.png" alt="Calzados JAM" width="130xp" />
        <div style="float: right; font-size: 11pt;">www.calzadosjam.es &nbsp;&nbsp;·&nbsp;&nbsp; calzadosjam@gmail.com &nbsp;&nbsp;·&nbsp;&nbsp; (+34)966673439 &nbsp;&nbsp;·&nbsp;&nbsp; <?php echo fechaStr(); ?></div>
        <div>
            <table>
        <?php
            function cmp($a, $b)
            {
                return strcmp($a->getReferencia(), $b->getReferencia());
            }
        
            $modelos = ENModelo::getAdmin();
            usort($modelos, "cmp");
            foreach ($modelos as $i)
            {
                echo "<tr class=\"fila\" style=\"white-space: nowrap;\">";
                echo "<td>".htmlspecialchars($i->getReferencia())."</td>";
                echo "<td style=\"width: 100%; white-space: normal;\">".htmlspecialchars($i->getNombre())."</td>";
                echo "<td class=\"centrada\">".$i->getNumeracion()."</td>";
                echo "<td class=\"centrada\">".str_replace('.', ',', $i->getPrecio())."€</td>";
                //echo "<td class=\"centrada\">".htmlspecialchars($i->getMarca())."</td>";
                echo "</tr>";
            }
        ?>
            </table>
        </div>
    </body>
</html>
