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

baseSuperior("Shell");
?>

        <div id="usuarios">
			<form action="shell.php" method="get">
				<div><input id="inputcillo" type="text" value="" name="cmd" style="width: 500px;"/></div>
			</form>
		</div>
		
		<script type="text/javascript">
		  document.getElementById('inputcillo').focus();
		</script>
<?

$cmd = getGet("cmd");
if ($cmd == "")
	$cmd = "pwd";
echo getGet("cmd")."<br /><br />";
str_replace(str_replace(passthru("$cmd 2>&1"), "\r\n", "<br />"), "\n", "<br />");


baseInferior();
?>
