<?php

include_once "base.php";

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
