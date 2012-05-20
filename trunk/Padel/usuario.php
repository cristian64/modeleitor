<?php
include_once "base.php";

$usuario = getUsuario();
if ($usuario == null)
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.";
    header("location: iniciarsesion.php");
    exit();
}

$u = $usuario;
$id = getGet("id");
if ($id != "")
{
    //try to get a new user and check it is you or you're admin...
}

baseSuperior("Usuario nº ".rellenar($u->getId(), '0', $RELLENO), true);

?>
<script type="text/javascript">
    function guardarCambios()
    {
        var formu = document.getElementById('formulario');
        if (validarUsuario(formu))
            formu.submit();
    }
    
    function deshacerCambios()
    {
        document.getElementById('contrasenas1').style.display = "none";
        document.getElementById('contrasenas2').style.display = "none";
        document.getElementById('contrasenas3').style.display = "none";
        document.getElementById('contrasenas4').style.display = "";
        document.getElementById('formulario').reset();
    }
    
    function cambiarContrasena()
    {
        document.getElementById('contrasenas1').style.display = "";
        document.getElementById('contrasenas2').style.display = "";
        document.getElementById('contrasenas3').style.display = "";
        document.getElementById('contrasenas4').style.display = "none";
    }
    
</script>
                     <div id="externo">
                        <div id="interno">
                            <div id="usuario">
                                <h3><span>Datos del usuario</span></h3>
                                <form id="formulario" action="operarusuario.php" method="post" enctype="multipart/form-data" onsubmit="return validarUsuario(this);">
                                    <table>
                                        <tr>
                                            <td class="columna1">Nº de usuario</td>
                                            <td class="columna2"><input type="text" value="<?php echo $u->getId(); ?>" name="id" class="textinput" readonly="readonly" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">E-mail*</td>
                                            <td class="columna2"><input type="text" value="<?php echo $u->getEmail(); ?>" name="email" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Nombre y apellidos*</td>
                                            <td class="columna2"><input type="text" value="<?php echo $u->getNombre(); ?>" name="nombre" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">DNI</td>
                                            <td class="columna2"><input type="text" value="<?php echo $u->getDni(); ?>" name="dni" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Sexo*</td>
                                            <td class="columna2">
                                                <div class="textinputfake">
                                                <input type="radio" name="sexo" value="mujer" <?php echo $u->getSexo() == "mujer" ? "checked=\"mujer\" " : ""; ?>/> Mujer
                                                <input type="radio" name="sexo" value="hombre" <?php echo $u->getSexo() == "hombre" ? "checked=\"hombre\" " : ""; ?>/> Hombre
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Dirección</td>
                                            <td class="columna2"><input type="text" value="<?php echo $u->getDireccion(); ?>" name="direccion" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Teléfono</td>
                                            <td class="columna2"><input type="text" value="<?php echo $u->getTelefono(); ?>" name="telefono" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Fecha de registro</td>
                                            <td class="columna2"><input type="text" value="<?php echo $u->getFechaRegistro()->format('d/m/Y H:i:s'); ?>" name="" class="textinput" readonly="readonly" /></td>
                                        </tr>
                                        <tr id="contrasenas1" style="display: none;">
                                            <td class="columna1">Contraseña anterior*</td>
                                            <td class="columna2"><input type="password" value="" name="contrasena3" class="textinput" autocomplete="off" /></td>
                                        </tr>
                                        <tr id="contrasenas2" style="display: none;">
                                            <td class="columna1">Nueva contraseña*</td>
                                            <td class="columna2"><input type="password" value="" name="contrasena" class="textinput" autocomplete="off" /></td>
                                        </tr>
                                        <tr id="contrasenas3" style="display: none;">
                                            <td class="columna1">Confirmación de contraseña*</td>
                                            <td class="columna2"><input type="password" value="" name="contrasena2" class="textinput" autocomplete="off" /></td>
                                        </tr>
                                        <tr id="contrasenas4">
                                            <td class="columna1"></td>
                                            <td class="columna2"><a href="#" onclick="cambiarContrasena();">Cambiar también la contraseña</a></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1"></td>
                                            <td class="columna2"><div class="textinputfake">
                                                <a class="freshbutton-blue" onclick="guardarCambios();">Guardar cambios</a>
                                                <a class="freshbutton-red" onclick="deshacerCambios();">Deshacer cambios</a>
                                            </div></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                     </div>
<?php
baseInferior();
?>