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

baseSuperior("Fabricantes");

?>
<div style="float: right; padding-top: 20px;"><a class="freshbutton-green" onclick="$('#dialogo-anadir').dialog('open');">Añadir</a></div>
<h3>Fabricantes</h3>
<div>
    <table>
        <tr class="cabecera">
            <td>ID</td>
            <td class="estirar">Nombre</td>
            <td>Email</td>
            <td>Teléfono</td>
            <td>Descripción</td>
            <td></td>
        </tr>
<?php
    $fabricantes = ENFabricante::get();
    foreach ($fabricantes as $i)
    {
        echo "<tr class=\"fila\">";
        echo "<td class=\"centrada\">".rellenar($i->getId(), '0', 6)."</td>";
        echo "<td>".$i->getNombre()."</td>";
        echo "<td class=\"centrada\">".$i->getEmail()."</td>";
        echo "<td class=\"centrada\">".$i->getTelefono()."</td>";
        echo "<td>".$i->getDescripcion()."</td>";
        
        echo "<td class=\"centrada\"><div>";
        echo "<a class=\"freshbutton-blue\" onclick=\"";
        echo "$('#form-editar input[name=id]').val(".$i->getId().");";
        echo "$('#form-editar input[name=nombre]').val('".filtrarComillas($i->getNombre())."');";
        echo "$('#form-editar input[name=email]').val('".filtrarComillas($i->getEmail())."');";
        echo "$('#form-editar input[name=telefono]').val('".filtrarComillas($i->getTelefono())."');";
        echo "$('#form-editar input[name=descripcion]').val('".filtrarComillas($i->getDescripcion())."');";
        echo "$('#dialogo-editar').dialog('open');\">Editar</a> ";
        
        echo "<a class=\"freshbutton-red\" onclick=\"$('#form-eliminar input[name=id]').val(".$i->getId()."); $('#dialogo-eliminar').dialog('open');\">Eliminar</a>\n";
        
        echo "</div></td>";
        echo "</tr>";
    }
?>
    </table>

<div id="dialogo-eliminar" title="¿Eliminar fabricante?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Se eliminará el fabricante. Los modelos que pertenezcan a este fabricante se quedarán sin fabricante. ¿Continuar?</p>
    <form id="form-eliminar" method="POST" action="operarfabricante">
        <div><input type="hidden" name="id" value="" /></div>
        <div><input type="hidden" name="op" value="eliminar" /></div>
    </form>
</div>

<div id="dialogo-anadir" title="Añadir marca">
    <form id="form-anadir" method="POST" action="operarfabricante">
    <div><input type="hidden" name="op" value="anadir" /></div>
    <table class="guapo-form">
        <tr><td class="guapo-label">Nombre</td><td class="guapo-input"><input type="text" name="nombre" value="" /></td></tr>
        <tr><td class="guapo-label">E-mail</td><td class="guapo-input"><input type="text" name="email" value="" /></td></tr>
        <tr><td class="guapo-label">Teléfono</td><td class="guapo-input"><input type="text" name="telefono" value="" /></td></tr>
        <tr><td class="guapo-label">Descripción</td><td class="guapo-input"><input type="text" name="descripcion" value="" /></td></tr>
    </table>
    </form>
</div>

<div id="dialogo-editar" title="Editar marca">
    <form id="form-editar" method="POST" action="operarfabricante">
    <div><input type="hidden" name="op" value="editar" /></div>
    <table class="guapo-form">
        <tr><td class="guapo-label">ID</td><td class="guapo-input"><input type="text" name="id" value="" readonly="readonly" /></td></tr>
        <tr><td class="guapo-label">Nombre</td><td class="guapo-input"><input type="text" name="nombre" value="" /></td></tr>
        <tr><td class="guapo-label">E-mail</td><td class="guapo-input"><input type="text" name="email" value="" /></td></tr>
        <tr><td class="guapo-label">Teléfono</td><td class="guapo-input"><input type="text" name="telefono" value="" /></td></tr>
        <tr><td class="guapo-label">Descripción</td><td class="guapo-input"><input type="text" name="descripcion" value="" /></td></tr>
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
