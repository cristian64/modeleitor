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

    public function __construct()
    {
        $this->id = 0;
        $this->nombre = "";
        $this->logo = "";
    }

    private static function getRow($fila)
    {
        $obj = new ENMarca();
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
                        depurar("ENMarca::get() Marca nula nº $contador");
                    }
                }

                BD::desconectar($conexion);
            }
            else
            {
                depurar("ENMarca::get()".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $lista = NULL;
            depurar("ENMarca::get()".$e->getMessage());
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
                        depurar("ENMarca::getById() Marca nula $id");
                    }
                }

                BD::desconectar();
            }
            else
            {
                depurar("ENMarca::getById() ".mysql_error());
            }
        }
        catch (Exception $e)
        {
            $obj = NULL;
            depurar("ENMarca::getById() ".$e->getMessage());
        }

        return $obj;
    }
    
    public function delete()
    {
        $done = false;

        if ($this->id > 0)
        {
            try
            {
                $conexion = BD::conectar();
                
                $sentencia = "delete from marcas where id = '".secure(utf8_decode($this->id))."'";
                $resultado = mysql_query($sentencia, $conexion);
                if ($resultado)
                {                    
                    $sentencia = "update modelos set id_marca = '0' where id_marca = '".secure(utf8_decode($this->id))."'";
                    $resultado = mysql_query($sentencia, $conexion);
                
                    if ($resultado)
                    {
                        $done = true;
                    }
                    else
                    {
                        depurar("ENMarca::delete() ".mysql_error());
                    }
                }
                else
                {
                    depurar("ENMarca::delete() ".mysql_error());
                }
                
                BD::desconectar($conexion);
                
                // Hay que intentar borrar las anteriores. No importa si falla.
                borrar("img/marcas/".$this->getLogo());
                $thumbs = getThumbs($this->getLogo());
                foreach ($thumbs as $thumb)
                    borrar("img/marcas/".$thumb);
            }
            catch (Exception $e)
            {
                depurar("ENCategoria::delete() ".$e->getMessage());
            }
        }

        return $done;
    }
    
    function saveLogo($httpPostFile)
    {
        //http://emilio.aesinformatica.com/2007/05/03/subir-una-imagen-con-php/
        $creada = false;

        if (is_uploaded_file($httpPostFile['tmp_name']))
        {
            // Hay que intentar borrar las anteriores. No importa si falla.
            borrar("img/marcas/".$this->getLogo());
            $thumbs = getThumbs($this->getLogo());
            foreach ($thumbs as $thumb)
                borrar("img/marcas/".$thumb);

            $nombre = $this->id;
            $extension = pathinfo($httpPostFile['name'], PATHINFO_EXTENSION);

            $this->setLogo("$nombre.$extension");
            $thumbs = getThumbs("$nombre.$extension");
            $rutaFoto = "img/marcas/".$this->getLogo();

            // Luego hay que copiar el fichero de la imagen a la ruta de la foto.
            if (@move_uploaded_file($httpPostFile['tmp_name'], $rutaFoto))
            {
                if (@chmod($rutaFoto,0777))
                {
                    $counter = 1;
                    foreach ($thumbs as $thumb)
                    {
                        $rutaThumb = "img/marcas/".$thumb;
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
                        depurar("ENMarca::save() ".mysql_error());
                    }
                }
                else
                {
                    depurar("ENMarca::save() ".mysql_error());
                }
                
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                depurar("ENMarca::save() ".$e->getMessage());
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
                    depurar("ENMarca::update() ".mysql_error());
                }
                        
                BD::desconectar($conexion);
            }
            catch (Exception $e)
            {
                depurar("ENMarca::update() ".$e->getMessage());
            }
        }

        return $guardado;
    }
}
?>
