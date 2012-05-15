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
function baseSuperior($titulo)
{
	if ($titulo == "")
		$titulo = "Título";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>PADEL - <?php echo $titulo; ?></title>
		<link type="text/css" rel="stylesheet" href="estilo/freshbutton.css" media="screen" title="Estilo principal" />
		<link type="text/css" rel="stylesheet" href="estilo/imprimible.css" media="print" />
		<script src="javascript/formularios.js" type="text/javascript"></script>
		<script src="javascript/cookies.js" type="text/javascript"></script>
	</head>
	<body>
		<div id="contenedor">
			<div id="contenido">
				<div id="cabecera">
					<div id="titulo">
						<h1><a href="index.php">PADEL</a></h1>
						<h2>Reserva tu pista de padel</h2>
					</div>

					<?php include 'mensajes.php'; ?>
				</div>

				<div id="navegacion">
					<div id="menu">
						<h3><span>Menú</span></h3>
						<ul>
							<li><a href="index.php">Inicio</a></li>
							<li><a href="mapa.php">Mapa</a></li>
                            <li><a href="contacto.php">Contacto</a></li>
						</ul>
					</div>

<?php

if (isset($_SESSION["usuario"]))
{
	$usuario = unserialize($_SESSION["usuario"]);
    //TODO: haced algo aqui!
?>
                    <div id="identificado">
                        <h3><span>Identificado</span></h3>
                        <p>Tú eres <?php echo $usuario->getNombre(); ?></p>
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
                    </div>
<?php
}
else
{
?>
					<div id="identificarse">
                        <h3><span>Identificarse</span></h3>
						<form action="iniciarsesion.php" method="post" onsubmit="return validarIdentificacion(this);">
							<div><label>E-mail:</label><input type="text" value="" name="email" class="textinput" /></div>
							<div><label>Contraseña:</label><input type="password" value="" name="contrasena" class="textinput" /></div>
							<div><label></label><input type="submit" value="Entrar" class="freshbutton-big"/></div>
							<div><label></label><input type="checkbox" value="on" name="recordar" /> <span>Recordarme en este equipo</span></div>
							<div><span><a href="registrarse.php">Regístrate</a></span></div>
						</form>
					</div>
<?php
}
?>
				</div>

				<div id="cuerpo">
					<div id="cuerpo_superior"><span></span></div>
					<div id="cuerpo_medio">
<?php
}

function baseInferior()
{
?>
					</div>
					<div id="cuerpo_inferior"><span></span></div>
				</div>

				<div id="pie">
					<div id="pie_superior">
						<div id="creditos">
							<h3><span>Créditos</span></h3>
							<ul>
								<li><a href="mailto:cam33@alu.ua.es">Cristian Aguilera Martínez</a></li>
								<li><a href="http://www.dlsi.ua.es/asignaturas/pi/">Programación en Internet, 2009/2010</a></li>
							</ul>
						</div>
						<div id="rss">
							<h3><span>Subscríbete al canal RSS</span></h3>
							<ul>
								<li><a href="#">Últimas fotos</a></li>
								<li><a href="#">Mejores fotos del día</a></li>
								<li><a href="#">Mejores catálogos</a></li>
							</ul>
						</div>
						<div id="w3">
							<h3><span>W3C</span></h3>
							<p class="w3">
								<a href="http://validator.w3.org"><!--/check?uri=referer-->
									<img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" />
								</a>
							</p>
							<p class="w3">
								<a href="http://jigsaw.w3.org/css-validator"><!--/check/referer-->
									<img src="http://jigsaw.w3.org/css-validator/images/vcss" alt="¡CSS Válido!" />
								</a>
							</p>
						</div>
					</div>
					<div id="pie_inferior">
						<div id="estilos">
							<h3><span>Selecciona un estilo:</span></h3>
							<ul>
								<li><a href="javascript: cambiarEstilo('Estilo principal');">Estilo principal</a></li>
								<li><a href="javascript: cambiarEstilo('Estilo sencillo');">Estilo sencillo</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Estas etiquetas se pueden emplear para añadir imágenes adicionales. -->
		<div id="extraDiv1"><span></span></div>
		<div id="extraDiv2"><span></span></div>
		<div id="extraDiv3"><span></span></div>
		<div id="extraDiv4"><span></span></div>
		<div id="extraDiv5"><span></span></div>
		<div id="extraDiv6"><span></span></div>

	</body>
</html>
<?php
}
?>