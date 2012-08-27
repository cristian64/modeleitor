<?php
include_once "base.php";

if (getUsuario() != null)
{
    header("location: index.php");
    exit();
}

require_once('recaptchalib.php');

$email = getPost("email");
$contrasena = getPost("contrasena");
$contrasena2 = getPost("contrasena2");
$nombre = getPost("nombre");
$telefono = getPost("telefono");
$direccion = getPost("direccion");

$kMinContrasena = 4;
$kMaxContrasena = 100;
$kMinNombre = 4;
$kMaxNombre = 100;

if ($email != "" || $contrasena != "" || $contrasena2 != "" || $nombre != "" || $telefono != "" || $direccion != "")
{
    $verify = recaptcha_check_answer($PRIVATEKEY, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
    if ($verify->is_valid)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $existente = ENUsuario::getByEmail($email);
            if ($existente == null)
            {
                if (strlen($nombre) < $kMaxNombre && strlen($nombre) > $kMinNombre)
                {
                    if ($contrasena == $contrasena2)
                    {
                        if (strlen($contrasena) < $kMaxContrasena && strlen($contrasena) > $kMinContrasena)
                        {
                            $nuevo = new ENUsuario;
                            $nuevo->setEmail($email);
                            $nuevo->setNombre($nombre);
                            $nuevo->setContrasena(sha512($contrasena));
                            $nuevo->setTelefono($telefono);
                            $nuevo->setDireccion($direccion);
                            $registrado = $nuevo->save();

                            if ($registrado)
                            {
                                $_SESSION["mensaje_exito"] = "Cuenta creada correctamente";
                                $_SESSION["usuario"] = serialize($nuevo);
                                
                                header("location: index.php");
                                exit();
                            }
                            else
                            {
                                $_SESSION["mensaje_error"] = "Ocurrió un fallo durante el registro";
                            }
                        }
                        else
                        {
                            $_SESSION["mensaje_error"] = "La contraseña debe tener entre $kMinContrasena y $kMaxContrasena caracteres";
                        }
                    }
                    else
                    {
                        $_SESSION["mensaje_error"] = "Las contraseñas no coinciden";
                    }
                }
                else
                {
                    $_SESSION["mensaje_error"] = "El nombre debe tener entre $kMinNombre y $kMaxNombre caracteres";
                }
            }
            else
            {
                $_SESSION["mensaje_error"] = "Ya existe un usuario con el e-mail $email";
            }
        }
        else
        {
            $_SESSION["mensaje_error"] = "El e-mail $email no tiene un formato adecuado";
        }
    }
    else
    {
        $_SESSION["mensaje_error"] = "El código de seguridad no se introdujo correctamente";
    }
}

baseSuperior("Clientes");
?>

<script type="text/javascript">
 var RecaptchaOptions = {
                custom_translations : {
                        instructions_visual : "Escribe las palabras",
                        instructions_audio : "Transcribe el mensaje",
                        play_again : "Reproducir el audio",
                        cant_hear_this : "Descargar el audio",
                        visual_challenge : "Modalidad visual",
                        audio_challenge : "Modalidad auditiva",
                        refresh_btn : "Refrescar",
                        help_btn : "Ayuda",
                        incorrect_try_again : "Incorrecto. Reintentar"
                },
    theme : 'red',
    lang : 'es'
 };
 </script>
<div id="externo">
<div id="interno">
    <div>
        <h3><span>Crear una nueva cuenta</span></h3>
        <form id="crearcuenta" action="crearcuenta" method="post">
            <table class="guapo-form">
                <tr>
                    <td class="guapo-label">E-mail*</td>
                    <td class="guapo-input"><input type="text" value="<?php echo $email; ?>" name="email" onblur="comprobarEmail();" autocomplete="off" /><div id="error-email" class="guapo-error">El e-mail no es válido</div></td>
                </tr>
                <tr>
                    <td class="guapo-label">Contraseña*</td>
                    <td class="guapo-input"><input type="password" value="" name="contrasena" autocomplete="off" onblur="comprobarContrasena();" /><div id="error-contrasena" class="guapo-error">La contraseña debe tener entre 4 y 100 caracteres</div></td>
                </tr>
                <tr>
                    <td class="guapo-label">Confirmación de contraseña*</td>
                    <td class="guapo-input"><input type="password" value="" name="contrasena2" autocomplete="off" onblur="comprobarContrasena2();" /><div id="error-contrasena2" class="guapo-error">La confirmación de contraseña debe coincidir con la contraseña</div></td>
                </tr>
                <tr>
                    <td class="guapo-label">Nombre y apellidos*</td>
                    <td class="guapo-input"><input type="text" value="<?php echo $nombre; ?>" name="nombre" onblur="comprobarNombre();" /><div id="error-nombre" class="guapo-error">El nombre debe tener entre 4 y 100 letras</div></td>
                </tr>
                <tr>
                    <td class="guapo-label">Teléfono</td>
                    <td class="guapo-input"><input type="text" value="<?php echo $telefono; ?>" name="telefono" /></td>
                </tr>
                <tr>
                    <td class="guapo-label">Dirección</td>
                    <td class="guapo-input"><input type="text" value="<?php echo $direccion; ?>" name="direccion" /></td>
                </tr>
                <tr>
                    <td class="guapo-label"></td>
                    <td class="guapo-input"><?php echo recaptcha_get_html($PUBLICKEY); ?></td>
                </tr>
                <tr>
                    <td class="guapo-label"></td>
                    <td class="guapo-input"><input type="submit" value="Crear cuenta" /></td>
                </tr>
            </table>
        </form>
    </div>
</div></div>
<?php
baseInferior();
?>
