<?php

require_once 'minilibreria.php';

class ENUsuario
{
    private $id;
    private $email;
    private $contrasena;
    private $nombre;
    private $apellidos;
    private $telefono;
    private $direccion;
    private $cp;
    private $ciudad;
    private $empresa;
    private $cif;
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
    
    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
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
    
    public function getCp()
    {
        return $this->cp;
    }

    public function setCp($cp)
    {
        $this->cp = $cp;
    }
    
    public function getCiudad()
    {
        return $this->ciudad;
    }

    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    }
    
    public function getEmpresa()
    {
        return $this->empresa;
    }

    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }
    
    public function getCif()
    {
        return $this->cif;
    }

    public function setCif($cif)
    {
        $this->cif = $cif;
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
        $this->apellidos = "";
        $this->telefono = "";
        $this->direccion = "";
        $this->cp = "";
        $this->ciudad = "";
        $this->empresa = "";
        $this->cif = "";
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
        $obj->apellidos = utf8_encode($fila[4]);
        $obj->telefono = utf8_encode($fila[5]);
        $obj->direccion = utf8_encode($fila[6]);
        $obj->cp = utf8_encode($fila[7]);
        $obj->ciudad = utf8_encode($fila[8]);
        $obj->empresa = utf8_encode($fila[9]);
        $obj->cif = utf8_encode($fila[10]);
        $obj->admin = ($fila[11] == "0" || $fila[11] == 0) ? false : true;
        $obj->activo = ($fila[12] == "0" || $fila[12] == 0) ? false : true;
        $obj->fecha_registro = new DateTime($fila[13]);
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
                        depurar("ENUsuario::get() Usuario nulo nº $contador");
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                depurar("ENUsuario::get()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            depurar("ENUsuario::get()".$e->getMessage());
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
                        depurar("ENUsuario::getById() Usuario nulo $id");
                    }
                }

                BD::desconectar();
            }
            else
            {
                depurar("ENUsuario::getById() ".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $obj = NULL;
            depurar("ENUsuario::getById() ".$e->getMessage());
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
                        depurar("ENUsuario::getByEmail() Usuario nulo $id");
                    }
                }

                BD::desconectar();
            }
            else
            {
                depurar("ENUsuario::getByEmail() ".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $obj = NULL;
            depurar("ENUsuario::getByEmail() ".$e->getMessage());
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
                $sentencia = "insert into usuarios (email, contrasena, nombre, apellidos, telefono, direccion, cp, ciudad, empresa, cif, admin, activo, fecha_registro)";
                $sentencia = "$sentencia values ('".secure(utf8_decode($this->email))."', '".secure(utf8_decode($this->contrasena))."', '".secure(utf8_decode($this->nombre))."', '".secure(utf8_decode($this->apellidos))."', '".secure(utf8_decode($this->telefono))."', '".secure(utf8_decode($this->direccion))."', '".secure(utf8_decode($this->cp))."', '".secure(utf8_decode($this->ciudad))."', '".secure(utf8_decode($this->empresa))."', '".secure(utf8_decode($this->cif))."', '".($this->admin ? 1 : 0)."', '".($this->activo ? 1 : 0)."', now())";
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
                        depurar("ENUsuario::save() ".mysql_error());
                    }
                }
                else
                {
                    depurar("ENUsuario::save() ".mysql_error());
                }
                
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                depurar("ENUsuario::save() ".$e->getMessage());
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
                $sentencia = "update usuarios set email = '".secure(utf8_decode($this->email))."', contrasena = '".secure(utf8_decode($this->contrasena))."', nombre = '".secure(utf8_decode($this->nombre))."', apellidos = '".secure(utf8_decode($this->apellidos))."', telefono = '".secure(utf8_decode($this->telefono))."', direccion = '".secure(utf8_decode($this->direccion))."', cp = '".secure(utf8_decode($this->cp))."', ciudad = '".secure(utf8_decode($this->ciudad))."', empresa = '".secure(utf8_decode($this->empresa))."', cif = '".secure(utf8_decode($this->cif))."', admin = '".($this->admin ? 1 : 0)."', activo = '".($this->activo ? 1 : 0)."'";
                $sentencia = "$sentencia where id = '".secure(utf8_decode($this->id))."'";

                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    $guardado = true;
                }
                else
                {
                    depurar("ENUsuario::update() ".mysql_error());
                }
                        
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                depurar("ENUsuario::update() ".$e->getMessage());
            }
        }

        return $guardado;
    }
    
    public function delete()
    {
        $done = false;

        if ($this->id > 0)
        {
            try
            {
                $conexion = BD::conectar();
                
                $sentencia = "delete from usuarios where id = '".secure(utf8_decode($this->id))."'";
                $resultado = mysql_query($sentencia, $conexion);
                if ($resultado)
                {           
                    $done = true;
                }
                else
                {
                    depurar("ENMarca::delete() ".mysql_error());
                }
                
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                depurar("ENCategoria::delete() ".$e->getMessage());
            }
        }

        return $done;
    }
}
?>
