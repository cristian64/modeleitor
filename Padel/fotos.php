<?php
include_once "base.php";

$esAdmin = false;
$usuario = getUsuario();
if ($usuario != null && $usuario->getAdmin())
{
    $esAdmin = true;
    if (getPost("subiendo") != "")
    {
        if (crearFicheroFoto($_FILES["foto"], getPost("portada") != "" ? "portada/" : "fotos/"))
            $_SESSION["mensaje_exito"] = "Foto subida correctamente";
        else
            $_SESSION["mensaje_error"] = "Ocurrió un error al subir la foto. Comprueba que el tamaño del fichero no es enorme y que es un ficher válido.";
        header("location: fotos.php");
        exit();
    }
    
    $borrarfoto = getGet("borrar");
    if ($borrarfoto != "")
    {
        if ((borrar("fotos/$borrarfoto") && borrar("fotos/".str_replace(".thumb", "", $borrarfoto))) || (borrar("portada/$borrarfoto") && borrar("portada/".str_replace(".thumb", "", $borrarfoto))))
            $_SESSION["mensaje_exito"] = "Foto eliminada correctamente";
        else
            $_SESSION["mensaje_error"] = "Ocurrió un error al eliminar la foto";
        header("location: fotos.php");
        exit();
    }
}

baseSuperior("Fotos");
?>
        <div id="fotos">
            <h3><span>Fotos</span></h3>
            <div id="mosaico">
            
<?php
    $fotos = getDirectoryList("fotos/");
    
    foreach ($fotos as $i)
    {
        if (strpos($i,'thumb') !== false)
        {
            $j = str_replace("thumb.", "", $i);
            echo "<div class=\"foto\">";
            echo "<a href=\"fotos/$j\" class=\"thickbox\"><img src=\"fotos/$i\" title=\"Club Padel Matola\" alt=\"Club Padel Matola\" /></a>";
            if ($esAdmin)
            {
                echo "<a class=\"borrarfoto\" href=\"fotos.php?borrar=$i\" onclick=\"return confirm('¿Está seguro de que quiere borrar la foto?');\"><img src=\"css/cruz.png\" title=\"Borrar foto\" /></a>";
            }
            echo "</div>";
        }
    }
    
    if ($esAdmin) {
        echo "<div style=\"clear:both;\"></div>";
        echo "<h3><span>Portada</span></h3>";
        echo "<div style=\"clear:both;\"></div>\n";
        
        $fotos = getDirectoryList("portada/");
        
        foreach ($fotos as $i)
        {
            if (strpos($i,'thumb') !== false)
            {
                $j = str_replace("thumb.", "", $i);
                echo "<div class=\"foto\">";
                echo "<a href=\"portada/$j\" class=\"thickbox\"><img src=\"portada/$i\" title=\"Club Padel Matola\" alt=\"Club Padel Matola\" /></a>";
                if ($esAdmin)
                {
                    echo "<a class=\"borrarfoto\" href=\"fotos.php?borrar=$i\" onclick=\"return confirm('¿Está seguro de que quiere borrar la foto?');\"><img src=\"css/cruz.png\" title=\"Borrar foto\" /></a>";
                }
                echo "</div>";
            }
        }
    }
?>
                <div style="clear: both;"></div>
            </div>

<?php
    if ($esAdmin)
    {
?>
            <div>
            <h3><span>Añadir foto</span></h3>
            <form action="fotos.php" method="POST" enctype="multipart/form-data">
                <div><input type="hidden" value="si" name="subiendo" /><input type="file" name="foto" /> &nbsp;&nbsp;&nbsp; <input type="checkbox" value="si" name="portada" /> Mostrar en portada
                 &nbsp;&nbsp;&nbsp; <input type="submit" name="" value="Subir foto"></div>
            </form>
            
            <br />
            <h4>Instrucciones:</h4>
            <ol>
                <li>Selecciona una foto y pulsa el botón Subir foto</li>
                <li>Comprueba que el fichero es un formato válido: JPG, PNG, GIF, etc.</li>
                <li>Es recomendable subir fotos JPG con un tamaño no superior a 500 KB</li>
            </ol>
            </div>
<?php
}
?>
        </div>
<?php
baseInferior();
?>
