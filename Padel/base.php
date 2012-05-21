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
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Club Padel Matola - <?php echo $titulo; ?></title>
        <link type="text/css" rel="stylesheet" href="css/freshbutton.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/cupertino/jquery-ui-1.8.20.custom.css" media="screen" />
        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="icon" type="image/png" href="favicon.png" />
       <link type="text/css" rel="stylesheet" type="text/css" href="css/sliderman.css" />
       <script type="text/javascript" src="js/sliderman.1.3.7.js"></script>
        <script src="js/formularios.js" type="text/javascript"></script>
        <script src="js/cookies.js" type="text/javascript"></script>
        <script src="http://maps.google.com/maps/api/js?sensor=false&amp;language=es" type="text/javascript"></script>
        <script src="js/mapa.js" type="text/javascript"></script>
        <script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div id="contenedor">
            <div id="cabecera">
                <div id="titulo">
                    <h1><a href="index.php">Club Padel <span class="otrocolor">Matola</span></a></h1>
                </div>
                
                <div id="navegacion">
                    <a href="index.php">Inicio</a><a href="fotos.php">Fotos</a><a href="mapa.php">Dirección y mapa</a><a href="contacto.php" class="ultimo">Contacto</a>
                </div>
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
                    <a href="reservas.php" class="freshbutton-blue">Mis reservas</a>
                    <a href="usuario.php" class="freshbutton-blue">Mis datos personales</a>
<?php
if ($usuario->getAdmin())
{
?>
                    <a href="usuarios.php" class="freshbutton-purple">Usuarios</a>
<?php
}
?>
                    <a href="cerrarsesion.php" class="freshbutton-red">Cerrar sesión</a>
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