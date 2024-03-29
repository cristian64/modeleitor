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
    if ($modelo->getDescatalogado())
        echo "<div class=\"modelo-descatalogado-modal\">Descatalogado</div>";
    echo "<img id=\"modelo-img\" src=\"img/modelos/".$modelo->getFoto()."\" alt=\"".$modelo->getNombre()."\" title=\"".$modelo->getNombre()."\" /></td></tr>";
    echo "<tr>";
    echo "<td class=\"modelo-titulo-modal\">";
    echo "<div class=\"modelo-ref\">Ref. ".$modelo->getReferencia()."</div>";
    if ($activo)
        if ($modelo->getOferta())
            echo "<div class=\"modelo-precio\"><span class=\"modelo-precio-tachado\">".str_replace('.', ',', $modelo->getPrecio())." €</span> <span class=\"modelo-precio-oferta\">".str_replace('.', ',', $modelo->getPrecioOferta())."€ <small>IVA no incluido</small></span></div>";
        else
            echo "<div class=\"modelo-precio\">".str_replace('.', ',', $modelo->getPrecio())."€ <small>IVA no incluido</small></div>";
    echo "<div class=\"modelo-nombre\">".$modelo->getNombre()."</div>";
    if ($modelo->getTallaMenor() > 0 || $modelo->getTallaMayor() > 0)
        echo "<div class=\"modelo-nombre\">Numeración: ".$modelo->getNumeracion()."</div>";
    echo "<div class=\"modelo-descripcion\">".$modelo->getDescripcion()."</div>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    
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

        </script>
<?php
}
else
{
    echo "ERROR";
}

?>
