<?php

require_once 'minilibreria.php';

class ENUsuario
{
    private $id;
    private $email;
    private $contrasena;
    private $nombre;
    private $telefono;
    private $direccion;
    private $admin;
    private $activo;
    private $fecha_registro;

    public function getId()
    {
        return $this->id;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    public function getContrasena()
    {
        return $this->contrasena;
    }
    
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    
    public function getTelefono()
    {
        return $this->telefono;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }
    
    public function getDireccion()
    {
        return $this->direccion;
    }

    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }
    
    public function getAdmin()
    {
        return $this->admin;
    }

    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }
    
    public function getActivo()
    {
        return $this->activo;
    }

    public function setActivo($activo)
    {
        $this->activo = $activo;
    }
    
    public function getFechaRegistro()
    {
        return $this->fecha_registro;
    }

    public function __construct()
    {
        $this->id = 0;
        $this->email = "";
        $this->contrasena = "";
        $this->nombre = "";
        $this->telefono = "";
        $this->direccion = "";
        $this->admin = false;
        $this->activo = false;
        $this->fecha_registro = new DateTime();
    }

    private static function getRow($fila)
    {
        $obj = new ENUsuario();
        $obj->id = $fila[0];
        $obj->email = utf8_encode($fila[1]);
        $obj->contrasena = utf8_encode($fila[2]);
        $obj->nombre = utf8_encode($fila[3]);
        $obj->telefono = utf8_encode($fila[4]);
        $obj->direccion = utf8_encode($fila[5]);
        $obj->admin = ($fila[6] == "0" || $fila[6] == 0) ? false : true;
        $obj->activo = ($fila[7] == "0" || $fila[7] == 0) ? false : true;
        $obj->fecha_registro = new DateTime($fila[8]);
        return $obj;
    }

    public static function get()
    {
        $lista = NULL;

        try
        {
            $sentencia = "select * from usuarios order by id asc";
            
            $conexion = BD::conectar();
            $resultado = mysql_query($sentencia, $conexion);
            if ($resultado)
            {
                $lista = array();
                $contador = 0;
                while ($fila = mysql_fetch_array($resultado))
                {
                    $obj = self::getRow($fila);
                    if ($obj != NULL)
                    {
                        $lista[$contador++] = $obj;
                    }
                    else
                    {
                        debug("ENUsuario::get() Usuario nulo nº $contador");
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                debug("ENUsuario::get()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            debug("ENUsuario::get()".$e->getMessage());
        }

        return $lista;
    }

    public static function getById($id)
    {
        $id = secure(utf8_decode($id));
        $obj = NULL;

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
                    $obj = self::getRow($fila);
                    if ($obj == NULL)
                    {
                        debug("ENUsuario::getById() Usuario nulo $id");
                    }
                }

                BD::desconectar();
            }
            else
            {
                debug("ENUsuario::getById() ".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $obj = NULL;
            debug("ENUsuario::getById() ".$e->getMessage());
        }

        return $obj;
    }
    
    public static function getByEmail($email)
    {
        $email = secure(utf8_decode($email));
        $obj = NULL;

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
                    $obj = self::getRow($fila);
                    if ($obj == NULL)
                    {
                        debug("ENUsuario::getByEmail() Usuario nulo $id");
                    }
                }

                BD::desconectar();
            }
            else
            {
                debug("ENUsuario::getByEmail() ".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $obj = NULL;
            debug("ENUsuario::getByEmail() ".$e->getMessage());
        }

        return $obj;
    }

    public function save()
    {
        $guardado = false;

        if ($this->id == 0)
        {
            try
            {
                $conexion = BD::conectar();

                // Insertamos el usuario.
                $sentencia = "insert into usuarios (email, contrasena, nombre, telefono, direccion, admin, activo, fecha_registro)";
                $sentencia = "$sentencia values ('".secure(utf8_decode($this->email))."', '".secure(utf8_decode($this->contrasena))."', '".secure(utf8_decode($this->nombre))."', '".secure(utf8_decode($this->telefono))."', '".secure(utf8_decode($this->direccion))."', '".($this->admin ? 1 : 0)."', '".($this->activo ? 1 : 0)."', now())";
                $resultado = mysql_query($sentencia, $conexion);



                echo "<br/>".$sentencia."<br/>";
                
                if ($resultado)
                {
                    // Obtenemos el identificador asignado al usuario recién creado.
                    $sentencia = "select max(id) from usuarios where email = '".secure(utf8_decode($this->email))."'";
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
                        debug("ENUsuario::save() ".mysql_error());
                    }
                }
                else
                {
                    debug("ENUsuario::save() ".mysql_error());
                }
                
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                debug("ENUsuario::save() ".$e->getMessage());
            }
        }

        return $guardado;
    }

    public function update()
    {
        $guardado = false;

        if ($this->id > 0)
        {
            try
            {
                $conexion = BD::conectar();

                // Actualizamos el usuario.
                $sentencia = "update usuarios set email = '".secure(utf8_decode($this->email))."', contrasena = '".secure(utf8_decode($this->contrasena))."', nombre = '".secure(utf8_decode($this->nombre))."', telefono = '".secure(utf8_decode($this->telefono))."', direccion = '".secure(utf8_decode($this->direccion))."', admin = '".($this->admin ? 1 : 0)."', activo = '".($this->activo ? 1 : 0)."'";
                $sentencia = "$sentencia where id = '".secure(utf8_decode($this->id))."'";

                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    $guardado = true;
                }
                else
                {
                    debug("ENUsuario::update() ".mysql_error());
                }
                        
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                debug("ENUsuario::update() ".$e->getMessage());
            }
        }

        return $guardado;
    }
}
?>
