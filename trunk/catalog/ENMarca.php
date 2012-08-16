<?php

require_once 'minilibreria.php';

class ENMarca
{
    private $id;
    private $nombre;
    private $logo;

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
    
    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    public function  __construct()
    {
        $this->id = 0;
        $this->nombre = "";
        $this->logo = "";
    }

    private static function getRow($fila)
    {
        $obj = new ENFabricante;
        $obj->id = $fila[0];
        $obj->nombre = utf8_encode($fila[1]);
        $obj->logo = utf8_encode($fila[2]);
        return $obj;
    }

    public static function get($filtro = "")
    {
        $filtro = secure(utf8_decode($filtro));
        $lista = NULL;

        try
        {
            $sentencia = "select * from marcas order by nombre asc";
            
            if ($filtro != "")
                $sentencia = "select * from marcas where nombre like '%$filtro%' order by nombre asc";
            
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
                        debug("ENMarca::get() Marca nula nº $contador");
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                debug("ENMarca::get()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            debug("ENMarca::get()".$e->getMessage());
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
            $sentencia = "$sentencia from marcas";
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
                        debug("ENMarca::getById() Marca nula $id");
                    }
                }

                BD::desconectar();
            }
            else
            {
                debug("ENMarca::getById() ".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $obj = NULL;
            debug("ENMarca::getById() ".$e->getMessage());
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
                $sentencia = "insert into marcas (nombre, logo)";
                $sentencia = "$sentencia values ('".secure(utf8_decode($this->nombre))."', '".secure(utf8_decode($this->logo))."')";
                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    // Obtenemos el identificador asignado al usuario recién creado.
                    $sentencia = "select id from marcas where nombre = '".secure(utf8_decode($this->nombre))."'";
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
                        debug("ENMarca::save() ".mysql_error());
                    }
                }
                else
                {
                    debug("ENMarca::save() ".mysql_error());
                }
                
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                debug("ENMarca::save() ".$e->getMessage());
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
                $sentencia = "update marcas set nombre = '".secure(utf8_decode($this->nombre))."', logo = '".secure(utf8_decode($this->logo))."'";
                $sentencia = "$sentencia where id = '".secure(utf8_decode($this->id))."'";

                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    $guardado = true;
                }
                else
                {
                    debug("ENMarca::update() ".mysql_error());
                }
                        
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                debug("ENMarca::update() ".$e->getMessage());
            }
        }

        return $guardado;
    }
}
?>
