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
    private $reservable;

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
    
    public function getFechaFin()
    {
        return $this->fecha_fin;
    }

    public function setFechaFin($fecha)
    {
        $this->fecha_fin = new DateTime($fecha);
    }
    
    public function getDuracion()
    {
        $interval = $this->fecha_inicio->diff($this->fecha_fin, true);
        return $interval->h * 60 + $interval->i;
    }
    
    public function getReservable()
    {
        return $this->reservable;
    }
    
    public function setReservable($reservable)
    {
        $this->reservable = $reservable == 0 || $reservable == "0" ? 0 : 1;
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
        $this->reservable = 1;
    }

    public function toString() {
        return "----- RESERVA :: $this->id :: $this->id_usuario :: $this->id_pista :: ".$this->fecha_inicio->format("d/m/Y H:i:s")." :: ".$this->fecha_fin->format("d/m/Y H:i:s")." :: ".$this->getDuracion()." minutos :: ".($this->reservable ? "RESERVABLE" : "NO RESERVABLE")."-----<br />";
    }

    private static function obtenerDatos($fila) {
        $reserva = new ENReserva;
        $reserva->id = $fila[0];
        $reserva->id_usuario = $fila[1];
        $reserva->id_pista = $fila[2];
        $reserva->fecha_inicio = new DateTime($fila[3]);
        $reserva->fecha_fin = new DateTime($fila[4]);
        $reserva->reservable = $fila[5];
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
                        echo "ENReserva::obtenerTodos() Reserva nula nº $contador";
                    }
                }

                BD::desconectar();
            }
            else
            {
                echo "ENReserva::obtenerTodos()" . mysql_error();
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            echo "ENReserva::obtenerTodos()" . $e->getMessage();
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
                        echo "ENReserva::obtenerPorId() Reserva nula $id";
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                echo "ENReserva::obtenerPorId() " . mysql_error();
            }
        }
        catch (Exception $e)
        {
            $usuario = NULL;
            echo "ENReserva::obtenerPorId() " . $e->getMessage();
        }

        return $usuario;
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
                
                $sentencia = "select id from reservas where id != '".$this->id."' and id_pista = '".$this->id_pista."' and ((fecha_inicio <= '$fechaInicioStr' and '$fechaInicioStr' < fecha_fin) or (fecha_inicio < '$fechaFinStr' and '$fechaFinStr' <= fecha_fin))";
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
                    echo "ENReserva::comprobarDisponibilidad() " . mysql_error();
                }
                    
                if ($disponible)
                {
                    $sentencia = "insert into reservas (id_usuario, id_pista, fecha_inicio, fecha_fin, reservable)";
                    $sentencia = "$sentencia values ('".$this->id_usuario."', '".$this->id_pista."', '".$fechaInicioStr."', '".$fechaFinStr."', '".$this->reservable."');\n";

                    $resultado = mysql_query($sentencia, $conexion);

                    if ($resultado)
                    {
                        // Obtenemos el identificador asignado al usuario recién creado.
                        $sentencia = "select id from reservas where id_usuario = '" . $this->id_usuario . "' and id_pista = '" . $this->id_pista . "' and fecha_inicio = '".$fechaInicioStr."' and reservable = '".$this->reservable."'";
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
                            echo "ENReserva::guardar() " . mysql_error();
                        }
                    }
                    else
                    {
                        echo "ENReserva::guardar() " . mysql_error();
                    }
                }
                
                $sentencia = "UNLOCK TABLES;\n";
                mysql_query($sentencia, $conexion);

                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                echo "ENReserva::guardar() " . $e->getMessage();
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
                $sentencia = "select id from reservas where id != '".$this->id."' and id_pista = '".$this->id_pista."' and ((fecha_inicio <= '$fechaInicioStr' and '$fechaInicioStr' < fecha_fin) or (fecha_inicio < '$fechaFinStr' and '$fechaFinStr' <= fecha_fin))";
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
                    echo "ENReserva::comprobarDisponibilidad() " . mysql_error();
                }

                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                echo "ENReserva::comprobarDisponibilidad() " . $e->getMessage();
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
				echo "ENReserva::borrarPorId(id)>".mysql_error();
			}

			BD::desconectar($conexion);
		}
		catch (Exception $e)
		{
			echo "ENReserva::borrarPorId(id) ".$e->getMessage();
		}

		return $borrado;
	}
}

?>