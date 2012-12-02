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
    var concambios = false;
    
    function guardarCambios()
    {
        if (concambios)
        {
            var formu = document.getElementById('formulario');
            if (validarUsuario(formu))
                formu.submit();
        }
    }
    
    function deshacerCambios()
    {
        if (concambios)
        {
            <?php if ($usuario->getId() == $u->getId()) { ?>
            document.getElementById('contrasenas1').style.display = "none";
            <?php } ?>
            document.getElementById('contrasenas2').style.display = "none";
            document.getElementById('contrasenas3').style.display = "none";
            document.getElementById('contrasenas4').style.display = "";
            document.getElementById('formulario').reset();
            
            $('#botonguardar').attr("class", "freshbutton-disabled");
            $('#botonreset').attr("class", "freshbutton-disabled");
        }
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
                                    <table class="guapo-form">
                                        <tr>
                                            <td class="guapo-label">Nº de usuario</td>
                                            <td class="guapo-input"><input type="text" value="<?php echo rellenar($u->getId(), '0', $RELLENO); ?>" name="id" class="textinputreadonly" readonly="readonly" /></td>
                                        </tr>
                                        <tr>
                                            <td class="guapo-label">E-mail*</td>
                                            <td class="guapo-input"><input type="text" value="<?php echo $u->getEmail(); ?>" name="email" class="textinputreadonly" readonly="readonly" /></td>
                                        </tr>
                                        <tr>
                                            <td class="guapo-label">Nombre y apellidos*</td>
                                            <td class="guapo-input"><input type="text" value="<?php echo $u->getNombre(); ?>" name="nombre" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="guapo-label">DNI</td>
                                            <td class="guapo-input"><input type="text" value="<?php echo $u->getDni(); ?>" name="dni" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="guapo-label">Sexo*</td>
                                            <td class="guapo-input">
                                                <div class="textinputfake">
                                                <input type="radio" name="sexo" value="mujer" <?php echo $u->getSexo() == "mujer" ? "checked=\"checked\" " : ""; ?>/> Mujer
                                                <input type="radio" name="sexo" value="hombre" <?php echo $u->getSexo() == "hombre" ? "checked=\"checked\" " : ""; ?>/> Hombre
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="guapo-label">Dirección</td>
                                            <td class="guapo-input"><input type="text" value="<?php echo $u->getDireccion(); ?>" name="direccion" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="guapo-label">Teléfono</td>
                                            <td class="guapo-input"><input type="text" value="<?php echo $u->getTelefono(); ?>" name="telefono" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="guapo-label">Disponibilidad<br/>para eventos</td>
                                            <td class="guapo-input">
                                                <fieldset>
                                                <legend>Entresemana:</legend>
                                                    <input type="checkbox" value="1" name="disponibilidad[]" <?php if ($u->getDisponibilidad() & 1) echo "checked=\"checked\""; ?> />mañana&nbsp;&nbsp;
                                                    <input type="checkbox" value="2" name="disponibilidad[]" <?php if ($u->getDisponibilidad() & 2) echo "checked=\"checked\""; ?> />tarde&nbsp;&nbsp;
                                                    <input type="checkbox" value="4" name="disponibilidad[]" <?php if ($u->getDisponibilidad() & 4) echo "checked=\"checked\""; ?> />noche&nbsp;&nbsp;
                                                </fieldset>
                                                <br/>
                                                <fieldset>
                                                <legend>Sábado:</legend>
                                                    <input type="checkbox" value="8" name="disponibilidad[]" <?php if ($u->getDisponibilidad() & 8) echo "checked=\"checked\""; ?> />mañana&nbsp;&nbsp;
                                                    <input type="checkbox" value="16" name="disponibilidad[]" <?php if ($u->getDisponibilidad() & 16) echo "checked=\"checked\""; ?> />tarde&nbsp;&nbsp;
                                                    <input type="checkbox" value="32" name="disponibilidad[]" <?php if ($u->getDisponibilidad() & 32) echo "checked=\"checked\""; ?> />noche&nbsp;&nbsp;
                                                </fieldset>
                                                <br/>
                                                <fieldset>
                                                <legend>Domingo:</legend>
                                                    <input type="checkbox" value="64" name="disponibilidad[]" <?php if ($u->getDisponibilidad() & 64) echo "checked=\"checked\""; ?> />mañana&nbsp;&nbsp;
                                                    <input type="checkbox" value="128" name="disponibilidad[]" <?php if ($u->getDisponibilidad() & 128) echo "checked=\"checked\""; ?> />tarde&nbsp;&nbsp;
                                                    <input type="checkbox" value="256" name="disponibilidad[]" <?php if ($u->getDisponibilidad() & 256) echo "checked=\"checked\""; ?> />noche&nbsp;&nbsp;
                                                </fieldset>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="guapo-label">Reservas</td>
                                            <td class="guapo-input"><?php echo ENReserva::contarPorUsuario($u->getId()); ?> reservas <a href="reservas.php?filtro=<?php echo $u->getEmail(); ?>">(Ver últimas)</a></td>
                                        </tr>
                                        <?php if ($usuario->getAdmin()) { ?>
                                        <tr>
                                            <td class="guapo-label">Administrador*</td>
                                            <td class="guapo-input">
                                                <div class="textinputfake">
                                                <input type="radio" <?php if ($u->getId() == $usuario->getId()) echo "disabled=\"disabled\""; ?> name="admin" value="0" <?php echo !$u->getAdmin() ? "checked=\"checked\" " : ""; ?>/> No
                                                <input type="radio" <?php if ($u->getId() == $usuario->getId()) echo "disabled=\"disabled\""; ?> name="admin" value="1" <?php echo $u->getAdmin() ? "checked=\"checked\" " : ""; ?>/> Sí
                                                <?php if ($u->getId() == $usuario->getId()) echo "<br /><span style=\"font-size: 0.8em\">(no puedes editar tu propio estado de administrador)</span>"; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td class="guapo-label">Fecha de registro</td>
                                            <td class="guapo-input"><input type="text" value="<?php echo $u->getFechaRegistro()->format('d/m/Y H:i:s'); ?>" name="" class="textinputreadonly" readonly="readonly" /></td>
                                        </tr>
                                        <?php if ($usuario->getId() == $u->getId()) { ?>
                                        <tr id="contrasenas1" style="display: none;">
                                            <td class="guapo-label">Contraseña anterior*</td>
                                            <td class="guapo-input"><input type="password" value="" name="contrasena3" class="textinput" autocomplete="off" /></td>
                                        </tr>
                                        <?php } ?>
                                        <tr id="contrasenas2" style="display: none;">
                                            <td class="guapo-label">Nueva contraseña*</td>
                                            <td class="guapo-input"><input type="password" value="" name="contrasena" class="textinput" autocomplete="off" /></td>
                                        </tr>
                                        <tr id="contrasenas3" style="display: none;">
                                            <td class="guapo-label">Confirmación de contraseña*</td>
                                            <td class="guapo-input"><input type="password" value="" name="contrasena2" class="textinput" autocomplete="off" /></td>
                                        </tr>
                                        <tr id="contrasenas4">
                                            <td class="guapo-label"></td>
                                            <td class="guapo-input"><div class="textinputfake"><a href="#" onclick="cambiarContrasena();">Cambiar también la contraseña</a></div></td>
                                        </tr>
                                        <tr>
                                            <td class="guapo-label"></td>
                                            <td class="guapo-input"><div class="textinputfake">
                                                <a id="botonguardar" class="freshbutton-disabled" onclick="guardarCambios();">Guardar cambios</a>
                                                <a id="botonreset" class="freshbutton-disabled" onclick="deshacerCambios();">Deshacer cambios</a>
                                            </div></td>
                                        </tr>
                                    </table>
                                </form>
                                

                                <script type="text/javascript">
                                    $(":input").change(function() {
                                            concambios = true;
                                            
                                            $('#botonguardar').attr("class", "freshbutton-blue");
                                            $('#botonreset').attr("class", "freshbutton-red");
                                        });
                                        
                                    $(":input").keydown(function() {
                                            concambios = true;
                                            
                                            $('#botonguardar').attr("class", "freshbutton-blue");
                                            $('#botonreset').attr("class", "freshbutton-red");
                                        });
                                </script>
                            </div>
                        </div>
                     </div>
<?php
baseInferior();
?>
