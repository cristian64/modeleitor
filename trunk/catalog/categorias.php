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

baseSuperior("Categorías");

function imprimirCategoria($categoria)
{
    $subcategorias = ENCategoria::getByPadre($categoria->getId(), true);
    echo "<div class=\"categoriapadre\"><div class=\"categoria\">▶ ".$categoria->getNombre()." [ID: ".$categoria->getId()."] <span>";
    echo "<a class=\"freshbutton-green\" onclick=\"$('#form-anadir input[name=id_padre]').val(".$categoria->getId()."); $('#dialogo-anadir').dialog('open');\">Añadir</a> ";
    
    if ($categoria->getId() > 0)
    {
        echo "<a class=\"freshbutton-blue\" onclick=\"";
        echo "$('#form-editar input[name=id]').val(".$categoria->getId().");";
        echo "$('#form-editar input[name=nombre]').val('".$categoria->getNombre()."');";
        echo "$('#form-editar input[name=descripcion]').val('".$categoria->getDescripcion()."');";
        if ($categoria->getMostrar())
            echo "$('#form-editar input[name=mostrar]').attr('checked', true);";
        else
            echo "$('#form-editar input[name=mostrar]').attr('checked', false);";
        echo "$('#form-editar input[name=zindex]').val(".$categoria->getZindex().");";
        echo "$('#dialogo-editar').dialog('open');\">Editar</a> ";
        
        echo "<a class=\"freshbutton-red\" onclick=\"$('#form-eliminar input[name=id]').val(".$categoria->getId()."); $('#dialogo-eliminar').dialog('open');\">Eliminar</a></span></div>\n";
    }
    else
    {
        echo "<a class=\"freshbutton-disabled\">Editar</a> ";
        echo "<a class=\"freshbutton-disabled\">Eliminar</a></span></div>\n";
    }
    
    
    foreach ($subcategorias as $i)
    {
        imprimirCategoria($i);
    }
    echo "</div>\n";
}

?>
<h3>Panel de Administración: Categorías</h3>
<div>
<?php
    $categoria = new ENCategoria();
    $categoria->setNombre("Raíz");
    imprimirCategoria($categoria);
?>

<div id="dialogo-eliminar" title="¿Eliminar categoría?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Se eliminará la categoría y todas las subcategorías.
    Los modelos que pertenezcan a esta categoría se quedarán sin categoría. ¿Continuar?</p>
    <form id="form-eliminar" method="POST" action="operarcategoria">
        <input type="hidden" name="id" value="" />
        <input type="hidden" name="op" value="eliminar" />
    </form>
</div>

<div id="dialogo-anadir" title="Añadir categoría">
    <form id="form-anadir" method="POST" action="operarcategoria">
    <table class="guapo-form">
        <input type="hidden" name="op" value="anadir" />
        <tr><td class="guapo-label">ID de la categoría padre</td><td class="guapo-input"><input type="text" name="id_padre" value="" readonly="readonly" /></td>
        <tr><td class="guapo-label">Nombre</td><td class="guapo-input"><input type="text" name="nombre" value="" /></td>
        <tr><td class="guapo-label">Descripción</td><td class="guapo-input"><input type="text" name="descripcion" value="" /></td>
        <tr><td class="guapo-label">Mostra en el menú</td><td class="guapo-input"><input type="checkbox" value="yes" name="mostrar" checked="checked" /></td>
        <tr><td class="guapo-label">Z-Index</td><td class="guapo-input"><input type="text" name="zindex" value="0" /></td>
    </table>
    </form>
</div>

<div id="dialogo-editar" title="Editar categoría">
    <form id="form-editar" method="POST" action="operarcategoria">
    <table class="guapo-form">
        <input type="hidden" name="id" value="" />
        <input type="hidden" name="op" value="editar" />
        <tr><td class="guapo-label">Nombre</td><td class="guapo-input"><input type="text" name="nombre" value="" /></td>
        <tr><td class="guapo-label">Descripción</td><td class="guapo-input"><input type="text" name="descripcion" value="" /></td>
        <tr><td class="guapo-label">Mostra en el menú</td><td class="guapo-input"><input type="checkbox" value="yes" name="mostrar" checked="checked" /></td>
        <tr><td class="guapo-label">Z-Index</td><td class="guapo-input"><input type="text" name="zindex" value="0" /></td>
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
