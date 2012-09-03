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
            <td>Activo</td>
            <td></td>
        </tr>
<?php
    $usuarios = ENUsuario::get();
    foreach ($usuarios as $i)
    {
        echo "<tr class=\"fila\">";
        echo "<td class=\"centrada\">".rellenar($i->getId(), '0', 6)."</td>";
        echo "<td>".htmlspecialchars($i->getNombre())."</td>";
        echo "<td class=\"centrada\">".$i->getEmail()."</td>";
        echo "<td class=\"centrada\">".htmlspecialchars($i->getTelefono())."</td>";
        echo "<td class=\"centrada\">".htmlspecialchars($i->getCif())."</td>";
        echo "<td class=\"centrada\">".($i->getActivo() == 1 ? "sí" : "no")."</td>";
        echo "<td></td>";
        echo "</tr>";
    }
?>
    </table>

</div>
        
<?php baseInferior(); ?>
