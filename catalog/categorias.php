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

baseSuperior("Categorías");

function imprimirTabs($nivel)
{
    if ($nivel > 0)
        return "<span style=\"visibility: hidden;\">".rellenar("", "─", ($nivel - 1) * 18)."</span>└".rellenar("", "─", 10);
    return "";
}

function imprimirCategoria($categoria, $nivel)
{
    $subcategorias = ENCategoria::getByPadre($categoria->getId(), true);
    echo "<tr class=\"fila\">";
    echo "<td class=\"centrada\">".rellenar($categoria->getId(), '0', 6)."</td><td>".imprimirTabs($nivel)." ".htmlspecialchars($categoria->getNombre())."</td><td class=\"centrada\">".($categoria->getMostrar() == 1 ? "sí" : "no")."</td><td class=\"centrada\">".$categoria->getZindex()."</td><td class=\"centrada\"><div>";
    echo "<a onclick=\"$('#form-anadir input[name=id_padre]').val(".$categoria->getId()."); $('#dialogo-anadir').dialog('open');\"><img src=\"css/anadir.png\" alt=\"Añadir\" title=\"Añadir\" /></a> ";
    
    if ($categoria->getId() > 0)
    {
        echo "<a onclick=\"";
        echo "$('#form-editar input[name=id]').val(".$categoria->getId().");";
        echo "$('#form-editar input[name=nombre]').val('".htmlspecialchars($categoria->getNombre())."');";
        echo "$('#form-editar input[name=descripcion]').val('".htmlspecialchars($categoria->getDescripcion())."');";
        if ($categoria->getMostrar())
            echo "$('#form-editar input[name=mostrar]').attr('checked', true);";
        else
            echo "$('#form-editar input[name=mostrar]').attr('checked', false);";
        echo "$('#form-editar input[name=zindex]').val(".$categoria->getZindex().");";
        echo "$('#dialogo-editar').dialog('open');\"><img src=\"css/editar.png\" alt=\"Editar\" title=\"Editar\"/></a> ";
        
        echo "<a onclick=\"$('#form-eliminar input[name=id]').val(".$categoria->getId()."); $('#dialogo-eliminar').dialog('open');\"><img src=\"css/papelera.png\" alt=\"Eliminar\" title=\"Eliminar\" /></a>\n";
    }
    else
    {
        /*echo "<a class=\"freshbutton-disabled\">Editar</a> ";
        echo "<a class=\"freshbutton-disabled\">Eliminar</a>\n";*/
    }
    echo "</div></td></tr>";
    
    
    foreach ($subcategorias as $i)
    {
        imprimirCategoria($i, $nivel + 1);
    }
}

?>
<h3>Categorías</h3>
<div>
    <table>
        <tr class="cabecera">
            <td>ID</td>
            <td class="estirar">Nombre</td>
            <td>Mostrar</td>
            <td>Orden</td>
            <td></td>
        </tr>
<?php
    $categoria = new ENCategoria();
    $categoria->setNombre("Raíz");
    
    imprimirCategoria($categoria, 0);
?>
    </table>

<div id="dialogo-eliminar" title="¿Eliminar categoría?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Se eliminará la categoría y todas las subcategorías.
    Los modelos que pertenezcan a esta categoría se quedarán sin categoría. ¿Continuar?</p>
    <form id="form-eliminar" method="POST" action="operarcategoria">
        <div><input type="hidden" name="id" value="" /></div>
        <div><input type="hidden" name="op" value="eliminar" /></div>
    </form>
</div>

<div id="dialogo-anadir" title="Añadir categoría">
    <form id="form-anadir" method="POST" action="operarcategoria">
    <div><input type="hidden" name="op" value="anadir" /></div>
    <table class="guapo-form">
        <tr><td class="guapo-label">ID de la categoría padre</td><td class="guapo-input"><input type="text" name="id_padre" value="" readonly="readonly" /></td></tr>
        <tr><td class="guapo-label">Nombre</td><td class="guapo-input"><input type="text" name="nombre" value="" /></td></tr>
        <tr><td class="guapo-label">Descripción</td><td class="guapo-input"><input type="text" name="descripcion" value="" /></td></tr>
        <tr><td class="guapo-label">Mostra en el menú</td><td class="guapo-input"><input type="checkbox" value="yes" name="mostrar" checked="checked" /></td></tr>
        <tr><td class="guapo-label">Z-Index</td><td class="guapo-input"><input type="text" name="zindex" value="0" /></td></tr>
    </table>
    </form>
</div>

<div id="dialogo-editar" title="Editar categoría">
    <form id="form-editar" method="POST" action="operarcategoria">
    <div><input type="hidden" name="op" value="editar" /></div>
    <table class="guapo-form">
        <tr><td class="guapo-label">ID</td><td class="guapo-input"><input type="text" name="id" value="" readonly="readonly" /></td></tr>
        <tr><td class="guapo-label">Nombre</td><td class="guapo-input"><input type="text" name="nombre" value="" /></td></tr>
        <tr><td class="guapo-label">Descripción</td><td class="guapo-input"><input type="text" name="descripcion" value="" /></td></tr>
        <tr><td class="guapo-label">Mostra en el menú</td><td class="guapo-input"><input type="checkbox" value="yes" name="mostrar" checked="checked" /></td></tr>
        <tr><td class="guapo-label">Z-Index</td><td class="guapo-input"><input type="text" name="zindex" value="0" /></td></tr>
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
