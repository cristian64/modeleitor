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

baseSuperior("Usuarios");

?>
<h3>Usuarios</h3>
<div>
    <table>
        <tr class="cabecera">
            <td>ID</td>
            <td class="estirar">Nombre</td>
            <td>Email</td>
            <td>Teléfono</td>
            <td>CIF/NIF</td>
            <td>Fecha de registro</td>
            <td></td>
        </tr>
<?php
    $usuarios = ENUsuario::get();
	$usuarios = array_reverse($usuarios);
    foreach ($usuarios as $i)
    {
        if ($i->getActivo())
            echo "<tr class=\"fila\">";
        else
            echo "<tr class=\"fila fila-roja\">";
        echo "<td class=\"centrada\">".rellenar($i->getId(), '0', 6)."</td>";
        echo "<td>".htmlspecialchars($i->getNombre())." ".htmlspecialchars($i->getApellidos())."</td>";
        echo "<td class=\"centrada\">".$i->getEmail()."</td>";
        echo "<td class=\"centrada\">".htmlspecialchars($i->getTelefono())."</td>";
        echo "<td class=\"centrada\">".htmlspecialchars($i->getCif())."</td>";
        echo "<td class=\"centrada\">".$i->getFechaRegistro()->format('d/m/Y H:i:s')."</td>";
        echo "<td class=\"centrada\"><div>";
        echo "<a onclick=\"";
        echo "$('#form-editar input[name=id]').val(".$i->getId().");";
        echo "$('#form-editar input[name=email]').val('".htmlspecialchars(secure($i->getEmail()))."');";
        echo "$('#form-editar input[name=nombre]').val('".htmlspecialchars(secure($i->getNombre()))."');";
        echo "$('#form-editar input[name=apellidos]').val('".htmlspecialchars(secure($i->getApellidos()))."');";
        echo "$('#form-editar input[name=telefono]').val('".htmlspecialchars(secure($i->getTelefono()))."');";
        echo "$('#form-editar input[name=contrasena]').val('');";
        echo "$('#form-editar input[name=contrasena2]').val('');";
        echo "$('#form-editar input[name=direccion]').val('".htmlspecialchars(secure($i->getDireccion()))."');";
        echo "$('#form-editar input[name=cp]').val('".htmlspecialchars(secure($i->getCp()))."');";
        echo "$('#form-editar input[name=ciudad]').val('".htmlspecialchars(secure($i->getCiudad()))."');";
        echo "$('#form-editar input[name=empresa]').val('".htmlspecialchars(secure($i->getEmpresa()))."');";
        echo "$('#form-editar input[name=cif]').val('".htmlspecialchars(secure($i->getCif()))."');";
        if ($i->getActivo())
            echo "$('#form-editar input[name=activo]').attr('checked', true);";
        else
            echo "$('#form-editar input[name=activo]').attr('checked', false);";
        echo "$('#form-editar input[name=fecha_registro]').val('".$i->getFechaRegistro()->format('d/m/Y H:i:s')."');";
        echo "$('#dialogo-editar').dialog('open');\"><img src=\"css/editar.png\" alt=\"Editar\" title=\"Editar\" /></a> ";
        
        echo "<a onclick=\"$('#form-eliminar input[name=id]').val(".$i->getId()."); $('#dialogo-eliminar').dialog('open');\"><img src=\"css/papelera.png\" alt=\"Eliminar\" title=\"Eliminar\" /></a>\n";
        
        echo "</div></td>";
        echo "</tr>";
    }
?>
    </table>

<div id="dialogo-eliminar" title="¿Eliminar usuario?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Se eliminará el usuario. ¿Continuar?</p>
    <form id="form-eliminar" method="POST" action="operarusuario">
        <div><input type="hidden" name="id" value="" /></div>
        <div><input type="hidden" name="op" value="eliminar" /></div>
    </form>
</div>

<div id="dialogo-editar" title="Editar usuario">
    <form id="form-editar" method="POST" action="operarusuario">
    <div><input type="hidden" name="op" value="editar" /></div>
    <table class="guapo-form">
        <tr><td class="guapo-label">ID</td><td class="guapo-input"><input type="text" name="id" value="" readonly="readonly" /></td></tr>
        <tr><td class="guapo-label">E-mail</td><td class="guapo-input"><input type="text" name="email" value="" readonly="readonly" /></td></tr>
        <tr><td class="guapo-label">Nombre</td><td class="guapo-input"><input type="text" name="nombre" value="" /></td></tr>
        <tr><td class="guapo-label">Apellidos</td><td class="guapo-input"><input type="text" name="apellidos" value="" /></td></tr>
        <tr><td class="guapo-label">Contraseña</td><td class="guapo-input"><input type="password" name="contrasena" value="" /></td></tr>
        <tr><td class="guapo-label">Confirmación de contraseña</td><td class="guapo-input"><input type="password" name="contrasena2" value="" /></td></tr>
        <tr><td class="guapo-label">Teléfono</td><td class="guapo-input"><input type="text" name="telefono" value="" /></td></tr>
        <tr><td class="guapo-label">Dirección</td><td class="guapo-input"><input type="text" name="direccion" value="" /></td></tr>
        <tr><td class="guapo-label">Código postal</td><td class="guapo-input"><input type="text" name="cp" value="" /></td></tr>
        <tr><td class="guapo-label">Ciudad <small>(localidad, provincia, país)</small></td><td class="guapo-input"><input type="text" name="ciudad" value="" /></td></tr>
        <tr><td class="guapo-label">Nombre de empresa</td><td class="guapo-input"><input type="text" name="empresa" value="" /></td></tr>
        <tr><td class="guapo-label">CIF/NIF</td><td class="guapo-input"><input type="text" name="cif" value="" /></td></tr>
        <tr><td class="guapo-label">Activo</td><td class="guapo-input"><input type="checkbox" value="yes" name="activo" /></td></tr>
        <tr><td class="guapo-label">Fecha de registro</td><td class="guapo-input"><input type="text" name="fecha_registro" value="" readonly="readonly" /></td></tr>
    </table>
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
