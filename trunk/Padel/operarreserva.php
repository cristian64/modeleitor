<?php

    require_once 'minilibreria.php';
    
    if (isset($_SESSION["usuario"]))
        $usuario = unserialize($_SESSION["usuario"]);
    
    // Se procesan los parámetros que llegan por post.
    $dia = $_POST["dia"];
    $desde = $_POST["desde"];
    $hasta = $_POST["hasta"];
    $pista = $_POST["pista"];    
    
    $reserva = new ENReserva();
    $reserva->setIdPista($pista);
    $reserva->setIdUsuario($usuario->getId());
    $reserva->setFechaInicioDateTime(DateTime::createFromFormat("d/m/Y H:i", $dia . " " . $desde));
    if ($hasta == "00:00")
    {
        $fechaFin = DateTime::createFromFormat("d/m/Y H:i", $dia . " " . $hasta);
        $fechaFin->add(new DateInterval("P1D"));
        $reserva->setFechaFinDateTime($fechaFin);
    }
    else
        $reserva->setFechaFinDateTime(DateTime::createFromFormat("d/m/Y H:i", $dia . " " . $hasta));
    
    /*echo $reserva->toString();
    echo "disponible: ".$reserva->comprobarDisponibilidad();
    exit();*/
    
    if ($reserva->comprobarDisponibilidad())
    {
        $reservada = $reserva->guardar();
        if ($reservada)
        {
            header("location: reservar.php?exito=La reserva de ha realizado correctamente");
        }
        else
        {
            header("location: reservar.php?error=Ocurrió un fallo inesperado y la reserva no se pudo completar. Por favor, vuelve a intentarlo.");
        }            
    }
    else
    {
        header("location: reservar.php?error=La reserva se no pudo completar porque alguien ha reservado ya este horario");
    }
?>
