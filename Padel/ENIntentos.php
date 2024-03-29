<?php

require_once 'minilibreria.php';

class ENIntentos
{
    public static $MINUTOS = 10;
    public static $MAXINTENTOS = 5;
    
    public static function guardar($ip)
    {
        $ip = filtrarCadena($ip);
        
        try
        {
            $sentencia = "insert into intentos (ip, fecha) values ('$ip', now());";
            $resultado = mysql_query($sentencia, BD::conectar());
            if (!$resultado)
            {
                echo "ENIntentos::guardar() ".mysql_error();
                return false;
            }

            BD::desconectar();
            return true;
        }
        catch (Exception $e)
        {
            debug("ENIntentos::guardar()" . $e->getMessage());
        }
        return false;
    }
    
    public static function contar($ip)
    {
        $intentos = 0;
        $ip = filtrarCadena($ip);

        try
        {
            $sentencia = "select count(*) from intentos where ip = '$ip' and fecha > now() - INTERVAL ".self::$MINUTOS." MINUTE";
            $resultado = mysql_query($sentencia, BD::conectar());
            if ($resultado)
            {
                $fila = mysql_fetch_array($resultado);
                if ($fila)
                {
                    $intentos = $fila[0];
                }
            }
            else
            {
                debug("ENIntentos::contar() ".mysql_error());
            }

            BD::desconectar();
        }
        catch (Exception $e)
        {
            debug("ENIntentos::contar()" . $e->getMessage());
        }

        return $intentos;
    }
    
    public static function limpiar()
    {
        try
        {
            $sentencia = "delete from intentos where fecha < now() - INTERVAL ".self::$MINUTOS." MINUTE";
            $resultado = mysql_query($sentencia, BD::conectar());
            if (!$resultado)
            {
                debug("ENIntentos::limpiar() ".mysql_error());
                return false;
            }

            BD::desconectar();
            return true;
        }
        catch (Exception $e)
        {
            debug("ENIntentos::limpiar()" . $e->getMessage());
        }
        return false;
    }
}

?>