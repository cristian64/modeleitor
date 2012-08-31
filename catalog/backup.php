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

$now = date("Y-m-d_H-i-s");
$dumpfile = "sql/" . $BDNAME . "." . $now . ".sql";
$gzdumpfile = "../catalog.$now.tar.gz";

shell_exec("/usr/bin/mysqldump --opt --host=$BDURL --user=$BDUSER --password=$BDPASSWORD $BDNAME > $dumpfile");
shell_exec("chmod 777 $dumpfile");

print nl2br(htmlspecialchars(shell_exec("tar -zcf $gzdumpfile ../catalog 2>&1")));
shell_exec("chmod 777 $gzdumpfile");

header("location: $gzdumpfile");

?>
