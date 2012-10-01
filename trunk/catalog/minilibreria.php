<?php
    session_start();
    
    require_once 'mobile_device_detect.php';
    require_once 'resize.php';
    require_once 'constantes.php';
    require_once 'BD.php';
    BD::espeficarDatos($BDURL, $BDUSER, $BDPASSWORD, $BDNAME);
    require_once 'ENFabricante.php';
    require_once 'ENMarca.php';
    require_once 'ENCategoria.php';
    require_once 'ENUsuario.php';
    require_once 'ENModelo.php';
    require_once 'ENAcceso.php';
    
    function getUsuario()
    {
        $usuario = null;
        if (!isset($_SESSION["usuario"]))
        {
            // Si no hay ninguna sesión abierta, intentamos abrir una desde las cookies.
            if (isset($_COOKIE["email"]) && isset($_COOKIE["contrasena"]))
            {
                $ip = $_SERVER['REMOTE_ADDR'];
                if (ENAcceso::isGranted($ip))
                {
                    $usuario2 = ENUsuario::getByEmail($_COOKIE["email"]);
                    if ($usuario2 != null)
                    {
                        if ($usuario2->getContrasena() == $_COOKIE["contrasena"])
                        {
                            if (!$usuario2->getActivo())
                            {
                                $_SESSION["mensaje_error"] = "No se pudo iniciar sesión. Tu cuenta está a la espera de ser activada por un administrador.";
                            }
                            else
                            {
                                $_SESSION["usuario"] = serialize($usuario2);
                                $usuario = $usuario2;
                            }
                            
                            ENAcceso::save($ip, $usuario2->getId(), true);
                        }
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
    
    function secure($string)
    {
        if ($string != NULL && (is_string($string) || is_numeric($string)))
        {
            $string = str_replace("\\", "\\\\", $string);
            return str_replace("'", "\'", $string);
        }
        return "";
    }
    
    function esMovil()
    {
        //TODO: guardar en la sesion el valor y traerlo, debe ser mas raipdo que calcular semejante tocho de fnucion
        return mobile_device_detect();
    }    
    
    function borrar($rutaFichero)
    {
        return $rutaFichero != NULL && file_exists($rutaFichero) && @unlink($rutaFichero);
    }
    
    function crearFicheroFoto($httpPostFile)
    {
        //http://emilio.aesinformatica.com/2007/05/03/subir-una-imagen-con-php/
        $creada = false;

        if (is_uploaded_file($httpPostFile['tmp_name']))
        {
            $ahora = new DateTime();
            $nombre = $ahora->format("dmYHis");
            $extension = pathinfo($httpPostFile['name'], PATHINFO_EXTENSION);
            $rutaFoto = "fotos/$nombre."."$extension";
            $rutaMiniatura = "fotos/$nombre.thumb."."$extension";

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
    
    function getThumbs($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $nombre = basename($filename, ".$extension");

        return array("$nombre.thumb1.$extension", "$nombre.thumb2.$extension", "$nombre.thumb3.$extension", "$nombre.thumb4.$extension", "$nombre.thumb5.$extension");
    }
    
    function debug($cadena)
    {
        $fp = fopen("$DEBUG_FILE", 'a');
        fwrite($fp, $cadena."\n");
        fclose($fp);
        echo $cadena."<br />";
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
        $cuerpo = "<img src=\"http://www.calzadosjam.es/css/logo.png\" /><h2>Se ha recibido un nuevo mensaje desde un usuario:<br /></h2>"."$cuerpo";
        
        include 'constantes.php';
        
        $to = "calzadosjam@gmail.com";
        $cabeceras = "From: Calzados JAM <noreply@calzadosjam.es>\r\nContent-type: text/html; charset=UTF-8\r\n";
        return mail($to, "Calzados JAM", $cuerpo, $cabeceras);
    }
    
    function emailActivado($usuario)
    {
        include 'constantes.php';
        
        $cuerpo = "";
        $cuerpo = $cuerpo."Tu cuenta ha sido <strong>ACTIVADA CORRECTAMENTE</strong>.<br />Inicia sesión con <strong>".$usuario->getEmail()."</strong> en <a href=\"http://www.calzadosjam.es/clientes\">http://www.calzadosjam.es/clientes</a><br /><br />";
        $cuerpo = "<div style=\"font-size: 12pt;\">".$cuerpo."</div>";
        $cuerpo = "<img src=\"http://www.calzadosjam.es/css/calzadosjam_logo.png\" /><br /><br /><br /><br />"."$cuerpo";

        $cabeceras = "From: Calzados JAM <noreply@calzadosjam.es>\r\nContent-type: text/html; charset=UTF-8\r\n";
        return mail($usuario->getEmail(), "Cuenta activada correctamente en Calzados JAM", $cuerpo, $cabeceras);
    }
    
    function emailRegistrado($usuario, $contrasena_sin_sha)
    {
        include 'constantes.php';
        
        $cuerpo = "";
        $cuerpo = $cuerpo."Tu cuenta ha sido <strong>REGISTRADA CORRECTAMENTE</strong>.<br /><br />E-mail: ".$usuario->getEmail()."<br />Contraseña: ".$contrasena_sin_sha."<br /><br /><strong>Debes esperar unos minutos hasta que validemos tu cuenta.</strong><br />Si no recibes respuesta rápida, puedes escribirnos a <a href=\"mailto:buzon@calzadosjam.es\">buzon@calzadosjam.es</a><br /><br />";
        $cuerpo = "<div style=\"font-size: 12pt;\">".$cuerpo."</div>";
        $cuerpo = "<img src=\"http://www.calzadosjam.es/css/calzadosjam_logo.png\" /><br /><br /><br /><br />"."$cuerpo";

        $cabeceras = "From: Calzados JAM <noreply@calzadosjam.es>\r\nContent-type: text/html; charset=UTF-8\r\n";
        mail($usuario->getEmail(), "Cuenta registrada correctamente en Calzados JAM", $cuerpo, $cabeceras);
        
        $cuerpo = "";
        $cuerpo = $cuerpo."La cuenta ".$usuario->getEmail()." ha sido registrada.";
        $cuerpo = "<div style=\"font-size: 12pt;\">".$cuerpo."</div>";        
        return mail("calzadosjam@gmail.com", "Nuevo usuario registrado en Calzados JAM", $cuerpo, $cabeceras);
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
            if (substr($file, 0, 1) != ".") {
                $results[] = $file;
            }

        }

        // tidy up: close the handler
        closedir($handler);

        // done!
        return $results;

    }

    function sha512($string)
    {
        return openssl_digest($string, "sha512");
    }
    
    function diaStr()
    {
        switch (date("w"))
        {
            case 1: return "lunes";
            case 2: return "martes";
            case 3: return "miércoles";
            case 4: return "jueves";
            case 5: return "viernes";
            case 6: return "sábado";
            case 7: return "domingo";
        }
    }
    
    function mesStr()
    {
        switch (date("m"))
        {
            case 1: return "enero";
            case 2: return "febrero";
            case 3: return "marzo";
            case 4: return "abril";
            case 5: return "mayo";
            case 6: return "junio";
            case 7: return "julio";
            case 8: return "agosto";
            case 9: return "septiembre";
            case 10: return "octubre";
            case 11: return "noviembre";
            case 12: return "diciembre";
        }
    }
    
    function fechaStr()
    {
        return diaStr()." ".date("d")." de ".mesStr()." de ".date("Y");
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
