/*************************************************
// Constantes
*************************************************/
var kMinContrasena = 4;
var kMaxContrasena = 100;
var kMinNombre = 4;
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

function validarRegistro()
{
    var email = comprobarEmail();
    var contrasena = comprobarContrasena();
    var contrasena2 = comprobarContrasena2();
    var nombre = comprobarNombre();
    var telefono = comprobarTelefono();
    return email && contrasena && contrasena2 && nombre && telefono;
}

function comprobarEmail()
{
    var formulario = document.getElementById('crearcuenta');
    var expReg = new RegExp("^[a-zA-Z]+([\\.-]?[a-zA-Z0-9]+)*@[a-zA-Z]+([\\.-]?[a-zA-Z0-9]+)*(\\.[a-zA-Z0-9]{2,4})+$");
    if (!expReg.test(formulario.email.value))
    {
        $('#error-email').show('blind');
        return false;
    }
    else
    {
        $('#error-email').hide();
        return true;
    }
}

function comprobarContrasena()
{
    var formulario = document.getElementById('crearcuenta');
    if (formulario.contrasena.value.length < kMinContrasena || formulario.contrasena.value.length > kMaxContrasena)
    {
        $('#error-contrasena').show('blind');
        return false;
    }
    else
    {
        $('#error-contrasena').hide();
        return true;
    }
}

function comprobarContrasena2()
{
    var formulario = document.getElementById('crearcuenta');
    if (formulario.contrasena.value != formulario.contrasena2.value)
    {
        $('#error-contrasena2').show('blind');
        return false;
    }
    else
    {
        $('#error-contrasena2').hide();
        return true;
    }
}

function comprobarNombre()
{
    var formulario = document.getElementById('crearcuenta');
    if (formulario.nombre.value.length < kMinNombre || formulario.nombre.value.length > kMaxNombre)
    {
        $('#error-nombre').show('blind');
        return false;
    }
    else
    {
        $('#error-nombre').hide();
        return true;
    }
}

function comprobarTelefono()
{
    var formulario = document.getElementById('crearcuenta');
    if (formulario.telefono.value.length == 0)
    {
        $('#error-telefono').show('blind');
        return false;
    }
    else
    {
        $('#error-telefono').hide();
        return true;
    }
}

/*function comprobarContrasena(formulario)
{
    if (formulario.contrasena.value.length < kMinContrasena || formulario.contrasena.value.length > kMaxContrasena)
    {
        alerta = alerta + "- La contraseña debe tener entre " + kMinContrasena + " y " + kMaxContrasena + " caracteres.\n\n";
    }

    // Comprobamos que las contraseñas coinciden.
    if (formulario.contrasena.value != formulario.contrasena2.value)
        alerta = alerta + "- Las contraseñas no coinciden.\n\n";
}*/

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

function cargarModelo(id)
{    
    ajax = nuevoAjax();
    ajax.open("POST", "ajax_modelo", true);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.responseText != "ERROR")
            {
                $('#modelo-loading').show();
                $("#modelo-modal").hide();
                $("#modelo-modal").html(ajax.responseText);
                
                $('#modelo-modal').height($(window).height() * 0.95);
                $('#modelo-img').height($(window).height() * 0.7);
                
                $('#myModal').reveal({
                    animation: 'none'
                });
                $('#myModal').position({
                    of: $(window)
                });
                
                
                $('#modelo-img').load(function (){
                    $("#modelo-modal").show();
                    $('#modelo-loading').hide();
                    $('#myModal').position({
                        of: $(window)
                    });
                });
            }
        }
    }
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("id="+id);
}
