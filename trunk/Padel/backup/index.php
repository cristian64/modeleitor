<?php

include_once "../base.php";

$usuario = getUsuario();
if ($usuario == null)
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.";
    header("location: ../iniciarsesion.php");
    exit();
}

if (!$usuario->getAdmin())
{
    $_SESSION["mensaje_error"] = "Esta sección necesita privilegios de administrador";
    header("location: ../index.php");
    exit();
}

$dbhost   = "mysql51-63.perso";
$dbuser   = "clubpadepadel";
$dbpwd    = "Sebastian";
$dbname   = "clubpadepadel";
$now = date("Y-m-d_H-i-s");
$dumpfile = $dbname . "_" . $now . ".sql";
$gzdumpfile = $dumpfile . ".tar.gz";

shell_exec("/usr/bin/mysqldump --opt --host=$dbhost --user=$dbuser --password=$dbpwd $dbname > $dumpfile");
shell_exec("tar -zcf $gzdumpfile $dumpfile");
shell_exec("rm -f $dumpfile");
header("location: $gzdumpfile");

?>
