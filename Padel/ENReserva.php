<?php

require_once 'minilibreria.php';

/**
 * Description of ENReserva
 *
 * @author cristian
 */
class ENReserva
{
    private $id;
    private $id_usuario;
    private $id_pista;
    private $fecha_inicio;
    private $fecha_fin;
    private $fecha_realizacion;
    private $reservable;
    private $notas;
    private $cobrado;

    public function getId()
    {
        return $this->id;
    }
    
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }
    
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function getIdPista()
    {
        return $this->id_pista;
    }
    
    public function setIdPista($id_pista)
    {
        $this->id_pista = $id_pista;
    }
    
    public function getFechaInicio()
    {
        return $this->fecha_inicio;
    }

    public function setFechaInicio($fecha)
    {
        $this->fecha_inicio = new DateTime($fecha);
    }
    
    public function setFechaInicioDateTime($fecha)
    {
        if (is_object($fecha) && get_class($fecha) == "DateTime")
            $this->fecha_inicio = $fecha;
    }
    
    public function getFechaFin()
    {
        return $this->fecha_fin;
    }

    public function setFechaFin($fecha)
    {
        $this->fecha_fin = new DateTime($fecha);
    }
    
    public function setFechaFinDateTime($fecha)
    {
        if (is_object($fecha) && get_class($fecha) == "DateTime")
            $this->fecha_fin = $fecha;
    }
    
    public function getFechaRealizacion()
    {
        return $this->fecha_realizacion;
    }
    
    public function getDuracion()
    {
        $interval = $this->fecha_inicio->diff($this->fecha_fin, true);
        return $interval->h * 60 + $interval->i;
    }
    
    public function getEstado()
    {
        if ($this->fecha_inicio > new DateTime())
            return "Pendiente";
        else if ($this->fecha_fin < new DateTime())
            return "Finalizada";
        else
            return "En curso";
    }
    
    public function getCuentaAtrasString()
    {
        $now = new DateTime();
        $interval = $this->fecha_inicio->diff($now, true);
        $dias = $interval->y * 365 + $interval->m * 30 + $interval->d;
        $horas = $interval->h;
        $minutos = $interval->i;
        $segundos = $interval->s;
        
        $cadena = "";
        if ($dias == 1)
            $cadena = $cadena."$dias día ";
        if ($dias > 1)
            $cadena = $cadena."$dias días ";
        if ($horas == 1)
            $cadena = $cadena."$horas hora ";
        if ($horas > 1)
            $cadena = $cadena."$horas horas ";
        if ($minutos == 1 && $dias == 0)
            $cadena = $cadena."$minutos minuto ";
        if ($minutos > 1 && $dias == 0)
            $cadena = $cadena."$minutos minutos ";
        if ($segundos == 1 && $dias == 0 && $horas == 0)
            $cadena = $cadena."$segundos segundo ";
        if ($segundos > 1 && $dias == 0 && $horas == 0)
            $cadena = $cadena."$segundos segundos ";
        
        return "faltan ".$cadena."para que comience";
        //o "La reserva ha comenzado hace X minutos";
        //o "La reserva acabó hace X días";
    }
    
    public function getCuentaAtras()
    {
        $now = new DateTime();
        $interval = $this->fecha_inicio->diff($now, true);
        if ($now < $this->fecha_inicio)
            return $interval->y * 365 * 30 * 24 * 60 + $interval->m * 30 * 24 * 60 + $interval->d * 24 * 60 + $interval->h * 60 + $interval->i;
        else
            return -1 * ($interval->y * 365 * 30 * 24 * 60 + $interval->m * 30 * 24 * 60 + $interval->d * 24 * 60 + $interval->h * 60 + $interval->i);
    }
    
    public function getReservable()
    {
        return $this->reservable;
    }
    
    public function setReservable($reservable)
    {
        $this->reservable = $reservable;
    }
    
    public function getNotas()
    {
        return $this->notas;
    }
    
    public function setNotas($notas)
    {
        $this->notas = filtrarCadena($notas);
    }
    
    public function getCobrado()
    {
        return $this->cobrado;
    }
    
    public function setCobrado($cobrado)
    {
        $this->cobrado = filtrarCadena($cobrado);
    }

    /**
     * Constructor de la clase.
     */
    public function __construct()
    {
        $this->id = 0;
        $this->id_usuario = 0;
        $this->id_pista = 0;
        $this->fecha_inicio = new DateTime();
        $this->fecha_fin = new DateTime();
        $this->fecha_realizacion = new DateTime();
        $this->reservable = true;
        $this->notas = "";
        $this->cobrado = 0;
    }

    public function toString() {
        return "----- RESERVA :: $this->id :: $this->id_usuario :: $this->id_pista :: ".$this->fecha_inicio->format("d/m/Y H:i:s")." :: ".$this->fecha_fin->format("d/m/Y H:i:s")." :: ".$this->getDuracion()." minutos :: ".$this->fecha_realizacion->format("d/m/Y H:i:s")." ::".($this->reservable ? "RESERVABLE" : "NO RESERVABLE")."-----<br />";
    }

    private static function obtenerDatos($fila) {
        $reserva = new ENReserva;
        $reserva->id = $fila[0];
        $reserva->id_usuario = $fila[1];
        $reserva->id_pista = $fila[2];
        $reserva->fecha_inicio = new DateTime($fila[3]);
        $reserva->fecha_fin = new DateTime($fila[4]);
        $reserva->fecha_realizacion = new DateTime($fila[5]);
        $reserva->reservable = ($fila[6] == "0" || $fila[6] == 0) ? false : true;
        $reserva->notas = utf8_encode($fila[7]);
        $reserva->cobrado = $fila[8];
        return $reserva;
    }

    public static function obtenerTodos()
    {
        $lista = NULL;

        try
        {
            $sentencia = "select * from reservas order by id desc";
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $lista = array();
                $contador = 0;
                while ($fila = mysql_fetch_array($resultado))
                {
                    $reserva = self::obtenerDatos($fila);
                    if ($reserva != NULL)
                    {
                        $lista[$contador++] = $reserva;
                    }
                    else
                    {
                        debug("ENReserva::obtenerTodos() Reserva nula nº $contador");
                    }
                }

                BD::desconectar();
            }
            else
            {
                debug("ENReserva::obtenerTodos()" . mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            debug("ENReserva::obtenerTodos()" . $e->getMessage());
        }

        return $lista;
    }
    
    public static function obtenerPendientes($filtro = "")
    {
        $lista = NULL;
        $filtro = filtrarCadena(utf8_decode($filtro));

        try
        {
            $ayer = new DateTime();
            $ayer->sub(new DateInterval("P2D"));                
            if ($filtro == "")
            {
                $sentencia = "select * from reservas where date(fecha_inicio) >= '".$ayer->format('Y/m/d')."' order by fecha_inicio asc";
            }
            else
            {   
                $idfiltro = "";
                if (is_numeric($filtro))
                    $idfiltro = "or reservas.id like '%".intval($filtro)."%'";
                
                $sentencia = "select reservas.* from reservas, usuarios where reservas.id_usuario = usuarios.id and (email like '%$filtro%' $idfiltro) and date(fecha_inicio) >= '".$ayer->format('Y/m/d')."' order by fecha_inicio asc";
            }
            
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $lista = array();
                $contador = 0;
                while ($fila = mysql_fetch_array($resultado))
                {
                    $reserva = self::obtenerDatos($fila);
                    if ($reserva != NULL)
                    {
                        $lista[$contador++] = $reserva;
                    }
                    else
                    {
                        debug("ENReserva::obtenerPendientes() Reserva nula nº $contador");
                    }
                }

                BD::desconectar();
            }
            else
            {
                debug("ENReserva::obtenerPendientes()" . mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            debug("ENReserva::obtenerPendientes()" . $e->getMessage());
        }

        return $lista;
    }
    
    public static function obtenerPorUsuario($id_usuario, $cantidad)
    {
        $lista = NULL;
        if (!is_numeric($id_usuario))
            $id_usuario = 0;

        try
        {
            $sentencia = "select * from reservas where id_usuario = '$id_usuario' order by fecha_inicio desc limit $cantidad";
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $lista = array();
                $contador = 0;
                while ($fila = mysql_fetch_array($resultado))
                {
                    $reserva = self::obtenerDatos($fila);
                    if ($reserva != NULL)
                    {
                        $lista[$contador++] = $reserva;
                    }
                    else
                    {
                        debug("ENReserva::obtenerPorUsuario() Reserva nula nº $contador");
                    }
                }

                BD::desconectar();
            }
            else
            {
                debug("ENReserva::obtenerPorUsuario()" . mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            debug("ENReserva::obtenerPorUsuario()" . $e->getMessage());
        }

        return $lista;
    }
    
    public static function contar()
    {
        $cantidad = 0;

        try
        {
            $sentencia = "select count(*) from reservas where reservable = 1";
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $fila = mysql_fetch_array($resultado);
                if ($fila)
                {
                    $cantidad = $fila[0];
                }

                BD::desconectar();
            }
            else
            {
                debug("ENReserva::contar()" . mysql_error());
            }
        }
        catch (Exception $e)
        {
            $cantidad = 0;
            debug("ENReserva::contar()" . $e->getMessage());
        }

        return $cantidad;
    }
    
    public static function contarHoy()
    {
        $cantidad = 0;

        try
        {
            $ahora = new DateTime();
            $sentencia = "select count(*) from reservas where date(fecha_inicio) = '".$ahora->format('Y/m/d')."' and reservable = 1";
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $fila = mysql_fetch_array($resultado);
                if ($fila)
                {
                    $cantidad = $fila[0];
                }

                BD::desconectar();
            }
            else
            {
                debug("ENReserva::contarHoy()" . mysql_error());
            }
        }
        catch (Exception $e)
        {
            $cantidad = 0;
            debug("ENReserva::contarHoy()" . $e->getMessage());
        }

        return $cantidad;
    }
    
    public static function contarUltimos7()
    {
        $cantidad = 0;

        try
        {
            $ahora = new DateTime();
            $ahora->sub(new DateInterval("P7D"));
            $ahora2 = new DateTime();
            $ahora2->add(new DateInterval("P1D"));
            $sentencia = "select count(*) from reservas where date(fecha_inicio) >= '".$ahora->format('Y/m/d')."' and date(fecha_fin) < '".$ahora2->format('Y/m/d')."' and reservable = 1";
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $fila = mysql_fetch_array($resultado);
                if ($fila)
                {
                    $cantidad = $fila[0];
                }

                BD::desconectar();
            }
            else
            {
                debug("ENReserva::contarUltimos7()" . mysql_error());
            }
        }
        catch (Exception $e)
        {
            $cantidad = 0;
            debug("ENReserva::contarUltimos7()" . $e->getMessage());
        }

        return $cantidad;
    }
    
    public static function contarProximos7()
    {
        $cantidad = 0;

        try
        {
            $ahora = new DateTime();
            $ahora2 = new DateTime();
            $ahora2->add(new DateInterval("P7D"));
            $sentencia = "select count(*) from reservas where date(fecha_inicio) >= '".$ahora->format('Y/m/d')."' and date(fecha_fin) <= '".$ahora2->format('Y/m/d')."' and reservable = 1";
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $fila = mysql_fetch_array($resultado);
                if ($fila)
                {
                    $cantidad = $fila[0];
                }

                BD::desconectar();
            }
            else
            {
                debug("ENReserva::contarProximos7()" . mysql_error());
            }
        }
        catch (Exception $e)
        {
            $cantidad = 0;
            debug("ENReserva::contarProximos7()" . $e->getMessage());
        }

        return $cantidad;
    }
    
    public static function contarPorUsuario($id_usuario)
    {
        $cantidad = 0;
        if (!is_numeric($id_usuario))
            $id_usuario = 0;

        try
        {
            $sentencia = "select count(*) from reservas where id_usuario = '$id_usuario'";
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $fila = mysql_fetch_array($resultado);
                if ($fila)
                {
                    $cantidad = $fila[0];
                }

                BD::desconectar();
            }
            else
            {
                debug("ENReserva::contarPorUsuario()" . mysql_error());
            }
        }
        catch (Exception $e)
        {
            $cantidad = 0;
            debug("ENReserva::contarPorUsuario()" . $e->getMessage());
        }

        return $cantidad;
    }
    
    public static function totalMinutos()
    {
        $cantidad = 0;

        try
        {
            $sentencia = "select sum(TIMESTAMPDIFF(MINUTE, fecha_inicio,fecha_fin)) from reservas where reservable = 1";
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $fila = mysql_fetch_array($resultado);
                if ($fila)
                {
                    $cantidad = $fila[0];
                }

                BD::desconectar();
            }
            else
            {
                debug("ENReserva::totalMinutos()" . mysql_error());
            }
        }
        catch (Exception $e)
        {
            $cantidad = 0;
            debug("ENReserva::totalMinutos()" . $e->getMessage());
        }

        return $cantidad;
    }
    
    public static function obtenerPorUsuarioHoy($id_usuario)
    {
        $lista = NULL;
        if (!is_numeric($id_usuario))
            $id_usuario = 0;

        try
        {
            $ahora = new DateTime();
            $sentencia = "select * from reservas where id_usuario = '$id_usuario' and date(fecha_realizacion) = '".$ahora->format('Y/m/d')."' order by fecha_inicio asc";
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $lista = array();
                $contador = 0;
                while ($fila = mysql_fetch_array($resultado))
                {
                    $reserva = self::obtenerDatos($fila);
                    if ($reserva != NULL)
                    {
                        $lista[$contador++] = $reserva;
                    }
                    else
                    {
                        debug("ENReserva::obtenerPorUsuarioHoy() Reserva nula nº $contador");
                    }
                }

                BD::desconectar();
            }
            else
            {
                debug("ENReserva::obtenerPorUsuarioHoy()" . mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            debug("ENReserva::obtenerPorUsuarioHoy()" . $e->getMessage());
        }

        return $lista;
    }
    
    public static function comprobanteDia($dia)
    {
        $comprobante = NULL;

        try
        {
            $sentencia = "select count(*), max(id) from reservas where date(fecha_inicio) = '".$dia->format('Y/m/d')."'";
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $fila = mysql_fetch_array($resultado);
                {
                    $comprobante = "";
                    $comprobante = $fila[0]."-".$fila[1];
                }

                BD::desconectar();
            }
            else
            {
                debug("ENReserva::comprobanteDia()" . mysql_error());
            }
        }
        catch (Exception $e)
        {
            $comprobante = NULL;
            debug("ENReserva::comprobanteDia()" . $e->getMessage());
        }

        return $comprobante;
    }
    
    public static function obtenerPorPistaDia($id_pista, $dia)
    {
        $lista = NULL;
        if (!is_numeric($id_pista))
            $id_pista = 0;

        try
        {
            $sentencia = "select * from reservas where id_pista = '$id_pista' and date(fecha_inicio) = '".$dia->format('Y/m/d')."' order by fecha_inicio asc";
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $lista = array();
                $contador = 0;
                while ($fila = mysql_fetch_array($resultado))
                {
                    $reserva = self::obtenerDatos($fila);
                    if ($reserva != NULL)
                    {
                        $lista[$contador++] = $reserva;
                    }
                    else
                    {
                        debug("ENReserva::obtenerPorPistaDia() Reserva nula nº $contador");
                    }
                }

                BD::desconectar();
            }
            else
            {
                debug("ENReserva::obtenerPorPistaDia()" . mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            debug("ENReserva::obtenerPorPistaDia()" . $e->getMessage());
        }

        return $lista;
    }

    /**
     * Obtiene un usuario desde la base de datos a partir de su nombre.
     * @param string $nombre Nombre del usuario que se va a obtener.
     * @return ENUsuario Devuelve el usuario con todos sus atributos extraidos desde la base de datos. Devuelve NULL si ocurrió algún error.
     */
    public static function obtenerPorId($id)
    {
        $id = filtrarCadena($id);
        $usuario = NULL;

        try
        {
            $conexion = BD::conectar();
            $sentencia = "select *";
            $sentencia = "$sentencia from reservas";
            $sentencia = "$sentencia where id = '$id'";
            $resultado = mysql_query($sentencia, $conexion);

            if ($resultado)
            {
                $fila = mysql_fetch_array($resultado);
                if ($fila)
                {
                    $usuario = self::obtenerDatos($fila);
                    if ($usuario == NULL)
                    {
                        debug("ENReserva::obtenerPorId() Reserva nula $id");
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                debug("ENReserva::obtenerPorId() " . mysql_error());
            }
        }
        catch (Exception $e)
        {
            $usuario = NULL;
            debug("ENReserva::obtenerPorId() " . $e->getMessage());
        }

        return $usuario;
    }

    public function copiar()
    {
        $nueva = new ENReserva();
        $nueva->setIdUsuario($this->getIdUsuario());
        $nueva->setIdPista($this->getIdPista());
        $nueva->setReservable($this->getReservable());
        $nueva->setNotas($this->getNotas());
        $nueva->setFechaInicioDateTime($this->getFechaInicio());
        $nueva->setFechaFinDateTime($this->getFechaFin());
        return $nueva;
    }

    /**
     * Guarda en la base de datos el usuario que invocó el método.
     * Sólo puede guardarse si no existe en la base de datos. Si ya existe, hay que utilizar el método "actualizar".
     * Es decir, si el usuario es nuevo, utilizarás "guardar". Si ha sido extraido de la base de datos, se utilizará "actualizar".
     * @return bool Devuelve verdadero si se ha guardado correctamente. Falso en caso contrario.
     */
    public function guardar()
    {
        $guardado = false;

        if ($this->id == 0)
        {
            try
            {
                $conexion = BD::conectar();
                
                $fechaInicioStr = $this->fecha_inicio->format("Y/m/d H:i:s");
                $fechaFinStr = $this->fecha_fin->format("Y/m/d H:i:s");

                $disponible = false;
                
                // Insertamos el usuario.
                $sentencia = "LOCK TABLES reservas WRITE;\n";
                mysql_query($sentencia, $conexion);
                
                $sentencia = "select id from reservas where id_pista = '".$this->id_pista."' and ((fecha_inicio <= '$fechaInicioStr' and '$fechaInicioStr' < fecha_fin) or (fecha_inicio < '$fechaFinStr' and '$fechaFinStr' <= fecha_fin))";
                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    $disponible = true;
                    $fila = mysql_fetch_array($resultado);
                    if ($fila)
                    {
                        $disponible = false;
                    }
                }
                else
                {
                    debug("ENReserva::guardar() " . mysql_error());
                }
                    
                if ($disponible)
                {
                    $sentencia = "insert into reservas (id_usuario, id_pista, fecha_inicio, fecha_fin, fecha_realizacion, reservable, notas, cobrado)";
                    $sentencia = "$sentencia values ('".$this->id_usuario."', '".$this->id_pista."', '".$fechaInicioStr."', '".$fechaFinStr."', now(), '".($this->reservable ? "1" : "0")."', '".utf8_decode($this->notas)."', '".$this->cobrado."');\n";

                    $resultado = mysql_query($sentencia, $conexion);

                    if ($resultado)
                    {
                        // Obtenemos el identificador asignado al usuario recién creado.
                        $sentencia = "select id from reservas where id_usuario = '" . $this->id_usuario . "' and id_pista = '" . $this->id_pista . "' and fecha_inicio = '".$fechaInicioStr."'";
                        $resultado = mysql_query($sentencia, $conexion);

                        if ($resultado)
                        {
                            $fila = mysql_fetch_array($resultado);
                            if ($fila)
                            {
                                $this->id = $fila[0];
                                $guardado = true;
                            }
                        }
                        else
                        {
                            debug("ENReserva::guardar() " . mysql_error());
                        }
                    }
                    else
                    {
                        debug("ENReserva::guardar() " . mysql_error());
                    }
                }
                
                $sentencia = "UNLOCK TABLES;\n";
                mysql_query($sentencia, $conexion);

                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                debug("ENReserva::guardar() " . $e->getMessage());
            }
        }

        return $guardado;
    }
    
    public function actualizarCobro()
    {
        $guardado = false;

        if ($this->id > 0)
        {
            try
            {
                $conexion = BD::conectar();

                // Actualizamos el usuario.
                $sentencia = "update reservas set cobrado = '".$this->cobrado."'";
                $sentencia = "$sentencia where id = '".$this->id."'";

                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    $guardado = true;
                }
                else
                {
                    debug("ENReserva::actualizarCobro() ".mysql_error());
                }
                        
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                debug("ENReserva::actualizarCobro() ".$e->getMessage());
            }
        }

        return $guardado;
    }
    
    public function comprobarDisponibilidad()
    {
        $disponible = false;

        if ($this->id == 0)
        {
            try
            {
                $fechaInicioStr = $this->fecha_inicio->format("Y/m/d H:i:s");
                $fechaFinStr = $this->fecha_fin->format("Y/m/d H:i:s");
                
                $conexion = BD::conectar();

                // Insertamos el usuario.
                $sentencia = "select id from reservas where id_pista = '".$this->id_pista."' and ((fecha_inicio <= '$fechaInicioStr' and '$fechaInicioStr' < fecha_fin) or (fecha_inicio < '$fechaFinStr' and '$fechaFinStr' <= fecha_fin))";
                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    $disponible = true;
                    $fila = mysql_fetch_array($resultado);
                    if ($fila)
                    {
                        $disponible = false;
                    }
                }
                else
                {
                    debug("ENReserva::comprobarDisponibilidad() " . mysql_error());
                }

                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                debug("ENReserva::comprobarDisponibilidad() " . $e->getMessage());
            }
        }

        return $disponible;
    }
    
    public static function borrarPorId($id)
    {
        $id = filtrarCadena($id);
        $borrado = false;

        try
        {
            $conexion = BD::conectar();

            $sentencia = "delete from reservas where id = '".$id."'";
            $resultado = mysql_query($sentencia, $conexion);
            if ($resultado)
            {
                $borrado = true;
            }
            else
            {
                debug("ENReserva::borrarPorId(id)>".mysql_error());
            }

            BD::desconectar($conexion);
        }
        catch (Exception $e)
        {
            debug("ENReserva::borrarPorId(id) ".$e->getMessage());
        }

        return $borrado;
    }
}

?>
