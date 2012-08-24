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

baseSuperior("Modelos");

?>
<div id="capaimagenraton" style="display: none; position: absolute; z-index: 10;"><img id="imagenraton" src="img/no_disponible.png" alt="" style="border: 1px solid #000;"/></div>
<script type="text/javascript">
function mostrarImagenRaton(ruta)
{
    document.getElementById("imagenraton").src = ruta;
    document.getElementById("capaimagenraton").style.display = "block";
    document.getElementById("capaimagenraton").style.top = (posY) + "px";
    document.getElementById("capaimagenraton").style.left = (posX-30-document.getElementById("imagenraton").width) + "px";
}

function ocultarImagenRaton()
{
    document.getElementById("capaimagenraton").style.display = "none";
}

function registrarCoordenadas(event)
{
    document.getElementById("capaimagenraton").style.top = (event.clientY)+document.documentElement.scrollTop + "px";
    document.getElementById("capaimagenraton").style.left = (event.clientX-30-document.getElementById("imagenraton").width)+document.documentElement.scrollLeft + "px";
}
</script>
<div style="float: right; padding-top: 20px;"><a class="freshbutton-green" onclick="$('#dialogo-anadir').dialog('open');">Añadir</a></div>
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
    $modelos = ENModelo::get();
    foreach ($modelos as $i)
    {
        $thumbs = getThumbs($i->getFoto());
        echo "<tr class=\"fila\" onmouseout=\"ocultarImagenRaton();\" onmouseover=\"mostrarImagenRaton('img/modelos/".$thumbs[3]."')\">";
        echo "<td class=\"centrada\">".rellenar($i->getId(), '0', 6)."</td>";
        echo "<td class=\"centrada\">".$i->getReferencia()."</td>";
        echo "<td>".$i->getNombre()."</td>";
        echo "<td class=\"centrada\">".$i->getPrecio()."</td>";
        echo "<td class=\"centrada\">".$i->getNumeracion()."</td>";
        echo "<td class=\"centrada\">".$i->getFabricanteStr()."</td>";
        echo "<td class=\"centrada\">".$i->getMarcaStr()."</td>";
        echo "<td class=\"centrada\">".$i->getPrioridad()."</td>";
        
        echo "<td class=\"centrada\"><div>";
        echo "<a class=\"freshbutton-blue\" onclick=\"";
        echo "$('#form-editar input[name=id]').val(".$i->getId().");";
        echo "$('#form-editar input[name=nombre]').val('".filtrarComillas($i->getNombre())."');";
        echo "$('#dialogo-editar').dialog('open');\">Editar</a> ";
        
        echo "<a class=\"freshbutton-red\" onclick=\"$('#form-eliminar input[name=id]').val(".$i->getId()."); $('#dialogo-eliminar').dialog('open');\">Eliminar</a>\n";
        
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
