<?php

    require_once 'minilibreria.php';
    
    $usuario = getUsuario();
    if ($usuario == null)
    {
        $_SESSION["mensaje_aviso"] = "Tu sesión ha caducado. Debes iniciar sesión antes de poder reservar pista.";
        header("location: iniciarsesion.php");
        exit();
    }
    
    $scroll = getPost("scroll");
    if ($scroll != "")
        $scroll = "&scroll=".$scroll;
    
    // Se procesan los parámetros que llegan por post.
    $diaoculto = getPost("diaoculto");
    $dia = getPost("dia");
    $desde = getPost("desde");
    $hasta = getPost("hasta");
    $pista = intval(getPost("pista"));
    $proximos = 0;
    if ($usuario->getAdmin())
    {
        $reservable = isset($_POST['bloquear']) ? false : true;
        $proximos = getPost("proximos");
    }

    $reservasHoy = ENReserva::obtenerPorUsuarioHoy($usuario->getId());
    if (count($reservasHoy) >= $RESERVASPORDIA && !$usuario->getAdmin())
    {
        $_SESSION["mensaje_error"] = "No se puede reservar más de $RESERVASPORDIA veces durante un mismo día";
        header("location: reservar.php?dia=$diaoculto".$scroll);
        exit();
    }
    
    if ($pista < 1 || $pista > 6)
    {
        $_SESSION["mensaje_error"] = "La pista seleccionada no existe";
        header("location: reservar.php?dia=$diaoculto");
        exit();
    }
    
    $fechaInicio = DateTime::createFromFormat("d/m/Y H:i", $dia . " " . $desde);    
    $fechaFin = DateTime::createFromFormat("d/m/Y H:i", $dia . " " . $hasta);
    if ($fechaInicio == false || $fechaFin == false)
    {
        $_SESSION["mensaje_error"] = "La fecha elegida es incorrecta";
        header("location: reservar.php?dia=$diaoculto".$scroll);
        exit();
    }
    
    if ($fechaFin < $fechaInicio)
    {
        $fechaFin = DateTime::createFromFormat("d/m/Y H:i", $dia . " " . $hasta);
        $fechaFin->add(new DateInterval("P1D"));
    }
    
    $ahora = new DateTime();
    $limiteMaximo = clone $ahora;
    $limiteMaximo->add(new DateInterval("P".$PERIODORESERVA."D"));
    $limiteMaximo->setTime(0, 0, 0);
    $limiteMaximo->add(new DateInterval("P1D"));
    if (($ahora < $fechaInicio && $fechaInicio <= $limiteMaximo && $ahora < $fechaFin && $fechaFin <= $limiteMaximo)  || $usuario->getAdmin())
    {
    }
    else
    {
        $_SESSION["mensaje_error"] = "La fecha elegida no se encuentra en un periodo reservable";
        header("location: reservar.php?dia=$diaoculto".$scroll);
        exit();
    }        
    
    $reserva = new ENReserva();
    $reserva->setIdPista($pista);
    $reserva->setIdUsuario($usuario->getId());
    $reserva->setFechaInicioDateTime($fechaInicio);
    $reserva->setFechaFinDateTime($fechaFin);
    
    if ($usuario->getAdmin())
        $reserva->setReservable($reservable);
    
    if ($reserva->getDuracion() > ($usuario->getAdmin() ? $MAXDURACION_ADMIN : $MAXDURACION))
    {
        $_SESSION["mensaje_error"] = "La duración de la reserva supera los ".($usuario->getAdmin() ? $MAXDURACION_ADMIN : $MAXDURACION)." minutos máximos";
        header("location: reservar.php?dia=$diaoculto".$scroll);
        exit();
    }
    
    $duracionHoy = 0;
    foreach ($reservasHoy as $r)
        $duracionHoy += $r->getDuracion();
    if ($duracionHoy + $reserva->getDuracion() > $MINUTOSPORDIA && !$usuario->getAdmin())
    {
        $_SESSION["mensaje_error"] = "No se pueden reservar más de $MINUTOSPORDIA minutos durante un mismo día";
        header("location: reservar.php?dia=$diaoculto".$scroll);
        exit();
    }
    
    if ($reserva->comprobarDisponibilidad())
    {
        $reservada = $reserva->guardar();
        if ($reservada)
        {
            $_SESSION["mensaje_exito"] = "La reserva se ha realizado correctamente";
            if (emailReserva($usuario->getEmail(), $usuario, $reserva))
            {
                $_SESSION["mensaje_info"] = "Recibirás un e-mail con el resumen de la reserva";            
            }
            emailReserva("beatriz@autofima.com", $usuario, $reserva);
            emailReserva("Santiago@autofima.com", $usuario, $reserva);
            emailReserva("fran@padelelche.com", $usuario, $reserva);
            
            $nueva = $reserva->copiar();
            $reservasProximas = 0;
            for ($i = 0; $i < $proximos; $i++)
            {
                $nueva = $nueva->copiar();
                $nueva->getFechaInicio()->add(new DateInterval("P1D"));
                $nueva->getFechaFin()->add(new DateInterval("P1D"));
                if ($nueva->comprobarDisponibilidad())
                {
                    if ($nueva->guardar())
                        $reservasProximas++;
                }
            }
            
            if ($reservasProximas > 0)
            {
                $_SESSION["mensaje_exito"] = "La reserva se ha realizado correctamente (también se ha reservado para los próximos $reservasProximas días)";
            }
            
            header("location: reservar.php?dia=$diaoculto".$scroll);
        }
        else
        {
            $_SESSION["mensaje_error"] = "Ocurrió un fallo inesperado y la reserva no se pudo completar. Por favor, vuelve a intentarlo.";
            header("location: reservar.php?dia=$diaoculto".$scroll);
        }
    }
    else
    {
        $_SESSION["mensaje_error"] = "La reserva se no pudo completar porque alguien ha reservado ya este horario";
        header("location: reservar.php?dia=$diaoculto".$scroll);
    }
?>
