/************************************************************************
// Confirma el borrado de un modelo
*************************************************************************/
function validarModelo(formulario)
{
	var alerta = "";
	var caracteresValidos = /^[a-zA-Z0-9\.\ \:\n\(\)\ª\º\€]*$/;
	var esEntero = /^[0-9]{1,4}$/;
	var esFlotante = /^[0-9]{1,4}[\.][0-9]{1,2}$/;

	if (formulario.modelo.length == 0)
	{
		alerta = alerta + "El código de referencia debe tener al menos un caracter.\n";
	}
	else
	{
		if (formulario.modelo.length > 50)
		{
			alerta = alerta + "El código de referencia debe tener como mucho 50 caracteres.\n";
		}
	}

	if (formulario.descripcion.length > 5000)
	{
		alerta = alerta + "La descripción debe tener como mucho 5000 caracteres.\n";
	}

	if (!esEntero.test(formulario.precio_venta.value) || !esEntero.test(formulario.precio_venta.value))
	{
		alerta = alerta + "El precio de venta debe tener un formato: 0000.00";
	}

	if (!esEntero.test(formulario.precio_venta_minorista.value) || !esFlotante.test(formulario.precio_venta_minorista.value))
	{
		alerta = alerta + "El precio de venta minorista debe tener un formato: 0000.00";
	}

	if (!esEntero.test(formulario.precio_compra.value) || !esFlotante.test(formulario.precio_compra.value))
	{
		alerta = alerta + "El precio de compra debe tener un formato: 0000.00";
	}

/*	if (!ExpReg.test(formulario.usuario.value))
	{
		alerta = alerta + "El usuario sólo puede contener letras minúsculas (sin ñ, sin tildes) y números.\n";
	}
	else
		if (formulario.usuario.value.length<kMinUser)
			alerta = alerta + "El usuario debe tener una longitud mínima de "+kMinUser+" caracteres.\n";
		else
			if (formulario.usuario.value.length>kMaxUser)
				alerta = alerta + "El usuario debe tener una longitud máxima de "+kMaxUser+" caracteres.\n";

	if (formulario.nombre.value=="")
		alerta = alerta + "El nombre está vacío.\n";
	else
		if (formulario.nombre.value.length<kMinNom)
			alerta = alerta + "El nombre debe tener una longitud mínima de "+kMinNom+" caracteres.\n";
		else
			if (formulario.nombre.value.length>kMaxNom)
				alerta = alerta + "El nombre debe tener una longitud máxima de "+kMaxNom+" caracteres.\n";

	if (formulario.adicional.value.length>kMaxAdi)
		alerta = alerta + "La información adicional debe tener una longitud máxima de "+kMaxAdi+" caracteres.\n";

	if (formulario.password1.value=="")
		alerta = alerta + "La contraseña está vacía.\n";
	else
		if (formulario.password1.value!=formulario.password2.value)
			alerta = alerta + "Las contraseñas deben coincidir.\n";
		else
			if (formulario.password1.value.length<kMinPass)
				alerta = alerta + "Las contraseñas deben tener una longitud mínima de "+kMinPass+" caracteres.\n";
			else
				if (formulario.password1.value.length>kMaxPass)
					alerta = alerta + "Las contraseñas deben tener una longitud máxima de "+kMaxPass+" caracteres.\n";
				else
				{
					var ExpReg = /^[a-zA-Z0-9]*$/;
					if (!ExpReg.test(formulario.password1.value))
					{
						alerta = alerta + "La contraseña sólo puede contener letras (sin ñ, sin tildes) y números.\n";
					}
				}*/

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


/************************************************************************
// Confirma el borrado de un modelo
*************************************************************************/
function confirmarEliminarModelo()
{
	var alerta = "Se eliminará toda la información del modelo (incluidas las fotos asociadas).\n¿Desea continuar?";
	if (confirm(alerta))
	{
		return true;
	}
	else
	{
		return false;
	}
}


/************************************************************************
// Desbloquear y bloquear el formularió de edición
*************************************************************************/
var tamanoMaximo;
function desbloquearFormularioModelo()
{
	var formulario = document.getElementById("formularioeditarmodelo");
	formulario.modelo.readOnly = false;
	formulario.descripcion.readOnly = false;
	formulario.precio_venta.readOnly = false;
	formulario.precio_venta_minorista.readOnly = false;
	formulario.precio_compra.readOnly = false;
	formulario.primer_ano.readOnly = false;
	formulario.fabricante.disabled = '';
	document.getElementById("bloquear").style.width = document.getElementById("desbloquear").offsetWidth+"px";
	document.getElementById("bloquear").style.display = 'inline';
	document.getElementById("desbloquear").style.display = 'none';
	document.getElementById("botonsubmit").disabled = '';
	document.getElementById("botonreset").disabled = '';
	document.getElementById("botoneliminar").disabled = '';
}

function bloquearFormularioModelo()
{
	var formulario = document.getElementById("formularioeditarmodelo");
	formulario.modelo.readOnly = true;
	formulario.descripcion.readOnly = true;
	formulario.precio_venta.readOnly = true;
	formulario.precio_venta_minorista.readOnly = true;
	formulario.precio_compra.readOnly = true;
	formulario.primer_ano.readOnly = true;
	formulario.fabricante.disabled = 'disabled';
	document.getElementById("bloquear").style.display = 'none';
	document.getElementById("desbloquear").style.display = 'inline';
	document.getElementById("botonsubmit").disabled = 'disabled';
	document.getElementById("botonreset").disabled = 'disabled';
	document.getElementById("botoneliminar").disabled = 'disabled';
}


/************************************************************************
// Eliminar una foto
*************************************************************************/
function eliminarFoto(id)
{
	var alerta = "¿Está seguro de que quiere eliminar la foto?";
	if (confirm(alerta))
	{
		ajax=nuevoAjax();
		ajax.open("POST", "operarmodelo.php",true);
		ajax.onreadystatechange = function()
		{
			if (ajax.readyState==4)
			{
				// Aquí deberá comprobarse si la petición AJAX ha sido correcta.
				if(ajax.responseText!="OK")
				{
					alert ("No se ha podido eliminar la foto.\n" + ajax.responseText);
				}
				else
				{
					document.getElementById('foto'+id).style.display = "none";
				}
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("operacion=eliminarfoto&id="+id);
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