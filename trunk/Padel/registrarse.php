<?php
include_once "base.php";
baseSuperior("Registrarse", false);

require_once('recaptchalib.php');

$pubkey = "6LclgdESAAAAAPO1GX1vx52lCEjeTG0AUlWC6-o3";
$privkey = "6LclgdESAAAAAG5ktRzJnf7u6Zk-I86bjKC-29DG";

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
                                            <td class="columna1">E-mail*:</td>
                                            <td class="columna2"><input type="text" value="" name="email" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Contraseña*:</td>
                                            <td class="columna2"><input type="password" value="" name="contrasena" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Confirmación de contraseña*:</td>
                                            <td class="columna2"><input type="password" value="" name="contrasena2" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Nombre y apellidos*:</td>
                                            <td class="columna2"><input type="text" value="" name="nombre" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">DNI:</td>
                                            <td class="columna2"><input type="text" value="" name="dni" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Sexo*:</td>
                                            <td class="columna2">
                                                <input type="radio" name="sexo" value="mujer" checked="checked"/> Mujer
                                                <input type="radio" name="sexo" value="hombre" /> Hombre
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Dirección:</td>
                                            <td class="columna2"><input type="text" value="" name="direccion" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1">Teléfono:</td>
                                            <td class="columna2"><input type="text" value="" name="telefono" class="textinput" /></td>
                                        </tr>
                                        <tr>
                                            <td class="columna1"></td>
                                            <td class="columna2"><?php echo recaptcha_get_html($pubkey); ?></td>
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