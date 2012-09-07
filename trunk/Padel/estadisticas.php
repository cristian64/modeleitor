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
    header("location: index.php");
    exit();
}

if ($usuario->getId() != 1 && $usuario->getId() != 2 && $usuario->getId() != 25 && $usuario->getId() != 30)
{
    header("location: index.php");
    exit();
}

baseSuperior("Estadísticas");
?>
        <div id="estadisticas">
            <h3><span>Estadísticas</span></h3>
            <table>
                <tr>
                    <td>
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
                    </td>
                    <td>
                        
                    </td>
                </tr>
            </table>            
        </div>
<?php
baseInferior();
?>
