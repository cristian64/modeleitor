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
        if (email(htmlspecialchars($nombre), htmlspecialchars($asunto), htmlspecialchars($email), htmlspecialchars($mensaje)))
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

baseSuperior("Contacto");

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
                            <table>
                                <tr>
                                    <td style="vertical-align: top;  padding-right: 30px;">
                                        <h3><span>Horario de apertura</span></h3>
                                        <div>
                                        De lunes a viernes, desde las 9h hasta las 14h y desde las 16h hasta las 21h.<br />
                                        Sábado, desde las 10h hasta las 13h.
                                        </div>
                                        <br />
                                        <br />
                                        <br />
                                        <h3><span>Información</span></h3>
                                        <div>
                                            <p>Puedes encontrarnos en la dirección:</p>
                                            <div class="contacto-detalles">
                                                <p>Calle Almansa, 65</p>
                                                <p>Elche (Alicante)</p>
                                                <p>03206</p>
                                                <p>ESPAÑA</p>
                                            </div>
                                            
                                            <p>Puedes llamar a:</p>
                                            <div class="contacto-detalles">
                                                <p>+34 966673439 (teléfono fijo)</p>
                                                <p>+34 644299700 (teléfono móvil)</p>
                                            </div>
                                            
                                            <p>Puedes escribinos a:</p>
                                            <div class="contacto-detalles">
                                                <p><a href="mailto:buzon@calzadosjam.es">buzon@calzadosjam.es</a></p>
                                            </div>
                                            
                                            <p>Visítanos en:</p>
                                            <div class="contacto-detalles">
                                                <p>
                                                    <a href="https://www.facebook.com/calzados.jam"><img src="css/facebook.png" alt="Facebook" title="Facebook" /></a> 
                                                    <a href="https://plus.google.com/115603135881616840952/"><img src="css/googleplus.png" alt="Google+" title="Google+" /></a>
													<a href="https://twitter.com/calzadosjam"><img src="css/twitter.png" alt="Twitter" title="Twitter" /></a>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="vertical-align: top;">
                                        <form action="contacto" method="post">
                                            <table class="guapo-form">
                                                <tr>
                                                    <td class="guapo-label">Tu nombre</td>
                                                    <td class="guapo-input"><input type="text" value="<?php if ($rellenar) echo $nombre; ?>"  name="nombre" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="guapo-label">Tu e-mail</td>
                                                    <td class="guapo-input"><input type="text" value="<?php if ($rellenar) echo $email; ?>" name="email" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="guapo-label">Asunto</td>
                                                    <td class="guapo-input"><input type="text" value="<?php if ($rellenar) echo $asunto; ?>" name="asunto" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="guapo-label">Mensaje</td>
                                                    <td class="guapo-input"><textarea cols="40" rows="7" name="mensaje"><?php if ($rellenar) echo $mensaje; ?></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td class="guapo-label"></td>
                                                    <td class="guapo-input"><?php echo recaptcha_get_html($PUBLICKEY); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="guapo-label"></td>
                                                    <td class="guapo-input"><input type="submit" value="Enviar mensaje" /></td>
                                                </tr>
                                            </table>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>
                     </div>
<?php
baseInferior();
?>
