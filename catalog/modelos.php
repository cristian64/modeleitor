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
    $modelos = ENModelo::getAdmin();
    foreach ($modelos as $i)
    {
        $thumbs = getThumbs($i->getFoto());
        echo "<tr class=\"fila\" onmouseout=\"ocultarImagenRaton();\" onmouseover=\"mostrarImagenRaton('img/modelos/".$thumbs[1]."')\">";
        echo "<td class=\"centrada\">".rellenar($i->getId(), '0', 6)."</td>";
        echo "<td class=\"centrada\">".htmlspecialchars($i->getReferencia())."</td>";
        echo "<td>".htmlspecialchars($i->getNombre())."</td>";
        echo "<td class=\"centrada\">".str_replace('.', ',', $i->getPrecio())."</td>";
        echo "<td class=\"centrada\">".$i->getNumeracion()."</td>";
        echo "<td class=\"centrada\">".htmlspecialchars($i->getFabricanteStr())."</td>";
        echo "<td class=\"centrada\">".htmlspecialchars($i->getMarcaStr())."</td>";
        echo "<td class=\"centrada\">".$i->getPrioridad()."</td>";
        
        echo "<td class=\"centrada\"><div>";
        echo "<a href=\"modelo?id=".$i->getId()."\"><img src=\"css/editar.png\" alt=\"Editar\" title=\"Editar\" /></a> ";
        
        echo "<a onclick=\"$('#form-eliminar input[name=id]').val(".$i->getId()."); $('#dialogo-eliminar').dialog('open');\"><img src=\"css/papelera.png\" alt=\"Eliminar\" title=\"Eliminar\" /></a>\n";
        
        echo "</div></td>";
        echo "</tr>";
    }
?>
    </table>

<div id="dialogo-eliminar" title="¿Eliminar modelo?">
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
