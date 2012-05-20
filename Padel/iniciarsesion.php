<?php
include_once "base.php";

if (getUsuario() != null)
{
    header("location: index.php");
    exit();
}
    
baseSuperior("Iniciar sesión", false);
?>
                    <div id="externo">
                        <div id="interno">
                            <div id="iniciarsesion">
                                <h3><span>Iniciar sesión</span></h3>
                                <form action="operariniciosesion.php" method="post" onsubmit="return validarIdentificacion(this);">
                                    <div class="columna2">
                                        <div><label class="columna1">E-mail</label><input type="text" value="" name="email" class="textinput" /></div>
                                        <div><label class="columna1">Contraseña</label><input type="password" value="" name="contrasena" class="textinput" /></div>
                                        <div><label class="columna1"></label><input type="checkbox" value="on" name="recordar" /> <span>Recordarme en este equipo</span></div>
                                        <div><label class="columna1"></label><input type="submit" value="Iniciar sesión" class="freshbutton-big"/><span> o <a href="registrarse.php">registrarse</a> para reservar pista</span></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
<?php
baseInferior();
?>
