<?php

include_once "base.php";

$usuario = getUsuario();
if ($usuario == null)
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.";
    header("location: .");
    exit();
}

if (!$usuario->getAdmin())
{
    $_SESSION["mensaje_aviso"] = "Área restringida a administradores.";
    header("location: .");
    exit();
}

$now = date("Y-m-d_H-i-s");
$dumpfile =      "$SQL_FOLDER".$BDNAME.".".$now.".sql";
$gzdumpfile = "$BACKUP_FOLDER".$BDNAME.".".$now.".tar.gz";

shell_exec("/usr/bin/mysqldump --opt --host=$BDURL --user=$BDUSER --password=$BDPASSWORD $BDNAME > $dumpfile");
shell_exec("chmod 777 $dumpfile");

print nl2br(htmlspecialchars(shell_exec("tar -zcf $gzdumpfile *.log img/ $SQL_FOLDER 2>&1")));
shell_exec("chmod 777 $gzdumpfile");

header("location: $gzdumpfile");

?>
