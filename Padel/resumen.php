<?php
include_once "base.php";

$usuario = getUsuario();
if ($usuario == null)
{
    $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder ver los datos.";
    header("location: iniciarsesion.php");
    exit();
}

if (!$usuario->getAdmin())
{
    $_SESSION["mensaje_error"] = "Esta sección necesita privilegios de administrador";
    header("location: index.php");
    exit();
}

baseSuperior("Resumen del sistema");
?>
        <div id="resumen">
            <h3><span>Resumen del sistema</span></h3>
            <div id="lineasresumen">
                <div><span class="descripcion">Reservas totales: </span><span class="dato">103</span></div>
                <div><span class="descripcion">Reservas para hoy: </span><span class="dato">6</span></div>
                <div><span class="descripcion">Reservas durante los últimos 7 días: </span><span class="dato">59</span></div>
                <div><span class="descripcion">Tiempo total de reservas: </span><span class="dato">581230 minutos</span></div>
                <div><span class="descripcion">Tiempo medio por reserva: </span><span class="dato">90 minutos</span></div>
                <div><span class="descripcion">Usuarios registrados: </span><span class="dato">507</span></div>
                <div><span class="descripcion">Usuarios registrados durante los últimos 7 días: </span><span class="dato">21</span></div>
            </div>
            
        </div>
<?php
baseInferior();
?>
