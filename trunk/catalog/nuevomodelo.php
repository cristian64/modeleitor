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
    
baseSuperior("Nuevo modelo");
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
                                <h3><span>Nuevo modelo</span></h3>
                                <form action="operarmodelo" method="post" enctype="multipart/form-data">
                                    <table style="vertical-align: top">
                                        <tr>
                                            <td style="vertical-align: top">
                                                <table class="guapo-form">
                                                    <tr>
                                                        <td class="guapo-label">Referencia</td>
                                                        <td class="guapo-input"><input type="text" value="" name="referencia" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Nombre</td>
                                                        <td class="guapo-input"><input type="text" value="" name="nombre" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Numeración</td>
                                                        <td class="guapo-input"><input type="text" value="" name="talla_menor" class="entero" style="width: 60px;" maxlength="2" /> <input type="text" value="" name="talla_mayor" class="entero" style="width: 60px;" maxlength="2" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Precio</td>
                                                        <td class="guapo-input"><input type="text" value="" name="precio" class="numerico" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Fabricante</td>
                                                        <td class="guapo-input">
                                                            <select id="fabricante" name="id_fabricante">
                                                                <option value="0">Otros fabricantes</option>
                                                            <?php
                                                                $fabricantes = ENFabricante::get();
                                                                foreach ($fabricantes as $f)
                                                                {
                                                                    echo "<option value=\"".$f->getId()."\">".$f->getNombre()."</option>\n";
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
                                                                <option value="0">Otras marcas</option>
                                                            <?php
                                                                $marcas = ENMarca::get();
                                                                foreach ($marcas as $m)
                                                                {
                                                                    echo "<option value=\"".$m->getId()."\">".$m->getNombre()."</option>\n";
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
                                                        <td class="guapo-input"><input type="file" name="foto" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Descripción</td>
                                                        <td class="guapo-input"><a id="descripcion-toggle" class="btnazul" onclick="$('#descripcion').show(); $('#descripcion-toggle').hide();">Mostrar</a><div id="descripcion" style="display: none;"><textarea rows="15" cols="20" name="descripcion" class="tinymce" id="tinymce"></textarea></div>
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
                                                                <option value="0">0</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5" selected="selected">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                                <option value="8">8</option>
                                                                <option value="9">9</option>
                                                                <option value="10">10</option>
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
                                                        <td class="guapo-input"><input type="checkbox" value="on" name="oferta" /> Oferta</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label">Precio (oferta)</td>
                                                        <td class="guapo-input"><input type="text" value="" name="precio_oferta" class="numerico" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label"></td>
                                                        <td class="guapo-input"><input type="checkbox" value="on" name="descatalogado" /> Descatalogado</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="guapo-label"></td>
                                                        <td class="guapo-input"><input type="hidden" value="anadir" name="op" /><input type="submit" value="Guardar" /></td>
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
                                                                    imprimirCategoria($i, 0);
                                                            ?>
                                                        </td>
                                                    </tr>                                            
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
</div></div>
<?php

function imprimirCategoria($categoria, $nivel, $jscode = "")
{
    echo "<div class=\"categoria\" style=\"margin-left: ".($nivel * 40)."px;\"><input id=\"cat".$categoria->getId()."\" onclick=\"if (this.checked) { $jscode }\" type=\"checkbox\" value=\"".$categoria->getId()."\" name=\"categorias[]\" /> ".$categoria->getNombre()."</div>\n";
    
    $subcategorias = ENCategoria::getByPadre($categoria->getId(), true);
    foreach ($subcategorias as $i)
    {
        imprimirCategoria($i, $nivel + 1, $jscode." $('#cat".$categoria->getId()."').prop('checked', true);");
    }
}

baseInferior();
?>
