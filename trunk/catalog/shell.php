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

baseSuperior("Shell");
?>
<h3>Shell</h3>
        <div id="usuarios">
            <form action="shell" method="get">
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
print nl2br(htmlspecialchars(shell_exec("$cmd 2>&1")));


baseInferior();
?>
