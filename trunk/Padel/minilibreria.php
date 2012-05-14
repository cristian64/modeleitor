<?php
    session_start();

    require_once 'BD.php';
    BD::espeficarDatos("localhost", "root", "8520", "padel");
    require_once 'ENUsuario.php';
    require_once 'ENReserva.php';

    function accesoValido()
    {
        if ($_SESSION["conectado"] != "si")
        {
            header("location: index.php");
            exit();
        }
    }

    function filtrarCadena($cadena)
    {
        if ($cadena != NULL)
        {
            if (is_string($cadena) || is_numeric($cadena))
            {
                $cadena = str_replace("'", "", $cadena);
                $cadena = str_replace("\\", "", $cadena);
                $cadena = str_replace("\"", "", $cadena);
                $cadena = str_replace("=", "", $cadena);
                $cadena = str_replace(">", "", $cadena);
                $cadena = str_replace("<", "", $cadena);
                $cadena = str_replace("\/", "", $cadena);
                $cadena = str_replace("/", "", $cadena);
                $cadena = str_replace("%", "", $cadena);
                $cadena = str_replace(";", ":", $cadena);
                $cadena = str_replace("|", "", $cadena);
                $cadena = str_replace("&", "", $cadena);
                return $cadena;
            }
        }
        return "";
    }

    function rellenar($cadena,$caracter,$digitos)
    {
        while (strlen($cadena)<$digitos)
        {
            $cadena = "$caracter$cadena";
        }
        return $cadena;
    }

    /**
     * Cambia el formato de la fecha.
     * @param string $fecha Fecha con un formato YYYY-MM-DD.
     * @return string Fecha con el formato DD/MM/YYYY.
     */
    function cambiarFormatoFecha($fecha)
    {
        $fecha = split("-", $fecha);
        return $fecha[2]."/".$fecha[1]."/".$fecha[0];
    }
?>
