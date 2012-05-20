<?php
include_once "base.php";

if (getUsuario() != null)
{
    header("location: index.php");
    exit();
}

require_once('recaptchalib.php');
baseSuperior("Registrarse", true);

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
                            <div id="registrarse">
                                <h3><span>Datos del nuevo usuario</span></h3>
                                <form action="operarregistro.php" method="post" enctype="multipart/form-data" onsubmit="return validarRegistro(this);">
                                    <table>
                                        <tr>
                                            <td class="columna1">E-mail*</td>
                                            <td class="columna2"><input type="text" value="" name="email" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Contraseña*</td>
                                            <td class="columna2"><input type="password" value="" name="contrasena" class="textinput" autocomplete="off" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Confirmación de contraseña*</td>
                                            <td class="columna2"><input type="password" value="" name="contrasena2" class="textinput" autocomplete="off" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Nombre y apellidos*</td>
                                            <td class="columna2"><input type="text" value="" name="nombre" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">DNI</td>
                                            <td class="columna2"><input type="text" value="" name="dni" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Sexo*</td>
                                            <td class="columna2"><div class="textinputfake">
                                                <input type="radio" name="sexo" value="mujer" checked="checked"/> Mujer
                                                <input type="radio" name="sexo" value="hombre" /> Hombre
                                            </div></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Dirección</td>
                                            <td class="columna2"><input type="text" value="" name="direccion" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Teléfono</td>
                                            <td class="columna2"><input type="text" value="" name="telefono" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1"></td>
                                            <td class="columna2"><?php echo recaptcha_get_html($PUBLICKEY); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1"></td>
                                            <td class="columna2"><input type="submit" value="Registrarse" class="freshbutton-big" /></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                     </div>
<?php
baseInferior();
?>