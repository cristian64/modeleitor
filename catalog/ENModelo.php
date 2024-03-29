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
    private $precio_oferta;
    private $oferta;
    private $prioridad;
    private $descatalogado;
    private $foto;
    private $fecha_creacion;
    private $fecha_modificacion;
    private $fabricante;
    private $marca;

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
    
    public function getFabricante()
    {
        return $this->fabricante;
    }
    
    public function getIdMarca()
    {
        return $this->id_marca;
    }

    public function setIdMarca($id_marca)
    {
        $this->id_marca = $id_marca;
    }
    
    public function getMarca()
    {
        return $this->marca;
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
    
    public function getPrecioOferta()
    {
        return $this->precio_oferta;
    }

    public function setPrecioOferta($precio_oferta)
    {
        $this->precio_oferta = $precio_oferta;
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
    
    public function getFoto()
    {
        return $this->foto;
    }

    public function setFoto($foto)
    {
        $this->foto = $foto;
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
        $lista = NULL;

        if ($this->id > 0)
        {
            try
            {
                $sentencia = "select id_categoria from categorias_modelos where id_modelo = '".secure(utf8_decode($this->id))."'";
                
                $conexion = BD::conectar();
                $resultado = mysql_query($sentencia, $conexion);
                if ($resultado)
                {
                    $lista = array();
                    $contador = 0;
                    while ($fila = mysql_fetch_array($resultado))
                    {
                        $categoria = $fila[0];
                        if ($categoria != NULL)
                        {
                            $lista[$contador++] = $categoria;
                        }
                        else
                        {
                            depurar("ENModelo::getCategoriasFromDB() Categoria nulo nº $contador");
                        }
                    }

                    BD::desconectar($conexion);
                }
                else
                {
                    depurar("ENModelo::getCategoriasFromDB()".mysql_error());
                }
            }
            catch (Exception $e)
            {
                $lista = NULL;
                depurar("ENModelo::getCategoriasFromDB()".$e->getMessage());
            }
        }

        return $lista;
    }
    
    public function setCategoriasToDB($categorias)
    {
        $guardado = false;

        if ($this->id > 0)
        {
            try
            {
                $conexion = BD::conectar();

                $sentencia = "delete from categorias_modelos where id_modelo = '".secure(utf8_decode($this->id))."';";
                $resultado = mysql_query($sentencia, $conexion);
                if ($resultado)
                {
                    foreach ($categorias as $i)
                    {                
                        $sentencia = "insert into categorias_modelos (id_categoria, id_modelo)";
                        $sentencia = "$sentencia values ('".$i."', '".secure(utf8_decode($this->id))."');";
                        $resultado = mysql_query($sentencia, $conexion);
                        if (!$resultado)
                        {
                            depurar("ENModelo::setCategoriasToDB() ".mysql_error());
                        }
                    }
                }
                else
                {
                    depurar("ENModelo::setCategoriasToDB() ".mysql_error());
                }
                
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                depurar("ENModelo::setCategoriasToDB() ".$e->getMessage());
            }
        }

        return $guardado;
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
        $this->precio_oferta = 0;
        $this->oferta = 0;
        $this->prioridad = 5;
        $this->descatalogado = 0;
        $this->foto = "";
        $this->fecha_creacion = new DateTime();
        $this->fecha_modificacion = new DateTime();
        $this->fabricante = "";
        $this->marca = "";
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
        $obj->precio_oferta = $fila[9];
        $obj->oferta = ($fila[10] == "0" || $fila[10] == 0) ? false : true;
        $obj->prioridad = $fila[11];
        $obj->descatalogado = ($fila[12] == "0" || $fila[12] == 0) ? false : true;
        $obj->foto = utf8_encode($fila[13]);
        $obj->fecha_creacion = new DateTime($fila[14]);
        $obj->fecha_modificacion = new DateTime($fila[15]);
        return $obj;
    }
    
    private static function getRowMore($fila)
    {
        $obj = self::getRow($fila);
        $obj->fabricante = utf8_encode($fila[16]);
        $obj->marca = utf8_encode($fila[17]);
        return $obj;
    }
    
    public static function getByCategoria($id_categoria, $pagina, $cantidad)
    {
        $id_categoria = secure(utf8_decode($id_categoria));
        $lista = NULL;

        try
        {
            $sentencia = "select modelos.* from modelos, categorias_modelos, marcas where marcas.id = id_marca and descatalogado = 0 and modelos.id = id_modelo and id_categoria = '".$id_categoria."' order by marcas.nombre asc, prioridad desc, referencia asc";
                
            if (is_numeric($pagina) && is_numeric($cantidad))
                $sentencia = $sentencia." limit ".(($pagina - 1) * $cantidad).", ".$cantidad;
            
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
                        depurar("ENModelo::getByCategoria() Modelo nulo nº $contador");
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                depurar("ENModelo::getByCategoria()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            depurar("ENModelo::getByCategoria()".$e->getMessage());
        }

        return $lista;
    }
    
    public static function countByCategoria($id_categoria)
    {
        $id_categoria = secure(utf8_decode($id_categoria));
        $cantidad = NULL;

        try
        {
            $sentencia = "select count(*) from modelos, categorias_modelos where descatalogado = 0 and modelos.id = id_modelo and id_categoria = '".$id_categoria."'";
            
            $conexion = BD::conectar();
            $resultado = mysql_query($sentencia, $conexion);
            if ($resultado)
            {
                $cantidad = 0;
                $fila = mysql_fetch_array($resultado);
                if ($fila)
                {
                    $cantidad = $fila[0];
                }

                BD::desconectar($conexion);
            }
            else
            {
                depurar("ENModelo::countByCategoria()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $cantidad = NULL;
            depurar("ENModelo::countByCategoria()".$e->getMessage());
        }

        return $cantidad;
    }
    
    public static function getByFabricante($id_fabricante, $pagina, $cantidad)
    {
        $id_fabricante = secure(utf8_decode($id_fabricante));
        $lista = NULL;

        try
        {
            $sentencia = "select * from modelos where descatalogado = 0 and id_fabricante = '".$id_fabricante."' order by prioridad desc, referencia asc";
                
            if (is_numeric($pagina) && is_numeric($cantidad))
                $sentencia = $sentencia." limit ".(($pagina - 1) * $cantidad).", ".$cantidad;
            
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
                        depurar("ENModelo::getByFabricante() Modelo nulo nº $contador");
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                depurar("ENModelo::getByFabricante()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            depurar("ENModelo::getByFabricante()".$e->getMessage());
        }

        return $lista;
    }
    
    public static function countByFabricante($id_fabricante)
    {
        $id_fabricante = secure(utf8_decode($id_fabricante));
        $cantidad = NULL;

        try
        {
            $sentencia = "select count(*) from modelos where descatalogado = 0 and id_fabricante = '".$id_fabricante."'";
            
            $conexion = BD::conectar();
            $resultado = mysql_query($sentencia, $conexion);
            if ($resultado)
            {
                $cantidad = 0;
                $fila = mysql_fetch_array($resultado);
                if ($fila)
                {
                    $cantidad = $fila[0];
                }

                BD::desconectar($conexion);
            }
            else
            {
                depurar("ENModelo::countByFabricante()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $cantidad = NULL;
            depurar("ENModelo::countByFabricante()".$e->getMessage());
        }

        return $cantidad;
    }
    
    public static function getByMarca($id_marca, $pagina, $cantidad)
    {
        $id_marca = secure(utf8_decode($id_marca));
        $lista = NULL;

        try
        {
            $sentencia = "select * from modelos where descatalogado = 0 and id_marca = '".$id_marca."' order by prioridad desc, referencia desc";
                
            if (is_numeric($pagina) && is_numeric($cantidad))
                $sentencia = $sentencia." limit ".(($pagina - 1) * $cantidad).", ".$cantidad;
            
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
                        depurar("ENModelo::getByMarca() Modelo nulo nº $contador");
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                depurar("ENModelo::getByMarca()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            depurar("ENModelo::getByMarca()".$e->getMessage());
        }

        return $lista;
    }
    
    public static function countByMarca($id_marca)
    {
        $id_marca = secure(utf8_decode($id_marca));
        $cantidad = NULL;

        try
        {
            $sentencia = "select count(*) from modelos where descatalogado = 0 and id_marca = '".$id_marca."'";
            
            $conexion = BD::conectar();
            $resultado = mysql_query($sentencia, $conexion);
            if ($resultado)
            {
                $cantidad = 0;
                $fila = mysql_fetch_array($resultado);
                if ($fila)
                {
                    $cantidad = $fila[0];
                }

                BD::desconectar($conexion);
            }
            else
            {
                depurar("ENModelo::countByMarca()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $cantidad = NULL;
            depurar("ENModelo::countByMarca()".$e->getMessage());
        }

        return $cantidad;
    }

    public static function getPro($filtro, $id_categoria, $id_fabricante, $id_marca, $oferta, $descatalogado, $orden, $pagina, $cantidad)
    {
        $filtro = secure(utf8_decode($filtro));
        $lista = NULL;

        try
        {
            $condiciones = "";
            $filtros = explode(',', $filtro);
            foreach ($filtros as $f)
            {
                $subcondiciones = "";
                $words = explode(' ', trim($f));
                foreach ($words as $w)
                    if ($w != "")
                    {
                        if ($subcondiciones == "")
                            $subcondiciones = "(modelos.nombre like '%$w%' or modelos.referencia like '%$w%' or modelos.descripcion like '%$w%' or marcas.nombre like '%$w%')";
                        else
                            $subcondiciones = "$subcondiciones and (modelos.nombre like '%$w%' or modelos.referencia like '%$w%' or modelos.descripcion like '%$w%' or marcas.nombre like '%$w%')";
                    }
                
                if ($subcondiciones != "")
                {
                    if ($condiciones == "")
                        $condiciones = "(".$subcondiciones.")";
                    else
                        $condiciones = "$condiciones or (".$subcondiciones.")";
                }
            }
            if ($condiciones != "")
                $condiciones = "and (".$condiciones.")";

            if ($id_categoria > 0) $condiciones = $condiciones." and modelos.id in (select id_modelo from categorias_modelos where id_categoria = $id_categoria)";
            if ($id_fabricante > 0) $condiciones = $condiciones." and id_fabricante = $id_fabricante";
            if ($id_marca > 0) $condiciones = $condiciones." and id_marca = $id_marca";
            if ($oferta != null) $condiciones = $condiciones." and oferta = $oferta";
            if ($descatalogado != null) $condiciones = $condiciones." and descatalogado = $descatalogado";
            
            $sentencia = "select modelos.*, fabricantes.nombre, marcas.nombre from modelos, fabricantes, marcas where id_fabricante = fabricantes.id and id_marca = marcas.id $condiciones order by $orden";
            if (is_numeric($pagina) && is_numeric($cantidad))
                $sentencia = $sentencia." limit ".(($pagina - 1) * $cantidad).", ".$cantidad;            
            
            $conexion = BD::conectar();
            $resultado = mysql_query($sentencia, $conexion);
            if ($resultado)
            {
                $lista = array();
                $contador = 0;
                while ($fila = mysql_fetch_array($resultado))
                {
                    $obj = self::getRowMore($fila);
                    if ($obj != NULL)
                    {
                        $lista[$contador++] = $obj;
                    }
                    else
                    {
                        depurar("ENModelo::getPro() Modelo nulo nº $contador");
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                depurar("ENModelo::getPro()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            depurar("ENModelo::getPro()".$e->getMessage());
        }

        return $lista;
    }

    public static function get($filtro = "", $descatalogados)
    {        
        $filtro = secure(utf8_decode($filtro));
        $lista = NULL;
        
        try
        {
            $descatalogado = !$descatalogados ? "where descatalogado = 0" : "";
            $sentencia = "select * from modelos $descatalogado order by marcas.nombre desc, prioridad desc, modelos.nombre asc";
            
            if ($filtro != "")
            {
                $words = explode(' ', $filtro);
                $condiciones = "";
                foreach ($words as $w)
                {
                    if ($condiciones == "")
                        $condiciones = "$condiciones (modelos.nombre like '%$w%' or modelos.referencia like '%$w%' or modelos.descripcion like '%$w%' or marcas.nombre like '%$w%')";
                    else
                        $condiciones = "$condiciones and (modelos.nombre like '%$w%' or modelos.referencia like '%$w%' or modelos.descripcion like '%$w%' or marcas.nombre like '%$w%')";
                }
                $descatalogado = !$descatalogados ? "and descatalogado = 0" : "";
                $sentencia = "select modelos.* from modelos, marcas where id_marca = marcas.id $descatalogado and $condiciones order by marcas.nombre desc, prioridad desc, modelos.nombre asc";
            }
            
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
                        depurar("ENModelo::get() Modelo nulo nº $contador");
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                depurar("ENModelo::get()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            depurar("ENModelo::get()".$e->getMessage());
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
                        depurar("ENModelo::getById() Modelo nulo $id");
                    }
                }

                BD::desconectar();
            }
            else
            {
                depurar("ENModelo::getById() ".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $obj = NULL;
            depurar("ENModelo::getById() ".$e->getMessage());
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
                $sentencia = "insert into modelos (id_fabricante, id_marca, referencia, nombre, talla_menor, talla_mayor, descripcion, precio, precio_oferta, oferta, prioridad, descatalogado, foto, fecha_creacion, fecha_modificacion)";
                $sentencia = "$sentencia values ('".secure(utf8_decode($this->id_fabricante))."', '".secure(utf8_decode($this->id_marca))."', '".secure(utf8_decode($this->referencia))."', '".secure(utf8_decode($this->nombre))."', '".secure(utf8_decode($this->talla_menor))."', '".secure(utf8_decode($this->talla_mayor))."', '".secure(utf8_decode($this->descripcion))."', '".secure(utf8_decode($this->precio))."', '".secure(utf8_decode($this->precio_oferta))."', '".($this->oferta ? 1 : 0)."', '".secure(utf8_decode($this->prioridad))."', '".($this->descatalogado ? 1 : 0)."', '".secure(utf8_decode($this->foto))."', now(), now())";
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
                        depurar("ENModelo::save() ".mysql_error());
                    }
                }
                else
                {
                    depurar("ENModelo::save() ".mysql_error());
                }
                
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                depurar("ENModelo::save() ".$e->getMessage());
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
                $sentencia = "update modelos set id_fabricante = '".secure(utf8_decode($this->id_fabricante))."', id_marca = '".secure(utf8_decode($this->id_marca))."', referencia = '".secure(utf8_decode($this->referencia))."', nombre = '".secure(utf8_decode($this->nombre))."', descripcion = '".secure(utf8_decode($this->descripcion))."', precio = '".secure(utf8_decode($this->precio))."', precio_oferta = '".secure(utf8_decode($this->precio_oferta))."', oferta = '".($this->oferta ? 1 : 0)."', descatalogado = '".($this->descatalogado ? 1 : 0)."', prioridad = '".secure(utf8_decode($this->prioridad))."', talla_menor = '".secure(utf8_decode($this->talla_menor))."', talla_mayor = '".secure(utf8_decode($this->talla_mayor))."', foto = '".secure(utf8_decode($this->foto))."', fecha_modificacion = now()";
                $sentencia = "$sentencia where id = '".secure(utf8_decode($this->id))."'";

                $resultado = mysql_query($sentencia, $conexion);

                if ($resultado)
                {
                    $guardado = true;
                }
                else
                {
                    depurar("ENModelo::update() ".mysql_error());
                }
                        
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                depurar("ENModelo::update() ".$e->getMessage());
            }
        }

        return $guardado;
    }
    
    function saveFoto($httpPostFile)
    {
        //http://emilio.aesinformatica.com/2007/05/03/subir-una-imagen-con-php/
        $creada = false;

        if (is_uploaded_file($httpPostFile['tmp_name']))
        {
            // Hay que intentar borrar las anteriores. No importa si falla.
            borrar("img/modelos/".$this->getFoto());
            $thumbs = getThumbs($this->getFoto());
            foreach ($thumbs as $thumb)
                borrar("img/modelos/".$thumb);

            $nombre = $this->id;
            $extension = pathinfo($httpPostFile['name'], PATHINFO_EXTENSION);

            $this->setFoto("$nombre.$extension");
            $thumbs = getThumbs("$nombre.$extension");
            $rutaFoto = "img/modelos/".$this->getFoto();

            // Luego hay que copiar el fichero de la imagen a la ruta de la foto.
            if (@move_uploaded_file($httpPostFile['tmp_name'], $rutaFoto))
            {
                if (@chmod($rutaFoto,0777))
                {
                    $counter = 1;
                    foreach ($thumbs as $thumb)
                    {
                        $rutaThumb = "img/modelos/".$thumb;
                        $miniatura=new thumbnail($rutaFoto);
                        $miniatura->size_auto(100 * $counter++);
                        $miniatura->jpeg_quality(100);
                        $miniatura->save($rutaThumb);
                        @chmod($rutaThumb,0777);
                    }

                    $creada = true;
                }
            }
        }

        return $creada;
    }
    
    public function delete()
    {
        $done = false;

        if ($this->id > 0)
        {
            try
            {
                $conexion = BD::conectar();
                
                $sentencia = "delete from modelos where id = '".secure(utf8_decode($this->id))."'";
                $resultado = mysql_query($sentencia, $conexion);
                if ($resultado)
                {
                    $sentencia = "delete from categorias_modelos where id_modelo = '".secure(utf8_decode($this->id))."'";
                    $resultado = mysql_query($sentencia, $conexion);
                
                    if ($resultado)
                    {
                        $done = true;
                    }
                    else
                    {
                        depurar("ENCategoria::delete() ".mysql_error());
                    }
                }
                else
                {
                    depurar("ENCategoria::delete() ".mysql_error());
                }
                
                BD::desconectar($conexion);
                
                // Hay que intentar borrar las anteriores. No importa si falla.
                borrar("img/modelos/".$this->getFoto());
                $thumbs = getThumbs($this->getFoto());
                foreach ($thumbs as $thumb)
                    borrar("img/modelos/".$thumb);
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
