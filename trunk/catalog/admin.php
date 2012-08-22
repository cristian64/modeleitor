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

baseSuperior("Administración");
?>
<h3>Panel de Administración</h3>
<div id="externo"><div id="interno">
        <div id="admin">
            <a class="freshbutton-big" href="categorias">Categorías</a>
            <a class="freshbutton-big" href="fabricantes">Fabricantes</a>
            <a class="freshbutton-big" href="marcas">Marcas</a>
            <a class="freshbutton-big" href="modelos">Modelos</a>
            <a class="freshbutton-big" href="usuarios">Usuarios</a>
        </div>
</div></div>
        
<?php baseInferior(); ?>
