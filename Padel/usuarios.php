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

$usuarios = ENUsuario::obtenerTodos($pagina, $CANTIDAD_USUARIOS, $filtro);

baseSuperior("Usuarios");
?>
        <div id="usuarios">
            <h3><span>Usuarios</span></h3>
            <table>
                <tr>
                    <td class="cabecera">Nº de usuario</td>
                    <td class="cabecera">E-mail</td>
                    <td class="cabecera">Nombre</td>
                    <td class="cabecera">DNI</td>
                    <td class="cabecera">Sexo</td>
                    <td class="cabecera">Teléfono</td>
                    <td class="cabecera">Dirección</td>
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
    echo "<td>".ellipsis($u->getDireccion(), 30)."</td>\n";
    echo "</tr>\n";
}
?>
            </table>
        </div>
<?php
baseInferior();
?>
