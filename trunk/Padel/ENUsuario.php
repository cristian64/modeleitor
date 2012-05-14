<?php

require_once 'minilibreria.php';

/**
 * Description of ENUsuario
 *
 * @author cristian
 */
class ENUsuario
{
    /**
     * Identificador único del usuario. Es clave candidata en la base de datos.
     * Si tiene valor 0, significa que el usuario no existe en la base de datos.
     * @var int
     */
    private $id;

    /**
     * Nombre del usuario. No puede repetirse en distintos usuarios.
     * @var string
     */
    private $nombre;

    /**
     * Contraseña del usuario.
     * @var string
     */
     private $contrasena;

     /**
      * Dirección de correo electrónico.
      * @var string
      */
     private $email;

     /**
      * Sexo del usuario ("hombre" o "mujer").
      * @var string
      */
     private $sexo;

     private $telefono;
     private $dni;
     private $direccion;
     private $admin;

     public function getId()
     {
         return $this->id;
     }

     public function getNombre()
     {
         return $this->nombre;
     }

     public function setNombre($nombre)
     {
         $nombre = filtrarCadena($nombre);
         $this->nombre = $nombre;
     }

     public function getContrasena()
     {
         return $this->contrasena;
     }

     public function setContrasena($contrasena)
     {
         $contrasena = filtrarCadena($contrasena);
         $this->contrasena = $contrasena;
     }

     public function getEmail()
     {
         return $this->email;
     }

     public function setEmail($email)
     {
         $email = filtrarCadena($email);
         $this->email = $email;
     }

     public function getSexo()
     {
         return $this->sexo;
     }

     public function setSexo($sexo)
     {
         if ($sexo != "hombre")
            $sexo = "mujer";
         $this->sexo = $sexo;
     }

     public function getTelefono()
     {
         return $this->telefono;
     }

     public function setTelefono($telefono)
     {
         $telefono = filtrarCadena($telefono);
         $this->telefono = $telefono;
     }
         
     public function getDni()
     {
         return $this->dni;
     }

     public function setDni($dni)
     {
         $dni = filtrarCadena($dni);
         $this->dni = $dni;
     }
         
     public function getDireccion()
     {
         return $this->direccion;
     }

     public function setDireccion($direccion)
     {
         $direccion = filtrarCadena($direccion);
         $this->direccion = $direccion;
     }
         
     public function getAdmin()
     {
         return $this->admin;
     }

     public function setAdmin($admin)
     {
         $this->admin = ($admin == 0 || $admin == "0") ? 0 : 1;
     }

    /**
     * Constructor de la clase.
     */
    public function  __construct()
    {
        $this->id = 0;
        $this->nombre = "";
        $this->contrasena = "";
        $this->email = "";
        $this->sexo = null;
        $this->telefono = "";
        $this->dni = "";
        $this->direccion = "";
        $this->admin = 0;
    }

    /**
     * Obtiene un conjunto de caracteres con los atributos del fabricante. Sobre todo para depuración.
     * @return string
     */
    public function toString()
    {
        return "----- USUARIO :: $this->nombre($this->id) :: $this->contrasena :: $this->email :: $this->sexo :: $this->dni :: $this->telefono :: $this->direccion :: $this->admin -----<br />";
    }

    /**
     * Procesa una fila y devuelve un objeto elaborado con el usuario.
     * @param array $fila Tantas componentes como columnas tiene la tabla "usuarios" de la base de datos.
     * @return ENUsuario Devuelve el usuario con todos sus atributos.
     */
    private static function obtenerDatos($fila)
    {
        $usuario = new ENUsuario;
        $usuario->id = $fila[0];
        $usuario->email = $fila[1];
        $usuario->nombre = utf8_encode($fila[2]);
        $usuario->contrasena = $fila[3];
        $usuario->dni = utf8_encode($fila[4]);
        $usuario->sexo = $fila[5];
        $usuario->direccion = utf8_encode($fila[6]);
        $usuario->telefono = utf8_encode($fila[7]);
        $usuario->admin = $fila[8];
        return $usuario;
    }

    /**
     * Obtiene todos los usuarios que hay en la base de datos.
     * @return array Devuelve una lista con todos los usuarios de la base de datos. Si hay algun error, devuelve NULL.
     */
    public static function obtenerTodos()
    {
        $listaUsuarios = NULL;

        try
        {
            $sentencia = "select * from usuarios order by id";           
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $listaUsuarios = array();
                $contador = 0;
                while ($fila = mysql_fetch_array($resultado))
                {
                    $usuario = self::obtenerDatos($fila);
                    if ($usuario != NULL)
                    {
                        $listaUsuarios[$contador++] = $usuario;
                    }
                    else
                    {
                        echo "ENUsuario::obtenerTodos() Usuario nulo nº $contador";
                    }
                }

                BD::desconectar();
            }
            else
            {
                echo "ENUsuario::obtenerTodos()".mysql_error();
            }
        }
        catch (Exception $e)
        {
            $listaUsuarios = NULL;
            echo "ENUsuario::obtenerTodos()".$e->getMessage();
        }

        return $listaUsuarios;
    }

    /**
     * Obtiene un usuario desde la base de datos a partir de su nombre.
     * @param string $nombre Nombre del usuario que se va a obtener.
     * @return ENUsuario Devuelve el usuario con todos sus atributos extraidos desde la base de datos. Devuelve NULL si ocurrió algún error.
     */
    public static function obtenerPorEmail($email)
    {
        $email = filtrarCadena($email);
        $usuario = NULL;

        try
        {
            $sentencia = "select *";
            $sentencia = "$sentencia from usuarios";
            $sentencia = "$sentencia where email = '$email'";
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $fila = mysql_fetch_array($resultado);
                if ($fila)
                {
                    $usuario = self::obtenerDatos($fila);
                    if ($usuario == NULL)
                    {
                        echo "ENUsuario::obtenerPorEmail() Usuario nulo $email";
                    }
                }

                BD::desconectar();
            }
            else
            {
                echo "ENUsuario::obtenerPorEmail()".mysql_error();
            }
        }
        catch (Exception $e)
        {
            $usuario = NULL;
            echo "ENUsuario::obtenerPorEmail() ".$e->getMessage();
        }

        return $usuario;
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
            $sentencia = "select *";
            $sentencia = "$sentencia from usuarios";
            $sentencia = "$sentencia where id = '$id'";
            $resultado = mysql_query($sentencia, BD::conectar());

            if ($resultado)
            {
                $fila = mysql_fetch_array($resultado);
                if ($fila)
                {
                    $usuario = self::obtenerDatos($fila);
                    if ($usuario == NULL)
                    {
                        echo "ENUsuario::obtenerPorId() Usuario nulo $id";
                    }
                }

                BD::desconectar();
            }
            else
            {
                echo "ENUsuario::obtenerPorId() ".mysql_error();
            }
        }
        catch (Exception $e)
        {
            $usuario = NULL;
            echo "ENUsuario::obtenerPorId() ".$e->getMessage();
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

                // Insertamos el usuario.
                $sentencia = "insert into usuarios (nombre, contrasena, email, dni, sexo, direccion, telefono, admin)";
                $sentencia = "$sentencia values ('".utf8_decode($this->nombre)."', '".$this->contrasena."', '".$this->email."', '".utf8_decode($this->dni)."', '".$this->sexo."', '".utf8_decode($this->direccion)."', '".utf8_decode($this->telefono)."', '".$this->admin."')";
                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    // Obtenemos el identificador asignado al usuario recién creado.
                    $sentencia = "select id from usuarios where email = '".$this->email."'";
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
                        echo "ENUsuario::guardar() ".mysql_error();
                    }
                }
                else
                {
                    echo "ENUsuario::guardar() ".mysql_error();
                }
                
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                echo "ENUsuario::guardar() ".$e->getMessage();
            }
        }

        return $guardado;
    }

    /**
     * Actualiza en la base de datos el usuario que invocó el método.
     * Ya debe existir el usuario en la base de datos.
     * @return bool Devuelve verdadero si se ha actualizado correctamente. Falso en caso contrario.
     */
    public function actualizar()
    {
        $guardado = false;

        if ($this->id > 0)
        {
            try
            {
                $conexion = BD::conectar();

                // Actualizamos el usuario.
                $sentencia = "update usuarios set nombre = '".$this->nombre."', contrasena = '".$this->contrasena."', sexo = '".$this->sexo."', dni = '".utf8_decode($this->dni)."', telefono = '".utf8_decode($this->telefono)."', direccion = '".utf8_decode($this->direccion)."', admin = '".$this->admin."'";
                $sentencia = "$sentencia where id = $this->id";

                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    $guardado = true;
                }
                else
                {
                    echo "ENUsuario::actualizar() ".mysql_error();
                }
                        
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                echo "<ENUsuario::actualizar() ".$e->getMessage();
            }
        }

        return $guardado;
    }
}
?>