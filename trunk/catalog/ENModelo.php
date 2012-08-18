<?php

require_once 'minilibreria.php';

class ENModelo
{
    private $id;
    private $id_fabricante;
    private $id_marca;
    private $referencia;
    private $nombre;
    private $talla_menor;
    private $talla_mayor;
    private $descripcion;
    private $precio;
    private $oferta;
    private $prioridad;
    private $descatalogado;
    private $fecha_creacion;
    private $fecha_modificacion;

    public function getId()
    {
        return $this->id;
    }
    
    public function getIdFabricante()
    {
        return $this->id_fabricante;
    }

    public function setIdFabricante($id_fabricante)
    {
        $this->id_fabricante = $id_fabricante;
    }
    
    public function getIdMarca()
    {
        return $this->id_marca;
    }

    public function setIdMarca($id_marca)
    {
        $this->id_marca = $id_marca;
    }

    public function getReferencia()
    {
        return $this->referencia;
    }

    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    
    public function getTallaMenor()
    {
        return $this->talla_menor;
    }
    
    public function setTallaMenor($talla_menor)
    {
        $this->talla_menor = $talla_menor;
    }
    
    public function getTallaMayor()
    {
        return $this->talla_mayor;
    }
    
    public function setTallaMayor($talla_mayor)
    {
        $this->talla_mayor = $talla_mayor;
    }
    
    public function getNumeracion()
    {
        return "".$this->talla_menor."/".$this->talla_mayor;
    }
    
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    
    public function getPrecio()
    {
        return $this->precio;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }
    
    public function getOferta()
    {
        return $this->oferta;
    }

    public function setOferta($oferta)
    {
        $this->oferta = $oferta;
    }
    
    public function getPrioridad()
    {
        return $this->prioridad;
    }

    public function setPrioridad($prioridad)
    {
        $this->prioridad = $prioridad;
    }
    
    public function getDescatalogado()
    {
        return $this->descatalogado;
    }

    public function setDescatalogado($descatalogado)
    {
        $this->descatalogado = $descatalogado;
    }
    
    public function getFechaCreacion()
    {
        return $this->fecha_creacion;
    }
    
    public function getFechaModificacion()
    {
        return $this->fecha_modificacion;
    }
    
    public function getCategoriasFromDB()
    {
        return array();
    }
    
    public function setCategoriasToDB($categorias)
    {
        if ($this->id > 0)
        {
            return false;
        }
    }

    public function __construct()
    {
        $this->id = 0;
        $this->id_fabricante = 0;
        $this->id_marca = 0;
        $this->referencia = "";
        $this->nombre = "";
        $this->talla_menor = 0;
        $this->talla_mayor = 0;
        $this->descripcion = "";
        $this->precio = 0;
        $this->oferta = 0;
        $this->prioridad = 5;
        $this->descatalogado = 0;
        $this->fecha_creacion = new DateTime();
        $this->fecha_modificacion = new DateTime();
    }

    private static function getRow($fila)
    {
        $obj = new ENModelo();
        $obj->id = $fila[0];
        $obj->id_fabricante = $fila[1];
        $obj->id_marca = $fila[2];
        $obj->referencia = utf8_encode($fila[3]);
        $obj->nombre = utf8_encode($fila[4]);
        $obj->talla_menor = utf8_encode($fila[5]);
        $obj->talla_mayor = utf8_encode($fila[6]);
        $obj->descripcion = utf8_encode($fila[7]);
        $obj->precio = $fila[8];
        $obj->oferta = ($fila[9] == "0" || $fila[9] == 0) ? false : true;
        $obj->prioridad = ($fila[10] == "0" || $fila[10] == 0) ? false : true;
        $obj->descatalogado = ($fila[11] == "0" || $fila[11] == 0) ? false : true;
        $obj->fecha_creacion = new DateTime($fila[12]);
        $obj->fecha_modificacion = new DateTime($fila[13]);
        return $obj;
    }

    public static function get($filtro = "")
    {
        $filtro = secure(utf8_decode($filtro));
        $lista = NULL;

        try
        {
            $sentencia = "select * from modelos order by nombre asc";
            
            if ($filtro != "")
                $sentencia = "select * from modelos where referencia like '%$filtro%' or nombre like '%$filtro%' order by nombre asc, prioridad desc";
            
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
                        debug("ENModelo::get() Modelo nulo nº $contador");
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                debug("ENModelo::get()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            debug("ENModelo::get()".$e->getMessage());
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
            $sentencia = "$sentencia from modelos";
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
                        debug("ENModelo::getById() Modelo nulo $id");
                    }
                }

                BD::desconectar();
            }
            else
            {
                debug("ENModelo::getById() ".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $obj = NULL;
            debug("ENModelo::getById() ".$e->getMessage());
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
                $sentencia = "insert into modelos (id_fabricante, id_marca, referencia, nombre, talla_menor, talla_mayor, descripcion, precio, oferta, prioridad, descatalogado, fecha_creacion, fecha_modificacion)";
                $sentencia = "$sentencia values ('".secure(utf8_decode($this->id_fabricante))."', '".secure(utf8_decode($this->id_marca))."', '".secure(utf8_decode($this->referencia))."', '".secure(utf8_decode($this->nombre))."', '".secure(utf8_decode($this->talla_menor))."', '".secure(utf8_decode($this->talla_mayor))."', '".secure(utf8_decode($this->descripcion))."', '".secure(utf8_decode($this->precio))."', '".($this->oferta ? 1 : 0)."', '".secure(utf8_decode($this->prioridad))."', '".($this->descatalogado ? 1 : 0)."', now(), now())";
                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    // Obtenemos el identificador asignado al usuario recién creado.
                    $sentencia = "select max(id) from modelos";
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
                        debug("ENModelo::save() ".mysql_error());
                    }
                }
                else
                {
                    debug("ENModelo::save() ".mysql_error());
                }
                
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                debug("ENModelo::save() ".$e->getMessage());
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
                $sentencia = "update modelos set id_fabricante = '".secure(utf8_decode($this->id_fabricante))."', id_marca = '".secure(utf8_decode($this->id_marca))."', referencia = '".secure(utf8_decode($this->referencia))."', nombre = '".secure(utf8_decode($this->nombre))."', descripcion = '".secure(utf8_decode($this->descripcion))."', precio = '".secure(utf8_decode($this->precio))."', oferta = '".($this->oferta ? 1 : 0)."', descatalogado = '".($this->descatalogado ? 1 : 0)."', prioridad = '".secure(utf8_decode($this->prioridad))."', talla_menor = '".secure(utf8_decode($this->talla_menor))."', talla_mayor = '".secure(utf8_decode($this->talla_mayor))."', fecha_modificacion = now()";
                $sentencia = "$sentencia where id = '".secure(utf8_decode($this->id))."'";

                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    $guardado = true;
                }
                else
                {
                    debug("ENModelo::update() ".mysql_error());
                }
                        
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                debug("ENModelo::update() ".$e->getMessage());
            }
        }

        return $guardado;
    }
}
?>
