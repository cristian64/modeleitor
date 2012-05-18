/*************************************************
// Constantes
*************************************************/
var kMinContrasena = 4;
var kMaxContrasena = 50;

function validarIdentificacion(formulario)
{
    // Se extraen las cadenas con el email y la contraseña y se eliminan espacios y tabuladores.
    var email = formulario.email.value.replace(" ", "").replace("\t", "");
    while (email != email.replace(" ", "").replace("\t", ""))
        email = email.replace(" ", "").replace("\t", "");
    var contrasena = formulario.contrasena.value.replace(" ", "").replace("\t", "");
    while (contrasena != contrasena.replace(" ", "").replace("\t", ""))
        contrasena = contrasena.replace(" ", "").replace("\t", "");

    // Se comprueba que todavía existan caracteres en el nombre de usuario y en la contraseña para permitir la consulta al servidor.
    if (email.length > 0 && contrasena.length > 0)
        return true;
    else
        return false;
}

function validarRegistro(formulario)
{
    var alerta = "";

    // Comprobamos que la contraseña es válida.
    if (!new RegExp("^[a-zA-Z0-9_]{" + kMinContrasena + "," + kMaxContrasena + "}$").test(formulario.contrasena.value))
        alerta = alerta + "- La contraseña sólo puede contener números, letras y subrayado (_) y debe tener entre " + kMinContrasena + " y " + kMaxContrasena + " caracteres.\n\n";

    // Comprobamos que las contraseñas coinciden.
    if (formulario.contrasena.value != formulario.contrasena2.value)
        alerta = alerta + "- Las contraseñas no coinciden.\n\n";

    // Comprobamos que se ha marcado algún sexo.
    if (!formulario.sexo[0].checked && !formulario.sexo[1].checked)
        alerta = alerta + "- Es necesario seleccionar algún sexo.\n\n";

    // Comprobamos que la dirección de correo electrónico es correcta.
    var expReg = new RegExp("^[a-zA-Z]+([\\.-]?[a-zA-Z0-9]+)*@[a-zA-Z]+([\\.-]?[a-zA-Z0-9]+)*(\\.[a-zA-Z0-9]{2,4})+$");
    if (!expReg.test(formulario.email.value))
        alerta = alerta + "- La dirección de e-mail no es válida.\n\n";

    // Si se ha concatenado algún error, se muestra el mensaje y se aborta el "submit" del formulario devolviendo falso.
    if (alerta != "")
    {
        alert(alerta);
        return false;
    }
    else
    {
        return true;
    }
}

function validarReserva(formulario)
{
    var alerta = "";

    // Comprobamos que la contraseña es válida.
    if (!new RegExp("^[0-9_]+$").test(formulario.duracion.value))
        alerta = alerta + "Debes elegir un horario para confirmar la reserva.";

    // Si se ha concatenado algún error, se muestra el mensaje y se aborta el "submit" del formulario devolviendo falso.
    if (alerta != "")
    {
        alert(alerta);
        return false;
    }
    else
    {
        return true;
    }
}

function nuevoAjax()
{
    var xmlhttp=false;
     try
     {
         xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
     }
     catch (e)
     {
         try
         {
             xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
         }
         catch (E) {
             xmlhttp = false;
         }
      }

    if (!xmlhttp && typeof XMLHttpRequest!='undefined')
    {
         xmlhttp = new XMLHttpRequest();
    }

    return xmlhttp;
}


function confirmarEliminarAlbum()
{
    return confirm("Se eliminarán todas las fotos del álbum y no se podrán recuperar.\n¿Desea continuar?")
}

function eliminarAlbum(id)
{
    if (confirmarEliminarAlbum())
    {
        ajax=nuevoAjax();
        ajax.open("POST", "eliminaralbum.php",true);
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState==4)
            {
                // Aquí deberá comprobarse si la petición AJAX ha sido correcta.
                if(ajax.responseText!="OK")
                {
                    alert ("No se ha podido eliminar el álbum.\n");
                }
                else
                {
                    document.getElementById('album'+id).style.display = "none";
                }
            }
        }
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send("id="+id);
    }
}

function confirmarEliminarFoto()
{
    return confirm("Se eliminará la foto y no se podrá recuperar.\n¿Desea continuar?")
}

function eliminarFoto(id)
{
    if (confirmarEliminarFoto())
    {
        ajax=nuevoAjax();
        ajax.open("POST", "eliminarfoto.php",true);
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState==4)
            {
                // Aquí deberá comprobarse si la petición AJAX ha sido correcta.
                if(ajax.responseText!="OK")
                {
                    alert ("No se ha podido eliminar la foto.\n");
                }
                else
                {
                    document.getElementById('foto'+id).style.display = "none";
                    document.getElementById('cantidad').firstChild.textContent = document.getElementById('cantidad').firstChild.textContent - 1;
                }
            }
        }
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send("id="+id);
    }
}
