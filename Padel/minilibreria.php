<?php
    session_start();

    require_once 'constantes.php';
    require_once 'BD.php';
    BD::espeficarDatos($BDURL, $BDUSER, $BDPASSWORD, $BDNAME);
    require_once 'ENUsuario.php';
    require_once 'ENReserva.php';
    require_once 'ENIntentos.php';
    
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
    
    function debug($cadena)
    {
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
        $cuerpo = "<h3>Se ha recibido un nuevo mensaje de un usuario:<br /></h3>"."$cuerpo";
        
        include 'constantes.php';
        
        $to = "$EMAILCONTACTO";
        $cabeceras = "From: Club Padel Matola <noreply@clubpadelmatola.com>\r\nContent-type: text/html; charset=UTF-8\r\n";
        return mail($to, "Club Padel Matola", $cuerpo, $cabeceras);
    }
    
    function emailReserva($usuario, $reserva)
    {
        include 'constantes.php';
        
        $cuerpo = "<tr>";
        $cuerpo = $cuerpo."<td><strong>Nº de reserva:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".rellenar($reserva->getId(), '0', $RELLENO)."</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = $cuerpo."<tr>";
        $cuerpo = $cuerpo."<td><strong>Pista:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".$reserva->getIdPista()."</td>";
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
        $cuerpo = $cuerpo."<td><strong>Precio:</strong>&nbsp;&nbsp;&nbsp;</td>";
        $cuerpo = $cuerpo."<td>".ceil($reserva->getDuracion() * $PRECIOHORA / 60)."€ a pagar en ventanilla</td>";
        $cuerpo = $cuerpo."</tr>";
        $cuerpo = "<table>".$cuerpo."</table>";
        $cuerpo = "<h3>Resumen de la reserva realizada:<br /></h3>"."$cuerpo";

        $cabeceras = "From: Club Padel Matola <noreply@clubpadelmatola.com>\r\nContent-type: text/html; charset=UTF-8\r\n";
        
        $to = $usuario->getEmail();
        
        return mail($to, "Club Padel Matola - Reserva realizada", $cuerpo, $cabeceras);
    }
    
    /*
    require_once 'Mail.php';
    require_once 'phpmailer.inc.php';
    
    function email2($nombre, $asunto, $email, $mensaje)
    {
        
        $newMail = new Mail();
        
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
        $cuerpo = "<div>Se ha recibido un nuevo mensaje de un usuario:<br /></div>"."$cuerpo";
        
        $subject = "Club Padel Matola";
        $mail_body = $cuerpo;
        $from = "From: Club Padel Matola <noreply@clubpadelmatola.com>";
        $to = "To: Cristian <cristian64@gmail.com>";
        $receiver = "cristian64@gmail.com";
        // Setting up the headers
        $headers["From"] = $from;
        $headers["To"] = $to;
        $headers["Subject"] = $subject;
        //$headers["Reply-To"] = "reply@address.com";
        $headers["Content-Type"] = "text/html";
        //$headers["Return-path"] = "returnpath@address.com";

        // Setting up the SMTP setting
        $smtp_info["host"] = "ssl://smtp.server.com";
        $smtp_info["port"] = "587";
        $smtp_info["auth"] = true;
        $smtp_info["username"] = "criludage@gmail.com";
        $smtp_info["password"] = "123456criludage";

        // Creating the PEAR mail object :
        $mail_obj =& $newMail->factory("smtp", $smtp_info);

        // Sending the mail now
        $mail_sent = $mail_obj->send($receiver, $headers, $mail_body);

        // If any error the see for that here:
        if (PEAR::isError($mail_sent)) { print($mail_sent->getMessage());}
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
        $cuerpo = "<div>Se ha recibido un nuevo mensaje de un usuario:<br /></div>"."$cuerpo";
        
        $to = "cristian64@gmail.com";
        //$cabeceras = "From: noreply@clubpadelmatola.com\r\nContent-type: text/html\r\n";
        //return mail($to, "Club Padel Matola", $cuerpo, $cabeceras);
        
        $mail = new PHPMailer(true);
        
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->SMTPSecure = "ssl"; // sets the prefix to the servier
        $mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
        $mail->Port = 587; // set the SMTP port for the GMAIL server
        $mail->Username = "criludage@gmail.com"; // GMAIL username
        $mail->Password = "123456criludage"; // GMAIL password

        $mail->AddAddress("cristian64@gmail.com");
        $mail->SetFrom("criludage@gmail.com", "Club Padel Matola");
        $mail->Subject = "Club Padel Matola";
        $mail->Body = $cuerpo;

        try
        {
            $mail->Send();
            echo "Success!";
            return true;
        } catch(Exception $e) {
            //Something went bad
            echo "Fail :( ";
            return false;
        }
    }*/

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
