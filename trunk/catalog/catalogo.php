<?php

include_once "base.php";

baseSuperior("Catálogo");

$usuario = getUsuario();
$activo = $usuario != null && $usuario->getActivo();
$admin = $usuario != null && $usuario->getAdmin();
$modelos = array();
$nombreCategoria = "";


$id_categoria = getGet("categoria");
if ($id_categoria != "")
{
    $nombreCategoria = "";
    $modelos = ENModelo::getByCategoria($id_categoria, 1, 9999);
    
    $categoria = ENCategoria::getById($id_categoria);
    while ($categoria != null)
    {
        if ($nombreCategoria == "")
            $nombreCategoria = $categoria->getNombre();
        else
            $nombreCategoria = "<a href=\"".$categoria->getId()."\">".$categoria->getNombre()."</a>"." &raquo; ".$nombreCategoria;
        $categoria = ENCategoria::getById($categoria->getIdPadre());
    }
}

$id_marca = getGet("marca");
if ($id_marca != "" && count($modelos) == 0)
{
    $modelos = ENModelo::getByMarca($id_marca, 1, 99999);
    if (is_array($modelos))
        if ($id_marca == 0)
            $nombreCategoria = "Otras marcas";
        else
            $nombreCategoria = ENMarca::getById($id_marca)->getNombre();
}

$filtro = getGet("busqueda");
if ($filtro != "" && count($modelos) == 0)
{
    $modelos = ENModelo::get($filtro, 1, 99999);
    $nombreCategoria = "Búsqueda: \"$filtro\"";
}

/*$modelos = ENModelo::get();
for ($i = 0; $i < 5; $i++)
    $modelos = array_merge($modelos, $modelos);*/

?>
    <h3><?php echo $nombreCategoria; ?></h3>
    <div id="externo">
        <div id="interno" style="text-align: center;">
            <?php
            foreach ($modelos as $i)
            {
                $thumbs = getThumbs($i->getFoto());
                echo "<div class=\"modelo\" onclick=\"cargarModelo(".$i->getId().");\">";
                if ($admin)
                    echo "<a style=\"position: absolute; top: 0; left: 0;\" href=\"modelo?id=".$i->getId()."\"><img src=\"css/editar.png\" alt=\"Editar\" title=\"Editar\" /></a>";
                echo "<div class=\"modelo-wrapper\">";
                echo "<img src=\"img/modelos/".$thumbs[1]."\" alt=\"\" style=\"max-height: 160px;\">";
                echo "<div class=\"modelo-titulo\"><div class=\"modelo-ref\">Ref. ".$i->getReferencia()."</div>";
                if ($activo)
                    echo "<div class=\"modelo-precio\">".str_replace('.', ',', $i->getPrecio())." €</div>";
                echo "<div class=\"modelo-nombre\">".$i->getNombre()." ".$i->getNumeracion()."</div></div></div></div>\n";
            }
            
            echo "<div class=\"modelo-pua\"></div>\n";
            echo "<div class=\"modelo-pua\"></div>\n";
            echo "<div class=\"modelo-pua\"></div>\n";
            echo "<div class=\"modelo-pua\"></div>\n";
            echo "<div class=\"modelo-pua\"></div>\n";
            echo "<div class=\"modelo-pua\"></div>\n";
            
            if (count($modelos) == 0)
                echo "<br /><br /><br /><div>En estos momentos esta categoría se encuentra vacía</div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";
            ?>
        </div>
    </div>
    <div id="myModal" class="reveal-modal">
        <div id="modelo-modal"></div>
        <img id="modelo-loading" src="css/loading.gif" alt="" />
        <a class="close-reveal-modal">&nbsp;</a>
    </div>
        
<?php baseInferior(); ?>
