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

$id = getGet("id");
$modelo = ENModelo::getById($id);
if ($modelo == null)
{
    $_SESSION["mensaje_error"] = "No se ha encontrado el modelo $id";
    header("location: modelos");
    exit();    
}

$array_categorias = $modelo->getCategoriasFromDB();
    
baseSuperior("Modelo ".$modelo->getReferencia());
?>

<script type="text/javascript">
$(document).ready(function(){
    $(".numerico").numeric();
    $(".entero").numeric(false);
});
</script>
<div id="externo">
<div id="interno">
                            <div>
                                <h3><span>Modelo <?php echo $modelo->getReferencia(); ?></span></h3>
                                <form action="operarmodelo" method="post" enctype="multipart/form-data">
                                    <table style="vertical-align: top">
                                        <tr>
                                            <td style="vertical-align: top">
                                                <table class="guapo-form">
                                                    <tr>
                                                        <td class="guapo-label">Referencia</td>
                                                        <td class="guapo-input"><input type="text" value="<?php echo htmlspecialchars($modelo->getReferencia()); ?>" name="referencia" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Nombre</td>
                                                        <td class="guapo-input"><input type="text" value="<?php echo htmlspecialchars($modelo->getNombre()); ?>" name="nombre" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Numeración</td>
                                                        <td class="guapo-input"><input type="text" value="<?php echo $modelo->getTallaMenor(); ?>" name="talla_menor" class="entero" style="width: 60px;" maxlength="2" /> <input type="text" value="<?php echo $modelo->getTallaMayor(); ?>" name="talla_mayor" class="entero" style="width: 60px;" maxlength="2" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Precio</td>
                                                        <td class="guapo-input"><input type="text" value="<?php echo $modelo->getPrecio(); ?>" name="precio" class="numerico" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Fabricante</td>
                                                        <td class="guapo-input">
                                                            <select id="fabricante" name="id_fabricante">
                                                            <?php
                                                                if ($modelo->getIdFabricante() == 0)
                                                                    echo "<option value=\"0\" selected=\"selected\">Otros fabricantes</option>";
                                                                else
                                                                    echo "<option value=\"0\">Otros fabricantes</option>";

                                                                $fabricantes = ENFabricante::get();
                                                                foreach ($fabricantes as $f)
                                                                {
                                                                    if ($modelo->getIdFabricante() == $f->getId())
                                                                        echo "<option value=\"".$f->getId()."\" selected=\"selected\">".htmlspecialchars($f->getNombre())."</option>\n";
                                                                    else
                                                                        echo "<option value=\"".$f->getId()."\">".htmlspecialchars($f->getNombre())."</option>\n";
                                                                }
                                                            ?>
                                                            </select>
                                                            <script type="text/javascript">
                                                                $(function() {
                                                                    $( "#fabricante" ).combobox();
                                                                    $( "#toggle" ).click(function() {
                                                                        $( "#fabricante" ).toggle();
                                                                    });
                                                                });
                                                            </script>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Marca</td>
                                                        <td class="guapo-input">
                                                            <select id="marca" name="id_marca">
                                                            <?php
                                                                if ($modelo->getIdMarca() == 0)
                                                                    echo "<option value=\"0\" selected=\"selected\">Otras marcas</option>";
                                                                else
                                                                    echo "<option value=\"0\">Otras marcas</option>";

                                                                $marcas = ENMarca::get();
                                                                foreach ($marcas as $m)
                                                                {
                                                                    if ($modelo->getIdMarca() == $m->getId())
                                                                        echo "<option value=\"".$m->getId()."\" selected=\"selected\">".htmlspecialchars($m->getNombre())."</option>\n";
                                                                    else
                                                                        echo "<option value=\"".$m->getId()."\">".htmlspecialchars($m->getNombre())."</option>\n";
                                                                }
                                                            ?>
                                                            </select>
                                                            <script type="text/javascript">
                                                                $(function() {
                                                                    $( "#marca" ).combobox();
                                                                    $( "#toggle" ).click(function() {
                                                                        $( "#marca" ).toggle();
                                                                    });
                                                                });
                                                            </script>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Foto</td>
                                                        <?php $thumbs = getThumbs($modelo->getFoto()); ?>
                                                        <td class="guapo-input"><img src="img/modelos/<?php echo $thumbs[1]; ?>" alt="" /><br /><input type="file" name="foto" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Descripción</td>
                                                        <td class="guapo-input"><a id="descripcion-toggle" class="btnazul" onclick="$('#descripcion').show(); $('#descripcion-toggle').hide();">Mostrar</a><div id="descripcion" style="display: none;"><textarea rows="15" cols="20" name="descripcion" class="tinymce" id="tinymce"><?php echo htmlspecialchars($modelo->getDescripcion()); ?></textarea></div>
<script type="text/javascript">
	$().ready(function() {
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : 'js/tinymce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

			// Theme options            
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,anchor,image,cleanup,code,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
            theme_advanced_buttons5 : "preview,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js"
		});
	});
</script>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Prioridad</td>
                                                        <td class="guapo-input">
                                                            <select id="prioridad" name="prioridad">
                                                            <?php
                                                                for ($i = 0; $i <= 10; $i++)
                                                                {
                                                                    if ($modelo->getPrioridad() == $i)
                                                                        echo "<option value=\"$i\" selected=\"selected\">$i</option>";
                                                                    else
                                                                        echo "<option value=\"$i\">$i</option>";
                                                                }
                                                            ?>
                                                            </select>
                                                            <script type="text/javascript">
                                                                $(function() {
                                                                    $( "#prioridad" ).combobox();
                                                                    $( "#toggle" ).click(function() {
                                                                        $( "#prioridad" ).toggle();
                                                                    });
                                                                });
                                                            </script>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label"></td>
                                                        <td class="guapo-input"><input type="checkbox" value="on" name="oferta" <?php if($modelo->getOferta()) echo "checked=\"checked\""; ?>/> Oferta</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Precio (oferta)</td>
                                                        <td class="guapo-input"><input type="text" value="<?php echo $modelo->getPrecioOferta(); ?>" name="precio_oferta" class="numerico" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label"></td>
                                                        <td class="guapo-input"><input type="checkbox" value="on" name="descatalogado" <?php if($modelo->getDescatalogado()) echo "checked=\"checked\""; ?>/> Descatalogado</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label"></td>
                                                        <td class="guapo-input">
                                                            <input type="hidden" value="editar" name="op" />
                                                            <input type="hidden" value="<?php echo $modelo->getId(); ?>" name="id" />
                                                            <input type="submit" value="Guardar" />
                                                            
                                                            <?php echo "<a class=\"btnrojo\" onclick=\"$('#form-eliminar input[name=id]').val(".$modelo->getId()."); $('#dialogo-eliminar').dialog('open');\">Eliminar</a>\n"; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="vertical-align: top;">
                                                <table class="guapo-form" style="vertical-align: top">
                                                    <tr>
                                                        <td class="guapo-label" style="vertical-align: top; text-align: left;">Categorías</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-input" style="vertical-align: top; text-align: left;">
                                                        <?php
                                                                $categoriasRaiz = ENCategoria::getByPadre(0);
                                                                foreach ($categoriasRaiz as $i)
                                                                    imprimirCategoria($i, 0, $array_categorias);
                                                            ?>
                                                        </td>
                                                    </tr>                                            
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                            <div id="dialogo-eliminar" title="¿Eliminar modelo?">
                                <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Se eliminará el modelo definitivamente. ¿Continuar?</p>
                                <form id="form-eliminar" method="POST" action="operarmodelo">
                                    <div><input type="hidden" name="id" value="" /></div>
                                    <div><input type="hidden" name="op" value="eliminar" /></div>
                                </form>
                            </div>

                            <script type="text/javascript">
                            $(window).load(function() {
                                $("#dialogo-eliminar").dialog({
                                    resizable: false,
                                    autoOpen: false,
                                    width: 'auto',
                                    height: 'auto',
                                    modal: true,
                                    buttons: {
                                        "Eliminar": function() {
                                            $("#form-eliminar").submit();
                                        },
                                        "Cancelar": function() {
                                            $( this ).dialog( "close" );
                                        }
                                    }
                                });
                            });
                            </script>
</div></div>


<?php

function imprimirCategoria($categoria, $nivel, $array_categorias, $jscode = "")
{
    $checked = in_array($categoria->getId(), $array_categorias) ? "checked=\"checked\"" : "";
    echo "<div class=\"categoria\" style=\"margin-left: ".($nivel * 40)."px;\"><input id=\"cat".$categoria->getId()."\" $checked onclick=\"if (this.checked) { $jscode }\" type=\"checkbox\" value=\"".$categoria->getId()."\" name=\"categorias[]\" /> ".htmlspecialchars($categoria->getNombre())."</div>\n";
    
    $subcategorias = ENCategoria::getByPadre($categoria->getId(), true);
    foreach ($subcategorias as $i)
    {
        imprimirCategoria($i, $nivel + 1, $array_categorias, $jscode." $('#cat".$categoria->getId()."').prop('checked', true);");
    }
}

baseInferior();
?>
