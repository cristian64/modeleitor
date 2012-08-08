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

baseSuperior("Estadísticas");
?>
        <div id="estadisticas">
            <h3><span>Estadísticas</span></h3>
            <div id="lineas">
                <div><span class="descripcion">Reservas totales: </span><span class="dato"><?php echo ENReserva::contar(); ?></span></div>
                <div><span class="descripcion">Reservas para hoy: </span><span class="dato"><?php echo ENReserva::contarHoy(); ?></span></div>
                <div><span class="descripcion">Reservas durante los últimos 7 días: </span><span class="dato"><?php echo ENReserva::contarUltimos7(); ?></span></div>
                <div><span class="descripcion">Reservas durante los próximos 7 días: </span><span class="dato"><?php echo ENReserva::contarProximos7(); ?></span></div>
                <div><span class="descripcion">Tiempo total de reservas: </span><span class="dato"><?php echo ENReserva::totalMinutos(); ?> minutos</span></div>
                <div><span class="descripcion">Tiempo medio por reserva: </span><span class="dato"><?php echo round(ENReserva::totalMinutos() / ENReserva::contar()); ?> minutos por reserva</span></div>
                <div><span class="descripcion">Usuarios registrados: </span><span class="dato"><?php echo ENUsuario::contar(); ?></span></div>
                <div><span class="descripcion">Usuarios registrados durante los últimos 7 días: </span><span class="dato"><?php echo ENUsuario::contarUltimos7(); ?></span></div>
            </div>
            
        </div>
<?php
baseInferior();
?>
