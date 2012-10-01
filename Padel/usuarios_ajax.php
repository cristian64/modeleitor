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

$filtro = filtrarCadena(getPost("filtro"));

$usuarios = ENUsuario::obtenerTodos(0, 999999, $filtro);
?>
            <table class="guapo-tabla" style="border-collapse: collapse; text-align: center;">
                <!--<tr>
                    <th>E-mail</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                </tr>-->
<?php
foreach ($usuarios as $u)
{
    echo "<tr class=\"linea\" onclick=\"$('#email').val('".$u->getEmail()."'); $('#dialogo-seleccionar').dialog('close');\">\n";
    echo "<td>".ellipsis($u->getEmail(), 30)."</td>\n";
    echo "<td>".ellipsis($u->getNombre(), 30)."</td>\n";
    echo "<td>".ellipsis($u->getDNI(), 30)."</td>\n";
    echo "<td>".ellipsis($u->getTelefono(), 30)."</td>\n";
    echo "</tr>\n";
}

if (count($usuarios) == 0)
{
    echo "<tr><td colspan=\"4\"><br /><br /><br />No se han encontrado usuarios<br /><br /><br /><br /></td>";
}
?>
            </table>

