/*************************************************
// Constantes
*************************************************/
var kMinContrasena = 3;
var kMaxContrasena = 100;
var kMinNombre = 3;
var kMaxNombre = 100;

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

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};

function validarRegistro(formulario)
{
    var alerta = "";

    // Comprobamos que la contraseña es válida.
    if (formulario.nombre.value.length < kMinNombre || formulario.nombre.value.length > kMaxNombre)
        alerta = alerta + "- El nombre debe tener entre " + kMinNombre + " y " + kMaxNombre + " caracteres.\n\n";

    // Comprobamos que la contraseña es válida.
    if (formulario.contrasena.value.length < kMinContrasena || formulario.contrasena.value.length > kMaxContrasena)
        alerta = alerta + "- La contraseña debe tener entre " + kMinContrasena + " y " + kMaxContrasena + " caracteres.\n\n";

    // Comprobamos que las contraseñas coinciden.
    if (formulario.contrasena.value != formulario.contrasena2.value)
        alerta = alerta + "- Las contraseñas no coinciden.\n\n";

    if (formulario.telefono.value.length < 5) {
        alerta = alerta + "- Debe facilitar un teléfono de contacto.\n\n";
    }

    // Comprobamos que se ha marcado algún sexo.
    if (!formulario.sexo[0].checked && !formulario.sexo[1].checked)
        alerta = alerta + "- Es necesario seleccionar algún sexo.\n\n";

    // Comprobamos que la dirección de correo electrónico es correcta.
    if (!isValidEmailAddress(formulario.email.value))
        alerta = alerta + "- La dirección de e-mail no tiene un formato válido.\n\n";

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

function validarUsuario(formulario)
{
    var alerta = "";

    // Comprobamos que la contraseña es válida.
    if (formulario.nombre.value.length < kMinNombre || formulario.nombre.value.length > kMaxNombre)
        alerta = alerta + "- El nombre debe tener entre " + kMinNombre + " y " + kMaxNombre + " caracteres.\n\n";

    // Comprobamos que la contraseña es válida.
    if (formulario.contrasena.value.length > 0 || formulario.contrasena2.value.length > 0)
    {
        // Comprobamos que las contraseñas coinciden.
        if (formulario.contrasena.value != formulario.contrasena2.value)
            alerta = alerta + "- Las contraseñas no coinciden.\n\n";
        
        if (formulario.contrasena.value.length < kMinContrasena || formulario.contrasena.value.length > kMaxContrasena)
            alerta = alerta + "- La contraseña debe tener entre " + kMinContrasena + " y " + kMaxContrasena + " caracteres.\n\n";
    }
    
    if (formulario.telefono.value.length < 5) {
        alerta = alerta + "- Debe facilitar un teléfono de contacto.\n\n";
    }

    // Comprobamos que se ha marcado algún sexo.
    if (!formulario.sexo[0].checked && !formulario.sexo[1].checked)
        alerta = alerta + "- Es necesario seleccionar algún sexo.\n\n";

    // Comprobamos que la dirección de correo electrónico es correcta.
    if (!isValidEmailAddress(formulario.email.value))
        alerta = alerta + "- La dirección de e-mail no tiene un formato válido.\n\n";

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

    formulario.scroll.value = $(document).scrollTop();

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

function confirmarBorrarReserva()
{
    return confirm("¿Estás seguro de que quieres borrar la reserva?");
}

function comprobanteDia(dia, anterior)
{    
    ajax = nuevoAjax();
    ajax.open("POST", "comprobante.php", true);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (anterior != ajax.responseText)
            {
                var scrollposition = $(document).scrollTop();
                window.location.href = "reservar.php?dia=" + dia + "&scroll=" + scrollposition;
            }
        }
    }
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("dia="+dia);
}

function realizarCobro(id, cobrado)
{
    ajax = nuevoAjax();
    ajax.open("POST", "operarcobro", true);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.responseText == "OK")
            {
                document.getElementById("cobrado"+id).value = cobrado + "€";
            }
        }
    }
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("id="+id+"&cobrado="+cobrado);
}

function buscarUsuarios(formulario)
{
    if (formulario == null)
    {
        formulario = document.getElementById("busqueda-form");

        if (formulario.filtro.value.length < 3)
            return;
    }
    
    ajax = nuevoAjax();
    ajax.open("POST", "usuarios_ajax", true);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.responseText != "ERROR")
            {
                $("#dialogo-seleccionar-contenedor").html(ajax.responseText);
            }
        }
    }
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("filtro="+formulario.filtro.value);
}
