<?php

include_once "base.php";

$usuario = getUsuario();
if ($usuario == null)
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.";
    header("location: index.php");
    exit();
}

if (!$usuario->getAdmin())
{
    $_SESSION["mensaje_aviso"] = "Área restringida a administradores.";
    header("location: index.php");
    exit();
}

baseSuperior("Marcas");

?>
<div style="float: right; padding-top: 20px;"><a onclick="$('#dialogo-anadir').dialog('open');"><img src="css/anadir.png" alt="Añadir" title="Añadir" /></a></div>
<h3>Marcas</h3>
<div>
    <table>
        <tr class="cabecera">
            <td>ID</td>
            <td>Logo</td>
            <td class="estirar">Nombre</td>
            <td></td>
        </tr>
<?php
    $marcas = ENMarca::get();
    foreach ($marcas as $i)
    {
        $thumbs = getThumbs($i->getLogo());
        echo "<tr class=\"fila\">";
        echo "<td class=\"centrada\">".rellenar($i->getId(), '0', 6)."</td><td class=\"centrada\"><img src=\"img/marcas/".$thumbs[1]."\" alt=\"\" /><td>".$i->getNombre()."</td><td class=\"centrada\">";
        
        echo "<div>";
        echo "<a onclick=\"";
        echo "$('#form-editar input[name=id]').val(".$i->getId().");";
        echo "$('#form-editar input[name=nombre]').val('".filtrarComillas($i->getNombre())."');";
        echo "$('#dialogo-editar').dialog('open');\"><img src=\"css/editar.png\" alt=\"Editar\" title=\"Editar\" /></a> ";
        
        echo "<a onclick=\"$('#form-eliminar input[name=id]').val(".$i->getId()."); $('#dialogo-eliminar').dialog('open');\"><img src=\"css/papelera.png\" alt=\"Eliminar\" title=\"Eliminar\" /></a>\n";
        
        echo "</div></td>";
        echo "</tr>";
    }
?>
    </table>

<div id="dialogo-eliminar" title="¿Eliminar marca?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Se eliminará la marca. Los modelos que pertenezcan a esta marca se quedarán sin marca. ¿Continuar?</p>
    <form id="form-eliminar" method="POST" action="operarmarca">
        <div><input type="hidden" name="id" value="" /></div>
        <div><input type="hidden" name="op" value="eliminar" /></div>
    </form>
</div>

<div id="dialogo-anadir" title="Añadir marca">
    <form id="form-anadir" method="POST" action="operarmarca" enctype="multipart/form-data">
    <div><input type="hidden" name="op" value="anadir" /></div>
    <table class="guapo-form">
        <tr><td class="guapo-label">Nombre</td><td class="guapo-input"><input type="text" name="nombre" value="" /></td></tr>
        <tr><td class="guapo-label">Logo</td><td class="guapo-input"><input type="file" name="logo" /></td></tr>
    </table>
    </form>
</div>

<div id="dialogo-editar" title="Editar marca">
    <form id="form-editar" method="POST" action="operarmarca" enctype="multipart/form-data">
    <div><input type="hidden" name="op" value="editar" /></div>
    <table class="guapo-form">
        <tr><td class="guapo-label">ID</td><td class="guapo-input"><input type="text" name="id" value="" readonly="readonly" /></td></tr>
        <tr><td class="guapo-label">Nombre</td><td class="guapo-input"><input type="text" name="nombre" value="" /></td></tr>
        <tr><td class="guapo-label">Logo</td><td class="guapo-input"><input type="file" name="logo" /></td></tr>
    </table>
    </form>
</div>

<script>
$(function() {
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

    $("#dialogo-anadir").dialog({
        resizable: false,
        autoOpen: false,
        width: 'auto',
        height: 'auto',
        modal: true,
        buttons: {
            "Añadir": function() {
                $("#form-anadir").submit();
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $("#dialogo-editar").dialog({
        resizable: false,
        autoOpen: false,
        width: 'auto',
        height: 'auto',
        modal: true,
        buttons: {
            "Guardar": function() {
                $("#form-editar").submit();
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
