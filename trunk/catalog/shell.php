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
        
<?php

$cmd = getGet("cmd");
if ($cmd == "")
    $cmd = "pwd";
echo "<br />".$cmd."<br /><br />";
print nl2br(shell_exec("$cmd 2>&1"));


baseInferior();
?>
