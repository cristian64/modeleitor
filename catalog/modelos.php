<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
?>

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

$filtro = getGet("filtro");
$oferta = getGet("oferta");
$descatalogado = getGet("descatalogado");
$orden = getGet("orden");

baseSuperior("Modelos");

?>
<div id="capaimagenraton" style="display: none; position: absolute; z-index: 10;"><img id="imagenraton" src="img/no_disponible.png" alt="" style="border: 1px solid #000;"/></div>
<script type="text/javascript">
function mostrarImagenRaton(ruta)
{
    document.getElementById("imagenraton").src = ruta;
    document.getElementById("capaimagenraton").style.display = "block";
}

function ocultarImagenRaton()
{
    document.getElementById("capaimagenraton").style.display = "none";
}

function registrarCoordenadas(event)
{
    document.getElementById("capaimagenraton").style.top = (30 + event.clientY)+document.documentElement.scrollTop + "px";
    document.getElementById("capaimagenraton").style.left = (event.clientX+30)+document.documentElement.scrollLeft + "px";
    //document.getElementById("capaimagenraton").style.left = (event.clientX-30-document.getElementById("imagenraton").width)+document.documentElement.scrollLeft + "px";
}
</script>

<div style="float: right; padding-bottom: 10px;">
    <form action="modelos" method="get">
        <input type="text" name="filtro" value="<?php echo $filtro; ?>" />
        <select name="oferta">
            <option value="" <?php if ($oferta == "") echo "selected=\"selected\""; ?>>Todos</option>
            <option value="1" <?php if ($oferta == "1") echo "selected=\"selected\""; ?>>En oferta</option>
            <option value="0" <?php if ($oferta == "0") echo "selected=\"selected\""; ?>>Sin oferta</option>
        </select>
        <select name="descatalogado">
            <option value="" <?php if ($descatalogado == "") echo "selected=\"selected\""; ?>>Todos</option>
            <option value="1" <?php if ($descatalogado == "1") echo "selected=\"selected\""; ?>>Descatalogados</option>
            <option value="0" <?php if ($descatalogado == "0") echo "selected=\"selected\""; ?>>Sin descatalogar</option>
        </select>
        <select name="orden">
            <option value="modelos.id desc" <?php if ($orden == "modelos.id desc") echo "selected=\"selected\""; ?>>ID (de mayor a menor)</option>
            <option value="modelos.id asc" <?php if ($orden == "modelos.id asc") echo "selected=\"selected\""; ?>>ID (de menor a mayor)</option>
            <option value="modelos.referencia asc" <?php if ($orden == "modelos.referencia asc") echo "selected=\"selected\""; ?>>Referencia (del 0 a la Z)</option>
            <option value="modelos.referencia desc" <?php if ($orden == "modelos.referencia desc") echo "selected=\"selected\""; ?>>Referencia (de la Z al 0)</option>
            <option value="modelos.nombre asc" <?php if ($orden == "modelos.nombre asc") echo "selected=\"selected\""; ?>>Nombre (de la A a la Z)</option>
            <option value="modelos.nombre desc" <?php if ($orden == "modelos.nombre desc") echo "selected=\"selected\""; ?>>Nombre (de la Z a la A)</option>
            <option value="modelos.marca asc" <?php if ($orden == "modelos.marca asc") echo "selected=\"selected\""; ?>>Marca (de la A a la Z)</option>
            <option value="modelos.marca desc" <?php if ($orden == "modelos.marca desc") echo "selected=\"selected\""; ?>>Marca (de la Z a la A)</option>
            <option value="modelos.precio desc" <?php if ($orden == "modelos.precio desc") echo "selected=\"selected\""; ?>>Precio (de mayor a menor)</option>
            <option value="modelos.precio asc" <?php if ($orden == "modelos.precio asc") echo "selected=\"selected\""; ?>>Precio (de menor a mayor)</option>
            <option value="modelos.prioridad desc" <?php if ($orden == "modelos.prioridad desc") echo "selected=\"selected\""; ?>>Prioridad (de mayor a menor)</option>
            <option value="modelos.prioridad asc" <?php if ($orden == "modelos.prioridad asc") echo "selected=\"selected\""; ?>>Prioridad (de menor a mayor)</option>
        </select>
        <input type="submit" value="Filtrar" />
    </form>
</div>
<div style="clear: both;"></div>
<div style="float: right; padding-top: 20px;"><a href="nuevomodelo"><img src="css/anadir.png" alt="Añadir" title="Añadir" /></a></div>
<h3>Modelos</h3>
<div>
    <table onmousemove="registrarCoordenadas(event);">    
        <tr class="cabecera">
            <td>ID</td>
            <td>Referencia</td>
            <td class="estirar">Nombre</td>
            <td>Precio</td>
            <td>Numeración</td>
            <td>Fabricante</td>
            <td>Marca</td>
            <td>Prioridad</td>
            <td></td>
        </tr>
<?php
    $modelos = ENModelo::getPro($filtro, 0, 0, 0, $oferta == "" ? null : $oferta, $descatalogado == "" ? null : $descatalogado, $orden == "" ? "modelos.id desc" : $orden, 1, 999999);
    foreach ($modelos as $i)
    {
        $thumbs = getThumbs($i->getFoto());
        echo "<tr class=\"fila\" onmouseout=\"ocultarImagenRaton();\" onmouseover=\"mostrarImagenRaton('img/modelos/".$thumbs[1]."')\">";
        echo "<td class=\"centrada\">".rellenar($i->getId(), '0', 6)."</td>";
        echo "<td class=\"centrada\">".htmlspecialchars($i->getReferencia())."</td>";
        $ofertaStr = $i->getOferta() == 1 ? " <div class=\"emblema-oferta\">OFERTA</div>" : "";
        $descatalogadoStr = $i->getDescatalogado() == 1 ? " <div class=\"emblema-descatalogado\">DESCATALOGADO</div>" : "";
        echo "<td style=\"white-space: normal;\">".htmlspecialchars($i->getNombre()).$ofertaStr.$descatalogadoStr."</td>";
        echo "<td class=\"centrada\">".str_replace('.', ',', $i->getPrecio())."</td>";
        echo "<td class=\"centrada\">".$i->getNumeracion()."</td>";
        echo "<td class=\"centrada\">".htmlspecialchars($i->getFabricante())."</td>";
        echo "<td class=\"centrada\">".htmlspecialchars($i->getMarca())."</td>";
        echo "<td class=\"centrada\">".$i->getPrioridad()."</td>";
        
        echo "<td class=\"centrada\"><div>";
        echo "<a href=\"modelo?id=".$i->getId()."\"><img src=\"css/editar.png\" alt=\"Editar\" title=\"Editar\" /></a> ";
        
        echo "<a onclick=\"$('#form-eliminar input[name=id]').val(".$i->getId()."); $('#dialogo-eliminar').dialog('open');\"><img src=\"css/papelera.png\" alt=\"Eliminar\" title=\"Eliminar\" /></a>\n";
        
        echo "</div></td>";
        echo "</tr>";
    }
    
    if (count($modelos) == 0)
        echo "<tr class=\"fila\"><td colspan=\"9\" class=\"centrada\"><br /><br />No se han encontrado modelos<br /><br /><br /></td></tr>";
?>
    </table>

<div id="dialogo-eliminar" title="¿Eliminar modelo?" style="display: none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Se eliminará el modelo definitivamente. ¿Continuar?</p>
    <form id="form-eliminar" method="POST" action="operarmodelo">
        <div><input type="hidden" name="id" value="" /></div>
        <div><input type="hidden" name="op" value="eliminar" /></div>
    </form>
</div>

<script type="text/javascript">
$(window).load(function() {
    $("#dialogo-eliminar").dialog({
        resizable: false,
        autoOpen: false,
        width: 'auto',
        height: 'auto',
        modal: true,
        buttons: {
            "Eliminar": function() {
                $("#form-eliminar").submit();
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
});
</script>

</div>
        
<?php baseInferior(); ?>

<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';
?>
