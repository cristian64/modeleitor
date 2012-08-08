<?php
include_once "base.php";

require_once('recaptchalib.php');

$nombre = getPost("nombre");
$asunto = getPost("asunto");
$email = getPost("email");
$mensaje = getPost("mensaje");

$rellenar = false;
if ($nombre != "" || $asunto != "" || $email != "" || $mensaje != "")
{    
    $rellenar = true;
    $verify = recaptcha_check_answer($PRIVATEKEY, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
    if ($verify->is_valid)
    {
        if (email($nombre, $asunto, $email, $mensaje))
        {
            $_SESSION["mensaje_exito"] = "El mensaje ha sido enviado. Responderemos tan pronto como sea posible.";
            $rellenar = false;
        }
    }
    else
    {
        $_SESSION["mensaje_error"] = "El código de seguridad no se introdujo correctamente";
    }    
}

baseSuperior("Contacto", true);

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
                            <div id="contacto">
                                <h3><span>Información de contacto</span></h3>
                                <div>
                                Teléfono de contacto de los administradores:
                                <ul>
                                    <li>Beatriz 605 85 68 70</li>
                                    <li>Santiago Pamies 636 67 06 87</li>
                                    <li>Emilio Mejias  607 382 173</li>
                                </ul>
                                </div>
                                <p>E-mail de contacto: <a href="mailto:<?php echo $EMAILCONTACTO; ?>"><?php echo $EMAILCONTACTO; ?></a></p>
                                <h3><span>También puedes enviarnos un mensaje</span></h3>
                                <form action="contacto.php" method="post" enctype="multipart/form-data" onsubmit="return validarContacto(this);">
                                    <table>
                                        <tr>
                                            <td class="columna1">Tu nombre</td>
                                            <td class="columna2"><input type="text" value="<?php if ($rellenar) echo $nombre; ?>" name="nombre" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Asunto</td>
                                            <td class="columna2"><input type="text" value="<?php if ($rellenar) echo $asunto; ?>" name="asunto" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">E-mail al que responderte</td>
                                            <td class="columna2"><input type="text" value="<?php if ($rellenar) echo $email; ?>" name="email" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Mensaje</td>
                                            <td class="columna2"><textarea cols="40" rows="7" class="textinput" name="mensaje"><?php if ($rellenar) echo $mensaje; ?></textarea></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1"></td>
                                            <td class="columna2"><?php echo recaptcha_get_html($PUBLICKEY); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1"></td>
                                            <td class="columna2"><input type="submit" value="Enviar mensaje" class="freshbutton-big" /></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                     </div>
<?php
baseInferior();
?>
