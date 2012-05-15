<?php

require_once("minilibreria.php");

if (!isset($_SESSION["usuario"]))
{
	// Si no hay ninguna sesión abierta, intentamos abrir una desde las cookies.
	if (isset($_COOKIE["email"]) && isset($_COOKIE["contrasena"]))
	{
		$usuario = ENUsuario::obtenerPorEmail($_COOKIE["email"]);
		if ($usuario != null)
		{
			if ($usuario->getContrasena() == $_COOKIE["contrasena"])
			{
				$_SESSION["usuario"] = serialize($usuario);
			}
		}
	}
}

/**
 *
 * @param String $titulo Título (<title>) que tendrá la página.
 */
function baseSuperior($titulo, $mostrarmenu = true)
{
	if ($titulo == "")
		$titulo = "Título";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Padel en Matola - <?php echo $titulo; ?></title>
		<link type="text/css" rel="stylesheet" href="estilo/freshbutton.css" media="screen" title="Estilo principal" />
		<script src="javascript/formularios.js" type="text/javascript"></script>
		<script src="javascript/cookies.js" type="text/javascript"></script>
	</head>
	<body>
		<div id="contenedor">
            <div id="cabecera">
                <div id="titulo">
                    <h1><a href="index.php">Padel <span class="otrocolor">en Matola</span></a></h1>
                </div>
                
                <div id="navegacion">
                    <a href="index.php">Inicio</a><a href="informacion.php">Información</a><a href="mapa.php">Mapa</a><a href="contacto.php" class="ultimo">Contacto</a>
                </div>
            </div>
            
			<div id="contenido">
                <div id="menu" <?php if (!$mostrarmenu) echo "style=\"display: none;\""; ?> >
<?php

if (isset($_SESSION["usuario"]))
{
	$usuario = unserialize($_SESSION["usuario"]);
?>
                    <span style="float: left;">Estás conectado como <?php echo $usuario->getNombre(); ?></span>
                    <a href="reservar.php" class="freshbutton-blue">Reservar pista</a>
                    <a href="reservas.php" class="freshbutton-blue">Mis reservas</a>
                    <a href="perfil.php" class="freshbutton-blue">Mis datos personales</a>
<?php
if ($usuario->getAdmin() == 1)
{
?>
                    <a href="usuarios.php" class="freshbutton-blue">Ver usuarios</a>
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
                    <div id="externo">
                        <div id="interno">
<?php
}

function baseInferior()
{
?>
                        </div>
                    </div>
				</div>

			</div>
		</div>
        
	</body>
</html>
<?php
}
?>