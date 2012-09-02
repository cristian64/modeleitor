<?php

require_once 'minilibreria.php';

class ENAcceso
{
    private static $MINUTES = 5;
    private static $MAX_TRIES = 5;
    
    public static function save($ip, $id_usuario, $exito)
    {
        $ip = secure(utf8_decode($ip));
        $id_usuario = secure(utf8_decode($id_usuario));
        
        try
        {
            $sentencia = "insert into accesos (ip, fecha, id_usuario, exito) values ('$ip', now(), '$id_usuario', '".($exito ? 1 : 0)."');";
            $resultado = mysql_query($sentencia, BD::conectar());
            if (!$resultado)
            {
                debug("ENAcceso::save() ".mysql_error());
                BD::desconectar();
                return false;
            }

            BD::desconectar();
            return true;
        }
        catch (Exception $e)
        {
            debug("ENAcceso::save()" . $e->getMessage());
        }
        return false;
    }
    
    public static function isGranted($ip)
    {
        $granted = false;
        $ip = secure(utf8_decode($ip));

        try
        {
            $sentencia = "select count(*) from accesos where ip = '$ip' and exito = 0 and fecha > now() - INTERVAL ".self::$MINUTES." MINUTE";
            $resultado = mysql_query($sentencia, BD::conectar());
            if ($resultado)
            {
                $fila = mysql_fetch_array($resultado);
                if ($fila)
                {
                    $granted = $fila[0] < self::$MAX_TRIES;
                }
            }
            else
            {
                debug("ENAcceso::isGranted() ".mysql_error());
            }

            BD::desconectar();
        }
        catch (Exception $e)
        {
            debug("ENAcceso::isGranted()" . $e->getMessage());
        }

        return $granted;
    }
}

?>
