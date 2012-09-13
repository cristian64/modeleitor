<?php
require_once "minilibreria.php";

$usuario = getUsuario();
$activo = $usuario != null && $usuario->getActivo();
$admin = $usuario != null && $usuario->getAdmin();

$id = getGet("id");
$modelo = ENModelo::getById($id);
if ($modelo != null && (!$modelo->getDescatalogado() || $admin))
{
    
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Calzados JAM - Modelo <?php echo htmlspecialchars($modelo->getReferencia()); ?></title>
        
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

        <!--<meta name="google-translate-customization" content="83036bbb495e9259-b92db425e1be685c-g15db1c5c55c82e4b-12" />-->
    </head>
    <body>
        
<?php
    echo "<table style=\"width: 100%; height: 100%;\">";
    echo "<tr><td class=\"modelo-foto\">";
    if ($modelo->getOferta())
        echo "<div class=\"modelo-oferta-modal\">Oferta</div>";
    echo "<img id=\"modelo-img\" src=\"img/modelos/".$modelo->getFoto()."\" alt=\"\" style=\"width: 100%;\"></td></tr>";
    echo "<tr>";
    echo "<td class=\"modelo-titulo-modal\">";
    echo "<div class=\"modelo-ref\">Ref. ".htmlspecialchars($modelo->getReferencia())."</div>";
    if ($activo)
        if ($modelo->getOferta())
            echo "<div class=\"modelo-precio\"><span class=\"modelo-precio-tachado\">".str_replace('.', ',', $modelo->getPrecio())." €</span> <span class=\"modelo-precio-oferta\">".str_replace('.', ',', $modelo->getPrecioOferta())." €</span></div>";
        else
            echo "<div class=\"modelo-precio\">".str_replace('.', ',', $modelo->getPrecio())." €</div>";
    echo "<div class=\"modelo-nombre\">".htmlspecialchars($modelo->getNombre())."</div>";
    echo "<div class=\"modelo-nombre\">Numeración: ".$modelo->getNumeracion()."</div>";
    echo "<div class=\"modelo-descripcion\">".$modelo->getDescripcion()."</div>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
}
else
{
    
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Calzados JAM; ?></title>
        
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
        
        <!--<meta name="google-translate-customization" content="83036bbb495e9259-b92db425e1be685c-g15db1c5c55c82e4b-12" />-->
    </head>
    <body>
<?php
    echo "No se encontró el modelo";
}

?>

    </body>
</html>
