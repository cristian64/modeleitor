<?php

require_once 'minilibreria.php';

class ENFabricante
{
    private $id;
    private $nombre;
    private $telefono;
    private $descripcion;
    private $email;

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
        $this->nombre = $nombre;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function __construct()
    {
        $this->id = 0;
        $this->nombre = "";
        $this->telefono = "";
        $this->descripcion = "";
        $this->email = "";
    }

    private static function getRow($fila)
    {
        $obj = new ENFabricante;
        $obj->id = $fila[0];
        $obj->nombre = utf8_encode($fila[1]);
        $obj->telefono = utf8_encode($fila[2]);
        $obj->descripcion = utf8_encode($fila[3]);
        $obj->email = utf8_encode($fila[4]);
        return $obj;
    }

    public static function get($filtro = "")
    {
        $filtro = secure(utf8_decode($filtro));
        $lista = NULL;

        try
        {
            $sentencia = "select * from fabricantes order by nombre asc";
            
            if ($filtro != "")
                $sentencia = "select * from fabricantes where nombre like '%$filtro%' or telefono like '%$filtro%' or descripcion like '%$filtro%' or email like '%$filtro%' order by nombre asc";
            
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
                        debug("ENFabricante::get() Fabricante nulo nº $contador");
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                debug("ENFabricante::get()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            debug("ENFabricante::get()".$e->getMessage());
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
            $sentencia = "$sentencia from fabricantes";
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
                        debug("ENFabricante::getById() Fabricante nulo $id");
                    }
                }

                BD::desconectar();
            }
            else
            {
                debug("ENFabricante::getById() ".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $obj = NULL;
            debug("ENFabricante::getById() ".$e->getMessage());
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
                $sentencia = "insert into fabricantes (nombre, telefono, descripcion, email)";
                $sentencia = "$sentencia values ('".secure(utf8_decode($this->nombre))."', '".secure(utf8_decode($this->telefono))."', '".secure(utf8_decode($this->descripcion))."', '".secure(utf8_decode($this->email))."')";
                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    // Obtenemos el identificador asignado al usuario recién creado.
                    $sentencia = "select id from fabricantes where email = '".secure(utf8_decode($this->email))."'";
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
                        debug("ENFabricante::save() ".mysql_error());
                    }
                }
                else
                {
                    debug("ENFabricante::save() ".mysql_error());
                }
                
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                debug("ENFabricante::save() ".$e->getMessage());
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
                $sentencia = "update fabricantes set nombre = '".secure(utf8_decode($this->nombre))."', telefono = '".secure(utf8_decode($this->telefono))."', descripcion = '".secure(utf8_decode($this->descripcion))."', email = '".secure(utf8_decode($this->email))."'";
                $sentencia = "$sentencia where id = '".secure(utf8_decode($this->id))."'";

                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    $guardado = true;
                }
                else
                {
                    debug("ENFabricante::update() ".mysql_error());
                }
                        
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                debug("ENFabricante::update() ".$e->getMessage());
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
                
                $sentencia = "delete from fabricantes where id = '".secure(utf8_decode($this->id))."'";
                $resultado = mysql_query($sentencia, $conexion);
                if ($resultado)
                {                    
                    $sentencia = "update modelos set id_fabricante = '0' where id_fabricante = '".secure(utf8_decode($this->id))."'";
                    $resultado = mysql_query($sentencia, $conexion);
                
                    if ($resultado)
                    {
                        $done = true;
                    }
                    else
                    {
                        debug("ENMarca::delete() ".mysql_error());
                    }
                }
                else
                {
                    debug("ENMarca::delete() ".mysql_error());
                }
                
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                debug("ENCategoria::delete() ".$e->getMessage());
            }
        }

        return $done;
    }
}
?>
