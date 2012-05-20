<?php

    require_once 'minilibreria.php';
    
    $usuario = getUsuario();
    if ($usuario == null)
    {
        $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder reservar pista.";
        header("location: iniciarsesion.php");
        exit();
    }
    
    // Se procesan los parámetros que llegan por post.
    $dia = $_POST["dia"];
    $desde = $_POST["desde"];
    $hasta = $_POST["hasta"];
    $pista = $_POST["pista"];   
    $reservable = getPost("reservable") == "0" ? false : true;
    
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
    
    if ($reserva->getFechaInicio() >= $reserva->getFechaFin() || $reserva->getDuracion() > ($usuario->getAdmin() ? $MAXDURACION_ADMIN : $MAXDURACION))
    {
        $_SESSION["mensaje_error"] = "Ocurrió un fallo inesperado y la reserva no se pudo completar. Por favor, vuelve a intentarlo.";
        header("location: reservar.php?dia=$dia");
        exit();
    }
    
    if ($reserva->comprobarDisponibilidad())
    {
        $reservada = $reserva->guardar();
        if ($reservada)
        {
            $_SESSION["mensaje_exito"] = "La reserva se ha realizado correctamente";
            header("location: reservar.php?dia=$dia");
        }
        else
        {
            $_SESSION["mensaje_error"] = "Ocurrió un fallo inesperado y la reserva no se pudo completar. Por favor, vuelve a intentarlo.";
            header("location: reservar.php?dia=$dia");
        }
    }
    else
    {
        $_SESSION["mensaje_error"] = "La reserva se no pudo completar porque alguien ha reservado ya este horario";
        header("location: reservar.php?dia=$dia");
    }
?>
