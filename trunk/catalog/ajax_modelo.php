<?php
include_once "minilibreria.php";

$usuario = getUsuario();
$activo = $usuario != null && $usuario->getActivo();
$admin = $usuario != null && $usuario->getAdmin();

$id = getPost("id");
$modelo = ENModelo::getById($id);
if ($modelo != null && (!$modelo->getDescatalogado() || $admin))
{
    echo "<table style=\"width: 100%; height: 100%;\">";
    echo "<tr><td class=\"modelo-foto\">";
    if ($modelo->getOferta())
        echo "<div class=\"modelo-oferta-modal\">Oferta</div>";
    echo "<img id=\"modelo-img\" src=\"img/modelos/".$modelo->getFoto()."\" alt=\"\"></td></tr>";
    echo "<tr>";
    echo "<td class=\"modelo-titulo-modal\">";
    echo "<div class=\"modelo-ref\">Ref. ".$modelo->getReferencia()."</div>";
    if ($activo)
        if ($modelo->getOferta())
            echo "<div class=\"modelo-precio\"><span class=\"modelo-precio-tachado\">".str_replace('.', ',', $modelo->getPrecio())." €</span> <span class=\"modelo-precio-oferta\">".str_replace('.', ',', $modelo->getPrecioOferta())." €</span></div>";
        else
            echo "<div class=\"modelo-precio\">".str_replace('.', ',', $modelo->getPrecio())." €</div>";
    echo "<div class=\"modelo-nombre\">".$modelo->getNombre()."</div>";
    echo "<div class=\"modelo-nombre\">Numeración: ".$modelo->getNumeracion()."</div>";
    echo "<div class=\"modelo-descripcion\">".$modelo->getDescripcion()."</div>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    echo "<div class=\"g-plusone-button\"><div class=\"g-plusone\"></div></div>";
    
?>
        <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-8410217-1']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
         
        gapi.plusone.go();

        </script>
<?php
}
else
{
    echo "ERROR";
}

?>
