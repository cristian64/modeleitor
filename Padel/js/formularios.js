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

    // Comprobamos que se ha marcado algún sexo.
    if (!formulario.sexo[0].checked && !formulario.sexo[1].checked)
        alerta = alerta + "- Es necesario seleccionar algún sexo.\n\n";

    // Comprobamos que la dirección de correo electrónico es correcta.
    var expReg = new RegExp("^[a-zA-Z]+([\\.-]?[a-zA-Z0-9]+)*@[a-zA-Z]+([\\.-]?[a-zA-Z0-9]+)*(\\.[a-zA-Z0-9]{2,4})+$");
    if (!expReg.test(formulario.email.value))
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

    // Comprobamos que se ha marcado algún sexo.
    if (!formulario.sexo[0].checked && !formulario.sexo[1].checked)
        alerta = alerta + "- Es necesario seleccionar algún sexo.\n\n";

    // Comprobamos que la dirección de correo electrónico es correcta.
    var expReg = new RegExp("^[a-zA-Z]+([\\.-]?[a-zA-Z0-9]+)*@[a-zA-Z]+([\\.-]?[a-zA-Z0-9]+)*(\\.[a-zA-Z0-9]{2,4})+$");
    if (!expReg.test(formulario.email.value))
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
