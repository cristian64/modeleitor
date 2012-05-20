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
$cuenta = ENUsuario::contar();
$pagina = $pagina <= 1 ? 1 : $pagina;
$maxpagina = ceil($cuenta / $CANTIDAD_USUARIOS);
$pagina = $pagina > $maxpagina ? $maxpagina : $pagina;

$usuarios = ENUsuario::obtenerTodos($pagina - 1, $CANTIDAD_USUARIOS, $filtro);

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
            <div><br />Mostrando <strong><?php echo count($usuarios); ?> usuarios</strong> de un total de <strong><?php echo $cuenta; ?> usuarios</strong>.</div>
            <div id="paginacion">
            <?php
                if ($pagina > 1)
                    echo "<a href=\"usuarios.php?pagina=".($pagina - 1)."\" class=\"freshbutton-blue\">Anterior</a>\n";
                else
                    echo "<a href=\"\" class=\"freshbutton-disabled\">Anterior</a>\n";
                
                echo "<a href=\"\" class=\"freshbutton-blue\">Página $pagina de $maxpagina</a>\n";
                
                if ($pagina < $maxpagina)
                    echo "<a href=\"usuarios.php?pagina=".($pagina + 1)."\" class=\"freshbutton-blue\">Siguiente</a>\n";
                else
                    echo "<a href=\"\" class=\"freshbutton-disabled\">Siguiente</a>\n";
            ?></div>
        </div>
<?php
baseInferior();
?>
