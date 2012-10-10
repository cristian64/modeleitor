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
    var apellidos = comprobarApellidos();
    var telefono = comprobarTelefono();
    var empresa = comprobarEmpresa();
    var cif = comprobarCif();
    var direccion = comprobarDireccion();
    var cp = comprobarCp();
    var ciudad = comprobarCiudad();
    return email && contrasena && contrasena2 && nombre && apellidos && telefono && empresa && cif && direccion && cp && ciudad;
}
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};
function comprobarEmail()
{
    var formulario = document.getElementById('crearcuenta');
    if (!isValidEmailAddress(formulario.email.value))
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

function comprobarApellidos()
{
    var formulario = document.getElementById('crearcuenta');
    if (formulario.apellidos.value.length < kMinNombre || formulario.apellidos.value.length > kMaxNombre)
    {
        $('#error-apellidos').show('blind');
        return false;
    }
    else
    {
        $('#error-apellidos').hide();
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

function comprobarEmpresa()
{
    var formulario = document.getElementById('crearcuenta');
    if (formulario.empresa.value.length == 0)
    {
        $('#error-empresa').show('blind');
        return false;
    }
    else
    {
        $('#error-empresa').hide();
        return true;
    }
}

function comprobarCif()
{
    var formulario = document.getElementById('crearcuenta');
    if (formulario.cif.value.length == 0)
    {
        $('#error-cif').show('blind');
        return false;
    }
    else
    {
        $('#error-cif').hide();
        return true;
    }
}

function comprobarDireccion()
{
    var formulario = document.getElementById('crearcuenta');
    if (formulario.direccion.value.length == 0)
    {
        $('#error-direccion').show('blind');
        return false;
    }
    else
    {
        $('#error-direccion').hide();
        return true;
    }
}

function comprobarCp()
{
    var formulario = document.getElementById('crearcuenta');
    if (formulario.cp.value.length == 0)
    {
        $('#error-cp').show('blind');
        return false;
    }
    else
    {
        $('#error-cp').hide();
        return true;
    }
}

function comprobarCiudad()
{
    var formulario = document.getElementById('crearcuenta');
    if (formulario.ciudad.value.length == 0)
    {
        $('#error-ciudad').show('blind');
        return false;
    }
    else
    {
        $('#error-ciudad').hide();
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
    document.location.hash = id;
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
