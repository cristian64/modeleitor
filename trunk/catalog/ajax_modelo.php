<?php
include_once "minilibreria.php";

$usuario = getUsuario();
$activo = $usuario != null && $usuario->getActivo();
$admin = $usuario != null && $usuario->getAdmin();

$id = getPost("id");
$modelo = ENModelo::getById($id);
if ($modelo != null && (!$modelo->getDescatalogado() || $admin))
{
    //TODO: devolver html con el modelillo :)
?>

    <table>
        <tr>
            <td>
<?php
                echo "<div class=\"modelo-wrapper\">";
                echo "<div class=\"modelo-foto\"><img src=\"img/modelos/".$modelo->getFoto()."\" alt=\"\" width=\"100%\"></div>";
                echo "<div class=\"modelo-titulo\"><div class=\"modelo-ref\">Ref. ".$modelo->getReferencia()."</div>";
                if ($activo)
                    echo "<div class=\"modelo-precio\">".$modelo->getPrecio()."€</div>";
                echo "<div class=\"modelo-nombre\">".$modelo->getNombre()."</div></div></div></div>\n";
?>
            </td>
            <td>
<?php

?>
            </td>
        </tr>
    </table>

<?php
}
else
{
    echo "ERROR $id";
}

?>
