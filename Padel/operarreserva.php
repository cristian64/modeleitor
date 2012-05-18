<?php

    require_once 'minilibreria.php';
    
    $usuario = getUsuario();
    if ($usuario == null)
    {
        header("location: iniciarsesion.php?aviso=Tu sesión ha caducado. Debes iniciar sesión antes de poder reservar pista.");
        exit();
    }
    
    // Se procesan los parámetros que llegan por post.
    $dia = $_POST["dia"];
    $desde = $_POST["desde"];
    $hasta = $_POST["hasta"];
    $pista = $_POST["pista"];   
    $reservable = $_POST["reservable"] == 0 ? 0 : 1;
    
    $reserva = new ENReserva();
    $reserva->setIdPista($pista);
    $reserva->setReservable($reservable);
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
    
    if ($reserva->getFechaInicio() >= $reserva->getFechaFin() || $reserva->getDuracion() > ($usuario->getAdmin() == 1 ? $MAXDURACION_ADMIN : $MAXDURACION))
    {
        header("location: reservar.php?dia=$dia&error=Ocurrió un fallo inesperado y la reserva no se pudo completar. Por favor, vuelve a intentarlo.");
        exit();
    }
    
    if ($reserva->comprobarDisponibilidad())
    {
        $reservada = $reserva->guardar();
        if ($reservada)
        {
            header("location: reservar.php?dia=$dia&exito=La reserva se ha realizado correctamente");
        }
        else
        {
            header("location: reservar.php?dia=$dia&error=Ocurrió un fallo inesperado y la reserva no se pudo completar. Por favor, vuelve a intentarlo.");
        }
    }
    else
    {
        header("location: reservar.php?dia=$dia&error=La reserva se no pudo completar porque alguien ha reservado ya este horario");
    }
?>
