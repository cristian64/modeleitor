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
    $diaoculto = getPost("diaoculto");
    $dia = getPost("dia");
    $desde = getPost("desde");
    $hasta = getPost("hasta");
    $pista = intval(getPost("pista"));
    if ($usuario->getAdmin())
        $reservable = getPost("reservable") == "0" ? false : true;

    
    if ($pista < 1 || $pista > 6)
    {
        $_SESSION["mensaje_error"] = "La pista seleccionada no está disponible";
        header("location: reservar.php?dia=$diaoculto");
        exit();
    }
    
    $fechaInicio = DateTime::createFromFormat("d/m/Y H:i", $dia . " " . $desde);    
    $fechaFin = DateTime::createFromFormat("d/m/Y H:i", $dia . " " . $hasta);
    if ($fechaInicio == false || $fechaFin == false)
    {
        $_SESSION["mensaje_error"] = "La fecha indicada es incorrecta";
        header("location: reservar.php?dia=$diaoculto");
        exit();
    }
    
    if ($fechaFin < $fechaInicio)
    {
        $fechaFin = DateTime::createFromFormat("d/m/Y H:i", $dia . " " . $hasta);
        $fechaFin->add(new DateInterval("P1D"));
    }
    
    $reserva = new ENReserva();
    $reserva->setIdPista($pista);
    $reserva->setIdUsuario($usuario->getId());
    $reserva->setFechaInicioDateTime($fechaInicio);
    $reserva->setFechaFinDateTime($fechaFin);
    
    $_SESSION["mensaje_info"] = $reserva->toString();
    
    if ($usuario->getAdmin())
        $reserva->setReservable($reservable);
    
    if ($reserva->getDuracion() > ($usuario->getAdmin() ? $MAXDURACION_ADMIN : $MAXDURACION))
    {
        $_SESSION["mensaje_error"] = "La duración de la reserva supera los ".($usuario->getAdmin() ? $MAXDURACION_ADMIN : $MAXDURACION)." minutos máximos";
        header("location: reservar.php?dia=$diaoculto");
        exit();
    }
    
    if ($reserva->comprobarDisponibilidad())
    {
        $reservada = $reserva->guardar();
        if ($reservada)
        {
            $_SESSION["mensaje_exito"] = "La reserva se ha realizado correctamente";
            header("location: reservar.php?dia=$diaoculto");
        }
        else
        {
            $_SESSION["mensaje_error"] = "Ocurrió un fallo inesperado y la reserva no se pudo completar. Por favor, vuelve a intentarlo.";
            header("location: reservar.php?dia=$diaoculto");
        }
    }
    else
    {
        $_SESSION["mensaje_error"] = "La reserva se no pudo completar porque alguien ha reservado ya este horario";
        header("location: reservar.php?dia=$diaoculto");
    }
?>
