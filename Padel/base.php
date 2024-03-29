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
        <link type="text/css" rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.23.custom.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/freshbutton.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/thickbox.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="css/responsiveslides/responsiveslides.css" media="screen" />
        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="icon" type="image/png" href="favicon.png" />
        <script src="http://maps.google.com/maps/api/js?sensor=false&amp;language=es" type="text/javascript"></script>
        <script src="js/mapa.js" type="text/javascript"></script>
        <script src="js/jquery-1.8.0.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
        <script src="js/jquery.textbox-hinter.min.js" type="text/javascript"></script>
        <script src="js/thickbox.js" type="text/javascript"></script>
        <script src="js/formularios.js" type="text/javascript"></script>
        <script src="js/cookies.js" type="text/javascript"></script>
        <script src="js/responsiveslides.min.js" type="text/javascript"></script>
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
if ($usuario->getId() == 1 || $usuario->getId() == 2 || $usuario->getId() == 25 || $usuario->getId() == 30)
{
?>
                    <a href="estadisticas.php" class="freshbutton-yellow">Estadísticas</a>
                    <a href="informe.php" class="freshbutton-yellow">Generar informe</a>
                    <a href="backup" class="freshbutton-yellow">Copia de seguridad</a>
<?php    
}

if ($usuario->getAdmin())
{
?>
                    <a href="usuarios.php" class="freshbutton-purple">Usuarios</a>
                    <a href="reservas.php" class="freshbutton-purple">Reservas</a>
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
            <div style="clear:both;"></div>
            <div id="pie">
                <div>
                    <a href="http://www.autofima.com"><img src="css/autofima.png" alt="AUTOFIMA" /></a>
                    <a href="http://www.hyundai.es"><img src="css/hyundai.png" alt="Hyundai" /></a>
                    <a href="http://www.lacaixa.es"><img src="css/lacaixa.jpg" alt="La Caixa" /></a>
                    <a href="http://www.informacion.es"><img src="css/informacion.gif" alt="Diario Información" /></a>
                    <a class="freshbutton-darkblue facebook" href="https://www.facebook.com/clubpadelmatola.mejias"><img src="css/facebook.png" alt="" title="Club Padel Matola" /> Club Padel<br />Matola</a>
                    <a class="freshbutton-darkblue facebook" href="https://www.facebook.com/cafeteria.padelmatola"><img src="css/facebook.png" alt="" title="Cafetería Padel Matola" /> Cafetería<br />Padel Matola</a>
                </div>            
            </div>
        </div>
        
    </body>
</html>
<?php
}
?>
