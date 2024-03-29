<?php
    session_start();

    require_once 'constantes.php';
    require_once 'pistas.php';
    require_once 'BD.php';
    BD::espeficarDatos($BDURL, $BDUSER, $BDPASSWORD, $BDNAME);
    require_once 'ENUsuario.php';
    require_once 'ENReserva.php';
    require_once 'ENIntentos.php';
    require_once 'resize.php';
    
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
        return substr((is_string($key) && isset($_POST[$key])) ? $_POST[$key] : "", 0, 300);
    }
    
    function getGet($key)
    {
        return substr((is_string($key) && isset($_GET[$key])) ? $_GET[$key] : "", 0, 300);
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
                /*$cadena = str_replace("'", "", $cadena);
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
                $cadena = str_replace("&", "", $cadena);*/
                
                $cadena = str_replace("'", "", $cadena);
                $cadena = str_replace("\\", "", $cadena);
                $cadena = str_replace("\"", "", $cadena);
                $cadena = str_replace(">", "", $cadena);
                $cadena = str_replace("<", "", $cadena);
                $cadena = str_replace("\/", "", $cadena);
                $cadena = str_replace("/", "", $cadena);
                $cadena = str_replace("&", "", $cadena);
                return $cadena;
            }
        }
        return "";
    }
    
    function borrar($rutaFichero)
    {
        return $rutaFichero != NULL && file_exists($rutaFichero) && @unlink($rutaFichero);
    }
    
    function crearFicheroFoto($httpPostFile, $directorio)
    {
        //http://emilio.aesinformatica.com/2007/05/03/subir-una-imagen-con-php/
        $creada = false;

        if (is_uploaded_file($httpPostFile['tmp_name']))
        {
            $ahora = new DateTime();
            $nombre = $ahora->format("dmYHis");
            $extension = pathinfo($httpPostFile['name'], PATHINFO_EXTENSION);
            $rutaFoto = $directorio."$nombre."."$extension";
            $rutaMiniatura = $directorio."$nombre.thumb."."$extension";

            // Hay que intentar borrar las anteriores. No importa si falla.
            borrar($rutaFoto);
            borrar($rutaMiniatura);

            // Luego hay que copiar el fichero de la imagen a la ruta de la foto.
            if (@move_uploaded_file($httpPostFile['tmp_name'], $rutaFoto))
            {
                if (@chmod($rutaFoto,0777))
                {
                    $miniatura=new thumbnail($rutaFoto);
                    //$miniatura->size_width(100);
                    //$miniatura->size_height(100);
                    $miniatura->size_auto(300);
                    $miniatura->jpeg_quality(100);
                    $miniatura->save($rutaMiniatura);

                    $creada = true;
                }
            }
        }

        return $creada;
    }
    
    function debug($cadena)
    {
        echo $cadena."<br />";
    }
    
    function depurar($cadena)
    {
        $fp = fopen("debug.txt", 'a');
        fwrite($fp, date("Y/m/d H:i:s")." ".$cadena."\n");
        fclose($fp);
    }

    function rellenar($cadena,$caracter,$digitos)
    {
        while (strlen($cadena)<$digitos)
        {
            $cadena = "$caracter$cadena";
        }
        return $cadena;
    }
    
    function ellipsis($string, $length, $end = '...')
    {
        return (strlen($string) > $length) ? (substr($string, 0, $length - strlen($end)) . $end) : $string;
    }
    
    function email($nombre, $asunto, $email, $mensaje)
    {        
        $cuerpo = "<tr>";
        $cuerpo = $cuerpo."<td><strong>Nombre:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>$nombre</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>Asunto:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>$asunto</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>E-mail:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>$email</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>Mensaje:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>$mensaje</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = "<table>".$cuerpo."</table>";
        $cuerpo = "<img src=\"http://www.clubpadelmatola.com/css/logo.png\" /><h2>Se ha recibido un nuevo mensaje desde un usuario:<br /></h2>"."$cuerpo";
        
        include 'constantes.php';
        
        $to = "$EMAILCONTACTO";
        $cabeceras = "From: Club Padel Matola <noreply@clubpadelmatola.com>\r\nContent-type: text/html; charset=UTF-8\r\n";
        return mail($to, "Club Padel Matola", $cuerpo, $cabeceras);
    }
    
    function emailReserva($destino, $usuario, $reserva)
    {
        include 'constantes.php';
        
        $cuerpo = "<tr>";
        $cuerpo = $cuerpo."<td><strong>Nº de reserva:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".rellenar($reserva->getId(), '0', $RELLENO)."</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>Pista:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".pistaString($reserva->getIdPista())."</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>Día:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".$reserva->getFechaInicio()->format('d/m/Y')."</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>Horario:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".$reserva->getFechaInicio()->format('H:i')." - ".$reserva->getFechaFin()->format('H:i')."</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>Duración:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".$reserva->getDuracion()." minutos</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>Usuario:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".$usuario->getEmail()."</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>Precio:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".ceil($reserva->getDuracion() * $PRECIOHORA / 60)."€ a pagar en ventanilla</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = "<table>".$cuerpo."</table>";
        $cuerpo = "<img src=\"http://www.clubpadelmatola.com/css/logo.png\" /><h2>Resumen de la reserva realizada:<br /></h2>"."$cuerpo";

        $cabeceras = "From: Club Padel Matola <noreply@clubpadelmatola.com>\r\nContent-type: text/html; charset=UTF-8\r\n";
        
        return mail($destino, "Club Padel Matola - Reserva realizada", $cuerpo, $cabeceras);
    }
    
    function emailCancelarReserva($destino, $usuario, $reserva)
    {
        include 'constantes.php';
        
        $cuerpo = "<tr>";
        $cuerpo = $cuerpo."<td><strong>Nº de reserva:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".rellenar($reserva->getId(), '0', $RELLENO)."</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>Pista:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".pistaString($reserva->getIdPista())."</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>Día:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".$reserva->getFechaInicio()->format('d/m/Y')."</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>Horario:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".$reserva->getFechaInicio()->format('H:i')." - ".$reserva->getFechaFin()->format('H:i')."</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>Duración:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".$reserva->getDuracion()." minutos</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>Usuario:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".$usuario->getEmail()."</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = "<table>".$cuerpo."</table>";
        $cuerpo = "<img src=\"http://www.clubpadelmatola.com/css/logo.png\" /><h2 style=\"color: #f00;\">Una reserva ha sido CANCELADA:<br /></h2>"."$cuerpo";

        $cabeceras = "From: Club Padel Matola <noreply@clubpadelmatola.com>\r\nContent-type: text/html; charset=UTF-8\r\n";
        
        return mail($destino, "Club Padel Matola - Reserva cancelada", $cuerpo, $cabeceras);
    }
    
    function getDirectoryList ($directory) 
    {

        // create an array to hold directory list
        $results = array();

        // create a handler for the directory
        $handler = opendir($directory);

        // open directory and walk through the filenames
        while ($file = readdir($handler)) {

            // if file isn't this directory or its parent, add it to the results
            if ($file != "." && $file != ".." && !startsWith($file, ".")) {
            $results[] = $file;
            }

        }

        // tidy up: close the handler
        closedir($handler);

        // done!
        return $results;

    }
    
    function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
    
    function mesToStr($mes)
    {
        switch ($mes)
        {
            case 1: return "Enero";
            case 2: return "Febrero";
            case 3: return "Marzo";
            case 4: return "Abril";
            case 5: return "Mayo";
            case 6: return "Junio";
            case 7: return "Julio";
            case 8: return "Agosto";
            case 9: return "Septiembre";
            case 10: return "Octubre";
            case 11: return "Noviembre";
            case 12: return "Diciembre";
        }
    }
    
    function tipoString($tipo) {
        switch ($tipo) {
            case 0: return "Normal"; break;
            case 1: return "No reservable"; break;
            case 2: return "Clase 1 persona"; break;
            case 3: return "Clase 2 personas"; break;
            case 4: return "Clase 3 personas"; break;
            case 5: return "Liga Matola"; break;
            case 6: return "Liga Información"; break;
            default: return "ERROR: tipo \"$tipo\" desconocido"; break;
        }
    }
    
    function tipoCss($tipo) {
        switch ($tipo) {
            case 0: return "ocupado"; break;
            case 1: return "noreservable"; break;
            case 2: return "clase"; break;
            case 3: return "clase"; break;
            case 4: return "clase"; break;
            case 5: return "ligamatola"; break;
            case 6: return "ligainformacion"; break;
            default: return "ERROR: tipo \"$tipo\" desconocido"; break;
        }
    }
    
    function disponibilidadString($disponibilidad) {
        $cadena = "";
        
        if ($disponibilidad & 1) $cadena = $cadena."semana-mañana, ";
        if ($disponibilidad & 2) $cadena = $cadena."semana-tarde, ";
        if ($disponibilidad & 4) $cadena = $cadena."semana-noche, ";
        
        if ($disponibilidad & 8) $cadena = $cadena."sabado-mañana, ";
        if ($disponibilidad & 16) $cadena = $cadena."sabado-tarde, ";
        if ($disponibilidad & 32) $cadena = $cadena."sabado-noche, ";
        
        if ($disponibilidad & 64) $cadena = $cadena."domingo-mañana, ";
        if ($disponibilidad & 128) $cadena = $cadena."domingo-tarde, ";
        if ($disponibilidad & 256) $cadena = $cadena."domingo-noche, ";
        
        
        return $cadena;
    }
    
    function pistaString($pista) {
        include 'pistas.php';
        if ($pista >= 1 && $pista <= count($PISTAS)) {
            return $PISTAS[$pista - 1];
        }
        return $pista;
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
