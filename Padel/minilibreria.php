<?php
    session_start();

    require_once 'constantes.php';
    require_once 'BD.php';
    BD::espeficarDatos($BDURL, $BDUSER, $BDPASSWORD, $BDNAME);
    require_once 'ENUsuario.php';
    require_once 'ENReserva.php';
    
    function getUsuario()
    {
        $usuario = null;
        if (!isset($_SESSION["usuario"]))
        {
            // Si no hay ninguna sesión abierta, intentamos abrir una desde las cookies.
            if (isset($_COOKIE["email"]) && isset($_COOKIE["contrasena"]))
            {
                $usuario2 = ENUsuario::obtenerPorEmail($_COOKIE["email"]);
                if ($usuario2 != null)
                {
                    if ($usuario2->getContrasena() == $_COOKIE["contrasena"])
                    {
                        $_SESSION["usuario"] = serialize($usuario2);
                        $usuario = $usuario2;
                    }
                }
            }
        }
        else
        {
            $usuario = unserialize($_SESSION["usuario"]);
        }
        return $usuario;
    }
    
    function getPost($key)
    {
        return (is_string($key) && isset($_POST[$key])) ? $_POST[$key] : "";
    }
    
    function getGet($key)
    {
        return (is_string($key) && isset($_GET[$key])) ? $_GET[$key] : "";
    }
    
    function getSession($key)
    {
        return (is_string($key) && isset($_SESSION[$key])) ? $_SESSION[$key] : "";
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
