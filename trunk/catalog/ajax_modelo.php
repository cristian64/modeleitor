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
    echo "<tr><td class=\"modelo-foto\"><img id=\"modelo-img\" src=\"img/modelos/".$modelo->getFoto()."\" alt=\"\"></td></tr>";
    echo "<tr>";
    echo "<td class=\"modelo-titulo-modal\">";
    echo "<div class=\"modelo-ref\">Ref. ".$modelo->getReferencia()."</div>";
    if ($activo)
        echo "<div class=\"modelo-precio\">".$modelo->getPrecio()." €</div>";
    echo "<div class=\"modelo-nombre\">".$modelo->getNombre()."</div>";
    echo "<div class=\"modelo-nombre\">Numeración: ".$modelo->getNumeracion()."</div>";
    echo "<div class=\"modelo-descripcion\">".$modelo->getDescripcion()."</div>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
}
else
{
    echo "ERROR";
}

?>
