<?php
include_once "base.php";

$usuario = getUsuario();
if ($usuario == null)
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.";
    header("location: iniciarsesion.php");
    exit();
}

if (!$usuario->getAdmin())
{
    $_SESSION["mensaje_error"] = "Esta sección necesita privilegios de administrador";
    header("location: index.php");
    exit();
}

$filtro = filtrarCadena(getGet("filtro"));
$pagina = intval(getGet("pagina"));
$cuenta = ENUsuario::contar($filtro);
$pagina = $pagina <= 1 ? 1 : $pagina;
$maxpagina = ceil($cuenta / $CANTIDAD_USUARIOS);
$pagina = $pagina > $maxpagina ? $maxpagina : $pagina;

$usuarios = ENUsuario::obtenerTodos($pagina > 1 ? $pagina - 1 : 0, $CANTIDAD_USUARIOS, $filtro);

baseSuperior("Usuarios");
?>
        <div id="usuarios">
            <div id="busqueda">
                <form action="usuarios.php" method="get">
                    <div><input id="filtro" type="text" name="filtro" value="<?php echo $filtro; ?>" class="searchinput" title="nº de usuario, nombre, e-mail, DNI o teléfono" />&nbsp;&nbsp;&nbsp;<a onclick="$('#dialogo-anadir').dialog('open');" class="freshbutton-green">Añadir usuario</a></div>
                </form>
            </div>
            <script type="text/javascript">
                $(document).ready(
                    function() {
                        textboxHint("busqueda");
                        <?php if ($filtro != "") { ?>
                            $("#filtro").focus();
                        <?php } ?>
                    });
            </script>
            <h3><span>Usuarios</span></h3>
            <table class="guapo-tabla">
                <tr>
                    <th>Nº de usuario</th>
                    <th>E-mail</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Sexo</th>
                    <th>Teléfono</th>
                    <!-- <th class="cabecera">Dirección</th> -->
                    <th>Fecha de registro</th>
                </tr>
<?php
foreach ($usuarios as $u)
{
    $clase = "linea";
    echo "<tr class=\"$clase\" onclick=\"window.location = 'usuario.php?id=".$u->getId()."';\">\n";
    echo "<td>".rellenar($u->getId(), '0', $RELLENO)."</td>\n";
    echo "<td>".ellipsis($u->getEmail(), 30)."</td>\n";
    echo "<td>".ellipsis($u->getNombre(), 30)."</td>\n";
    echo "<td>".ellipsis($u->getDNI(), 30)."</td>\n";
    echo "<td>".$u->getSexo()."</td>\n";
    echo "<td>".ellipsis($u->getTelefono(), 30)."</td>\n";
    //echo "<td>".ellipsis($u->getDireccion(), 30)."</td>\n";
    echo "<td>".$u->getFechaRegistro()->format('d/m/Y H:i:s')."</td>\n";
    echo "</tr>\n";
}

if (count($usuarios) == 0)
{
    echo "<tr><td colspan=\"7\"><br /><br /><br />No se han encontrado usuarios<br /><br /><br /><br /></td></tr>";
}
?>
            </table>
            
            <div><br />Mostrando <strong><?php echo count($usuarios); ?> usuarios</strong> de un total de <strong><?php echo $cuenta; ?> usuarios encontrados</strong>.</div>
            <div id="paginacion">
            <?php
                $parametros = $filtro != "" ? "?filtro=$filtro&" : "?";
                if ($pagina > 1) {
                    echo "<a href=\"usuarios.php$parametros"."pagina=".(1)."\" class=\"freshbutton-lightblue\">Primera</a>\n";
                    echo "<a href=\"usuarios.php$parametros"."pagina=".($pagina - 1)."\" class=\"freshbutton-lightblue\">Anterior</a>\n";
                } else {
                    echo "<a href=\"\" class=\"freshbutton-lightdisabled\">Primera</a>\n";
                    echo "<a href=\"\" class=\"freshbutton-lightdisabled\">Anterior</a>\n";
                }
                
                echo "<a href=\"\" class=\"freshbutton-lightblue\">Página $pagina de $maxpagina</a>\n";
                
                if ($pagina < $maxpagina) {
                    echo "<a href=\"usuarios.php$parametros"."pagina=".($pagina + 1)."\" class=\"freshbutton-lightblue\">Siguiente</a>\n";
                    echo "<a href=\"usuarios.php$parametros"."pagina=".($maxpagina)."\" class=\"freshbutton-lightblue\">Última</a>\n";
                } else {
                    echo "<a href=\"\" class=\"freshbutton-lightdisabled\">Siguiente</a>\n";
                    echo "<a href=\"\" class=\"freshbutton-lightdisabled\">Última</a>\n";
                }
            ?></div>
        </div>
        
<div id="dialogo-anadir" title="Añadir usuario">
    <form id="form-anadir" method="POST" action="operarusuario">
    <div><input type="hidden" name="op" value="anadir" /></div>
    <table class="guapo-form">
        <tr><td class="guapo-label">E-mail*</td><td class="guapo-input"><input type="text" name="email" value="" /></td></tr>
        <tr><td class="guapo-label">Contraseña*</td><td class="guapo-input"><input type="password" name="contrasena" value="" /></td></tr>
        <tr><td class="guapo-label">Nombre y apellidos*</td><td class="guapo-input"><input type="text" name="nombre" value="" /></td></tr>
        <tr><td class="guapo-label">DNI</td><td class="guapo-input"><input type="text" name="dni" value="" /></td></tr>
        <tr>
            <td class="guapo-label">Sexo*</td>
            <td class="guapo-input">
                <div class="textinputfake">
                    <input type="radio" name="sexo" value="mujer" <?php if ($sexo == "mujer") echo "checked=\"checked\";" ?> /> Mujer
                    <input type="radio" name="sexo" value="hombre" <?php if ($sexo == "hombre") echo "checked=\"checked\";" ?>/> Hombre
                </div>
            </td>
        </tr>
        <tr><td class="guapo-label">Dirección</td><td class="guapo-input"><input type="text" name="direccion" value="" /></td></tr>
        <tr><td class="guapo-label">Teléfono</td><td class="guapo-input"><input type="text" name="telefono" value="" /></td></tr>
    </table>
    </form>
</div>

<script type="text/javascript">
$(window).load(function() {

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
});
</script>

<?php
baseInferior();
?>
