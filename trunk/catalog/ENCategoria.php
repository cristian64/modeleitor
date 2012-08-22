<?php

require_once 'minilibreria.php';

class ENCategoria
{
    private $id;
    private $id_padre;
    private $nombre;
    private $mostrar;
    private $zindex;
    private $descripcion;

    public function getId()
    {
        return $this->id;
    }
    
    public function getIdPadre()
    {
        return $this->id;
    }
    
    public function setIdPadre($id_padre)
    {
        $this->id_padre = $id_padre;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    
    public function getMostrar()
    {
        return $this->mostrar;
    }

    public function setMostrar($mostrar)
    {
        $this->mostrar = $mostrar;
    }
    
    public function getZindex()
    {
        return $this->zindex;
    }

    public function setZindex($zindex)
    {
        $this->zindex = $zindex;
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
        $this->id_padre = 0;
        $this->nombre = "";
        $this->mostrar = true;
        $this->zindex = 0;
        $this->descripcion = "";
    }

    private static function getRow($fila)
    {
        $obj = new ENCategoria();
        $obj->id = $fila[0];
        $obj->id_padre = $fila[1];
        $obj->nombre = utf8_encode($fila[2]);
        $obj->mostrar = ($fila[3] == "0" || $fila[3] == 0) ? false : true;
        $obj->zindex = $fila[4];
        $obj->descripcion = utf8_encode($fila[5]);
        return $obj;
    }

    public static function get($todas = false)
    {
        $lista = NULL;

        try
        {
            $condicion = $todas ? "" : "where mostrar = true";
            $sentencia = "select * from categorias $condicion order by id_padre asc, zindex desc, nombre asc";
            
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
                        debug("ENCategoria::get() Categoria nula nº $contador");
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                debug("ENCategoria::get()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            debug("ENCategoria::get()".$e->getMessage());
        }

        return $lista;
    }

    public static function getByPadre($id_padre, $todas = false)
    {
        $id_padre = secure(utf8_decode($id_padre));
        $lista = NULL;

        try
        {
            $condicion = $todas ? "" : "mostrar = true and";
            $sentencia = "select * from categorias where $condicion id_padre = '$id_padre' order by zindex desc, nombre asc";
            
            $conexion = BD::conectar();
            $resultado = mysql_query($sentencia, $conexion);
            if ($resultado)
            {
                $lista = array();
                $contador = 0;
                while ($fila = mysql_fetch_array($resultado))
                {
                    $usuario = self::getRow($fila);
                    if ($usuario != NULL)
                    {
                        $lista[$contador++] = $usuario;
                    }
                    else
                    {
                        debug("ENCategoria::get() Categoria nula nº $contador");
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                debug("ENCategoria::getByPadre()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            debug("ENCategoria::getByPadre()".$e->getMessage());
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
            $sentencia = "$sentencia from categorias";
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
                        debug("ENCategoria::getById() Categoria nula $id");
                    }
                }

                BD::desconectar();
            }
            else
            {
                debug("ENCategoria::getById() ".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $obj = NULL;
            debug("ENCategoria::getById() ".$e->getMessage());
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
                $sentencia = "insert into categorias (id_padre, nombre, mostrar, zindex, descripcion)";
                $sentencia = "$sentencia values ('".secure(utf8_decode($this->id_padre))."', '".secure(utf8_decode($this->nombre))."', '".($this->mostrar ? 1 : 0)."', '".secure(utf8_decode($this->zindex))."', '".secure(utf8_decode($this->descripcion))."')";
                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    // Obtenemos el identificador asignado al usuario recién creado.
                    $sentencia = "select max(id) from categorias where id_padre = '".secure(utf8_decode($this->id_padre))."' and nombre = '".secure(utf8_decode($this->nombre))."'";
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
                        debug("ENCategoria::save() ".mysql_error());
                    }
                }
                else
                {
                    debug("ENCategoria::save() ".mysql_error());
                }
                
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                debug("ENCategoria::save() ".$e->getMessage());
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
                $sentencia = "update categorias set id_padre = '".secure(utf8_decode($this->id_padre))."', nombre = '".secure(utf8_decode($this->nombre))."', mostrar = '".($this->mostrar ? 1 : 0)."', id_padre = '".secure(utf8_decode($this->zindex))."', descripcion = '".secure(utf8_decode($this->descripcion))."'";
                $sentencia = "$sentencia where id = '".secure(utf8_decode($this->id))."'";

                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    $guardado = true;
                }
                else
                {
                    debug("ENCategoria::update() ".mysql_error());
                }
                        
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                debug("ENCategoria::update() ".$e->getMessage());
            }
        }

        return $guardado;
    }
}
?>
