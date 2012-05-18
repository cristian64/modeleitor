<?php
include_once "base.php";
baseSuperior("Iniciar sesión", false);
?>
                    <div id="externo">
                        <div id="interno">
                            <div id="iniciarsesion">
                                <h3><span>Iniciar sesión</span></h3>
                                <form action="operariniciosesion.php" method="post" onsubmit="return validarIdentificacion(this);">
                                    <div><label>E-mail:</label><input type="text" value="" name="email" class="textinput" /></div>
                                    <div><label>Contraseña:</label><input type="password" value="" name="contrasena" class="textinput" /></div>
                                    <div><label></label><input type="checkbox" value="on" name="recordar" /> <span>Recordarme en este equipo</span></div>
                                    <div><label></label><input type="submit" value="Iniciar sesión" class="freshbutton-big"/><span> o <a href="registrarse.php">registrarse</a> para reservar pista</span></div>
                                </form>
                            </div>
                        </div>
                    </div>
<?php
baseInferior();
?>
