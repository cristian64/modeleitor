<?php
include_once "base.php";

if (getUsuario() != null)
{
    header("location: .");
    exit();
}
    
baseSuperior("Clientes");
?>
<div id="externo">
<div id="interno">
                            <div>
                                <h3><span>Área de clientes</span></h3>
                                <form action="iniciarsesion" method="post">
                                    <table class="guapo-form">
                                        <tr>
                                            <td class="guapo-label">E-mail</td>
                                            <td class="guapo-input"><input type="text" value="" name="email" /></td>
                                        </tr>
                                        <tr>
                                            <td class="guapo-label">Contraseña</td>
                                            <td class="guapo-input"><input type="password" value="" name="contrasena" /></td>
                                        </tr>
                                        <tr>
                                            <td class="guapo-label"></td>
                                            <td class="guapo-input"><input type="checkbox" value="on" name="recordar" /> Recordarme en este equipo</td>
                                        </tr>
                                        <tr>
                                            <td class="guapo-label"></td>
                                            <td class="guapo-input"><input type="submit" value="Iniciar sesión" /></td>
                                        </tr>
                                    </table>
                                </form>
                                <br />
                                <br />
                                <br />
                                <div>Si todavía no eres cliente, debes crear una nueva cuenta. <br /><br /><a class="btnverde" href="crearcuenta">Soy un nuevo cliente</a></div>
                            </div>
</div></div>
<?php
baseInferior();
?>
