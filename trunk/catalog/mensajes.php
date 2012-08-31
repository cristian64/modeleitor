<?php

    require_once 'minilibreria.php';
    $error = getSession('mensaje_error');
    $aviso = getSession('mensaje_aviso');
    $exito = getSession('mensaje_exito');
    $info = getSession('mensaje_info');

    if ($exito != "" || $info != "" || $aviso != "" || $error != "")
    {
        echo "<div id=\"mensajes\" onclick=\"$('#mensajes').hide('fade');\">\n";
        if ($exito != "")
        {
            if (is_string($exito))
            {
                if (strlen($exito)>0)
                {
                    echo "<table class=\"exito\">\n";
                    echo "<tr>\n";
                    echo "<td><img src=\"css/exito.png\" alt=\"ÉXITO: \" title=\"¡Éxito!\" /> ".$exito."</td>";
                    echo "</tr>\n";
                    echo "</table>\n";
                }
            }
        }

        if ($info != "")
        {
            if (is_string($info))
            {
                if (strlen($info)>0)
                {
                    echo "<table class=\"info\">\n";
                    echo "<tr>\n";
                    echo "<td><img src=\"css/info.png\" alt=\"INFORMATIVO: \" title=\"Mensaje de información\" /> ".$info."</td>";
                    echo "</tr>\n";
                    echo "</table>\n";
                }
            }
        }

        if ($aviso != "")
        {
            if (is_string($aviso))
            {
                if (strlen($aviso)>0)
                {
                    echo "<table class=\"aviso\">\n";
                    echo "<tr>\n";
                    echo "<td><img src=\"css/aviso.png\" alt=\"AVISO: \" title=\"¡Cuidado!\" /> ".$aviso."</td>";
                    echo "</tr>\n";
                    echo "</table>\n";
                }
            }
        }

        if ($error != "")
        {
            if (is_string($error))
            {
                if (strlen($error)>0)
                {
                    echo "<table class=\"error\">\n";
                    echo "<tr>\n";
                    echo "<td><img src=\"css/error.png\" alt=\"ERROR: \" title=\"Ocurrió un error\" /> ".$error."</td>";
                    echo "</tr>\n";
                    echo "</table>\n";
                }
            }
        }
        echo "</div>\n";
        
        unset($_SESSION["mensaje_exito"]);
        unset($_SESSION["mensaje_error"]);
        unset($_SESSION["mensaje_info"]);
        unset($_SESSION["mensaje_aviso"]);
        
        ?>
            <script type="text/javascript">    
            $(window).load(function(){
                $("#mensajes").show('fade');
            });
            </script>
<?php
    }

?>
