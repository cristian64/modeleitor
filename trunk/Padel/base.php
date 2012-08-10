<?php

require_once("minilibreria.php");

/**
 *
 * @param String $titulo Título (<title>) que tendrá la página.
 */
function baseSuperior($titulo, $mostrarmenu = true)
{
    if ($titulo == "")
        $titulo = "Inicio";
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Club Padel Matola - <?php echo $titulo; ?></title>
        <link type="text/css" rel="stylesheet" href="css/cupertino/jquery-ui-1.8.20.custom.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/freshbutton.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/thickbox.css" media="screen" />
        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="icon" type="image/png" href="favicon.png" />
        <link type="text/css" rel="stylesheet" href="css/sliderman.css" />
        <script type="text/javascript" src="js/sliderman.1.3.7.js"></script>
        <script src="http://maps.google.com/maps/api/js?sensor=false&amp;language=es" type="text/javascript"></script>
        <script src="js/mapa.js" type="text/javascript"></script>
        <script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>
        <script src="js/jquery.textbox-hinter.min.js" type="text/javascript"></script>
        <script src="js/thickbox.js" type="text/javascript"></script>
        <script src="js/formularios.js" type="text/javascript"></script>
        <script src="js/cookies.js" type="text/javascript"></script>
    </head>
    <body>
        <div id="contenedor">
            <div id="cabecera">
                <div id="titulo">
                    <h1><a href="index.php"><span>Club Padel </span><span class="otrocolor">Matola</span></a></h1>
                </div>
                
                <?php
                function navegacion()
                {
                    echo '
                    <div id="navegacion">
                        <div id="linksnavegacion">
                            <a href="index.php" id="inicionavegacion">Inicio</a><a href="mapa.php" id="mapanavegacion">Localización</a><a href="fotos.php" id="fotosnavegacion">Fotos</a><a href="contacto.php" id="contactonavegacion" class="ultimo">Contacto</a>
                        </div>
                    </div>
                    ';
                }
                
                navegacion();
                ?>
            </div>
            
            <div id="contenido">
                <div id="menu" <?php if (!$mostrarmenu) echo "style=\"display: none;\""; ?> >
<?php

$usuario = getUsuario();
if ($usuario != null)
{
?>
                    <span><?php echo $usuario->getNombre(); ?> (<strong><?php echo $usuario->getEmail(); ?></strong>)&nbsp;&nbsp;&nbsp;</span>
                    <a href="reservar.php" class="freshbutton-blue">Reservar pista</a>
                    <a href="misreservas.php" class="freshbutton-blue">Mis reservas</a>
                    <a href="usuario.php" class="freshbutton-blue">Mis datos personales</a>
                    <a href="cerrarsesion.php" class="freshbutton-red">Cerrar sesión</a>
                    <br /><br />
<?php
if ($usuario->getAdmin())
{
?>
                    <a href="estadisticas.php" class="freshbutton-purple">Estadísticas</a>
                    <a href="usuarios.php" class="freshbutton-purple">Usuarios</a>
                    <a href="reservas.php" class="freshbutton-purple">Reservas</a>
                    <a href="backup" class="freshbutton-purple">Copia de seguridad</a>
<?php
}
?>
<?php
}
else
{
?>
                    <a href="iniciarsesion.php" class="freshbutton-blue">Iniciar sesión</a> o <a href="registrarse.php">registrarse</a> para reservar pista
<?php
}
?>
                </div>
                <div id="cuerpo">
                    <?php include 'mensajes.php'; ?>
<?php
}

function baseInferior()
{
?>
                </div>

            </div>
            <div id="pie"></div>
        </div>
        
    </body>
</html>
<?php
}
?>
