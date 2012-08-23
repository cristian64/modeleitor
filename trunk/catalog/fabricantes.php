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
            <td>Nombre</td>
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
        echo "<td>".$i->getId()."</td><td>".$i->getNombre()."</td><td>".$i->getEmail()."</td><td>".$i->getTelefono()."</td><td>".$i->getDescripcion()."</td><td>";
        
        echo "<div>";
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
        <input type="hidden" name="id" value="" />
        <input type="hidden" name="op" value="eliminar" />
    </form>
</div>

<div id="dialogo-anadir" title="Añadir marca">
    <form id="form-anadir" method="POST" action="operarfabricante">
    <table class="guapo-form">
        <input type="hidden" name="op" value="anadir" />
        <tr><td class="guapo-label">Nombre</td><td class="guapo-input"><input type="text" name="nombre" value="" /></td><tr/>
        <tr><td class="guapo-label">E-mail</td><td class="guapo-input"><input type="text" name="email" value="" /></td><tr/>
        <tr><td class="guapo-label">Telefono</td><td class="guapo-input"><input type="text" name="telefono" value="" /></td><tr/>
        <tr><td class="guapo-label">Descripción</td><td class="guapo-input"><input type="text" name="descripcion" value="" /></td><tr/>
    </table>
    </form>
</div>

<div id="dialogo-editar" title="Editar marca">
    <form id="form-editar" method="POST" action="operarfabricante">
    <table class="guapo-form">
        <input type="hidden" name="op" value="editar" />
        <tr><td class="guapo-label">ID</td><td class="guapo-input"><input type="text" name="id" value="" readonly="readonly" /></td><tr/>
        <tr><td class="guapo-label">Nombre</td><td class="guapo-input"><input type="text" name="nombre" value="" /></td><tr/>
        <tr><td class="guapo-label">E-mail</td><td class="guapo-input"><input type="text" name="email" value="" /></td><tr/>
        <tr><td class="guapo-label">Telefono</td><td class="guapo-input"><input type="text" name="telefono" value="" /></td><tr/>
        <tr><td class="guapo-label">Descripción</td><td class="guapo-input"><input type="text" name="descripcion" value="" /></td><tr/>
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