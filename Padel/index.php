<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
        require_once("minilibreria.php");
        
        $usuarios = ENUsuario::obtenerTodos();
        foreach ($usuarios as $usuario)
        {
            echo $usuario->toString()."<br />";
        }
        
        echo "<hr 7>";
        
        echo ENUsuario::obtenerPorEmail("cristian@correo.com")->toString()."<br />";
        echo ENUsuario::obtenerPorEmail("santi@correo.com")->toString()."<br />";
        echo ENUsuario::obtenerPorEmail("cristian@correo.com")->toString()."<br />";
        echo ENUsuario::obtenerPorEmail("cristian@correo.com")->toString()."<br />";
        
        echo "<hr 7>";
        
        echo ENUsuario::obtenerPorId(1)->toString()."<br />";
        echo ENUsuario::obtenerPorId("1")->toString()."<br />";
        echo ENUsuario::obtenerPorId("1")->toString()."<br />";
        echo ENUsuario::obtenerPorId("2")->toString()."<br />";
        
        echo "<hr 7>";
        
        $usuario = new ENUsuario();
        $usuario->setAdmin(0);
        $usuario->setContrasena("CONTRASEÑA");
        $usuario->setDireccion("calle falsa 123");
        $usuario->setDni("74236861Z");
        $usuario->setEmail("aaaaaaaaaaa.com");
        $usuario->setNombre("Cristiano Messi");
        $usuario->setSexo("hombre");
        $usuario->setTelefono("+34 000000000");
        if ($usuario->guardar())
            echo "Guardado correctamente!";
        else
            echo "No guardado correctamente!";
        
        $cristian = ENUsuario::obtenerPorId(1);
        $cristian->setDni($cristian->getDni()."a");
        $cristian->setEmail($cristian->getEmail()."a");
        $cristian->setTelefono($cristian->getEmail()."a");
        $cristian->setNombre($cristian->getNombre()."a");
        $cristian->setAdmin($cristian->getAdmin() == 0 ? 1 : 0);
        $cristian->setSexo($cristian->getSexo() == "mujer" ? "hombre" : "mujer");
        $cristian->actualizar();
        ?>
    </body>
</html>
