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

if ($usuario->getAdmin())
{
    $id = getGet("id");
    if ($id != "")
    {
        $candidato = ENUsuario::obtenerPorId($id);
        if ($candidato != null)
        {
            $u = $candidato;
        }
        else
        {
            $_SESSION["mensaje_aviso"] = "No se ha encontrado el usuario con el nº $id";
            header('location: usuarios.php');
            exit();
        }
    }
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
        <?php if ($usuario->getId() == $u->getId()) { ?>
        document.getElementById('contrasenas1').style.display = "none";
        <?php } ?>
        document.getElementById('contrasenas2').style.display = "none";
        document.getElementById('contrasenas3').style.display = "none";
        document.getElementById('contrasenas4').style.display = "";
        document.getElementById('formulario').reset();
    }
    
    function cambiarContrasena()
    {
        <?php if ($usuario->getId() == $u->getId()) { ?>
        document.getElementById('contrasenas1').style.display = "";
        <?php } ?>
        document.getElementById('contrasenas2').style.display = "";
        document.getElementById('contrasenas3').style.display = "";
        document.getElementById('contrasenas4').style.display = "none";
    }
    
</script>
                     <div id="externo">
                        <div id="interno">
                            <div id="usuario">
                                <?php if ($u->getId() != $usuario->getId()) { ?>
                                <h3><span>Datos del usuario nº <?php echo rellenar($u->getId(), '0', $RELLENO); ?></span></h3>
                                <? } else { ?>
                                <h3><span>Mis datos personales</span></h3>
                                <?php } ?>
                                <form id="formulario" action="operarusuario.php" method="post" enctype="multipart/form-data" onsubmit="return validarUsuario(this);">
                                    <table>
                                        <tr>
                                            <td class="columna1">Nº de usuario</td>
                                            <td class="columna2"><input type="text" value="<?php echo rellenar($u->getId(), '0', $RELLENO); ?>" name="id" class="textinputreadonly" readonly="readonly" /></td>
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
                                        <?php if ($usuario->getAdmin()) { ?>
                                        <tr>
                                            <td class="columna1">Administrador*</td>
                                            <td class="columna2">
                                                <div class="textinputfake">
                                                <input type="radio" <?php if ($u->getId() == $usuario->getId()) echo "disabled=\"disabled\""; ?> name="admin" value="0" <?php echo !$u->getAdmin() ? "checked=\"checked\" " : ""; ?>/> No
                                                <input type="radio" <?php if ($u->getId() == $usuario->getId()) echo "disabled=\"disabled\""; ?> name="admin" value="1" <?php echo $u->getAdmin() ? "checked=\"checked\" " : ""; ?>/> Sí
                                                <?php if ($u->getId() == $usuario->getId()) echo "<br /><span style=\"font-size: 0.8em\">(no puedes editar tu propio estado de administrador)</span>"; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td class="columna1">Fecha de registro</td>
                                            <td class="columna2"><input type="text" value="<?php echo $u->getFechaRegistro()->format('d/m/Y H:i:s'); ?>" name="" class="textinputreadonly" readonly="readonly" /></td>
                                        </tr>
                                        <?php if ($usuario->getId() == $u->getId()) { ?>
                                        <tr id="contrasenas1" style="display: none;">
                                            <td class="columna1">Contraseña anterior*</td>
                                            <td class="columna2"><input type="password" value="" name="contrasena3" class="textinput" autocomplete="off" /></td>
                                        </tr>
                                        <?php } ?>
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
                                            <td class="columna2"><div class="textinputfake"><a href="#" onclick="cambiarContrasena();">Cambiar también la contraseña</a></div></td>
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