/************************************************************************
// Valida un modelo
*************************************************************************/
function validarModelo(formulario)
{
	var alerta = "";
	var esEntero = /^[0-9]{1,4}$/;
	var esFlotante = /^[0-9]{1,4}[\.,][0-9]{1,2}$/;

	if (formulario.modelo.value.length == 0)
	{
		alerta = alerta + "El código de referencia debe tener al menos un caracter.\n";
	}
	else
	{
		if (formulario.modelo.value.length > 50)
		{
			alerta = alerta + "El código de referencia debe tener como mucho 50 caracteres.\n";
		}
	}

	if (formulario.descripcion.value.length > 5000)
	{
		alerta = alerta + "La descripción debe tener como mucho 5000 caracteres.\n";
	}

	if (!esEntero.test(formulario.precio_venta.value) && !esFlotante.test(formulario.precio_venta.value))
	{
		alerta = alerta + "El precio de venta debe tener un formato: 0000.00\n";
	}

	if (!esEntero.test(formulario.precio_venta_minorista.value) && !esFlotante.test(formulario.precio_venta_minorista.value))
	{
		alerta = alerta + "El precio de venta minorista debe tener un formato: 0000.00\n";
	}

	if (!esEntero.test(formulario.precio_compra.value) && !esFlotante.test(formulario.precio_compra.value))
	{
		alerta = alerta + "El precio de compra debe tener un formato: 0000.00\n";
	}

	if (!esEntero.test(formulario.primer_ano.value) || formulario.primer_ano.value.length != 4)
	{
		alerta = alerta + "El primer año de fabricación debe tener 4 números. Por ejemplo, 2009.\n";
	}

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
	var alerta = "Se eliminará toda la información del modelo (incluidas las fotos asociadas) y desaparecerá de todos los catálogos.\n¿Desea continuar?";
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
					alert ("No se ha podido eliminar la foto.\n");
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

/************************************************************************
// Confirma el borrado de un fabricante
*************************************************************************/
function confirmarEliminarFabricante()
{
	var alerta = "(Primera confirmación)\n\nSe eliminará toda la información del fabricante (incluidos sus modelos).\n¿Desea continuar?";
	if (confirm(alerta))
	{
		alerta = "(Segunda confirmación)\n\nSi elimina el fabricante, también eliminará todos los modelos vinculados, sin posibilidad de recuperación.\n¿Está seguro?";
		if (confirm(alerta))
		{
			alerta = "(Última confirmación)\n\n¿Definitivamente quiere hacerlo?";
			if (confirm(alerta))
			{
				return true;
			}
		}
	}
	return false;
}

/************************************************************************
// Desbloquear y bloquear el formularió de edición
*************************************************************************/
function desbloquearFormularioFabricante()
{
	var formulario = document.getElementById("formularioeditarfabricante");
	formulario.nombre.readOnly = false;
	formulario.telefono.readOnly = false;
	formulario.informacion_adicional.readOnly = false;
	document.getElementById("bloquear").style.width = document.getElementById("desbloquear").offsetWidth+"px";
	document.getElementById("bloquear").style.display = 'inline';
	document.getElementById("desbloquear").style.display = 'none';
	document.getElementById("botonsubmit").disabled = '';
	document.getElementById("botonreset").disabled = '';
	document.getElementById("botoneliminar").disabled = '';
}

function bloquearFormularioFabricante()
{
	var formulario = document.getElementById("formularioeditarfabricante");
	formulario.nombre.readOnly = true;
	formulario.telefono.readOnly = true;
	formulario.informacion_adicional.readOnly = true;
	document.getElementById("bloquear").style.display = 'none';
	document.getElementById("desbloquear").style.display = 'inline';
	document.getElementById("botonsubmit").disabled = 'disabled';
	document.getElementById("botonreset").disabled = 'disabled';
	document.getElementById("botoneliminar").disabled = 'disabled';
}

/************************************************************************
// Valida un fabricante
*************************************************************************/
function validarFabricante(formulario)
{
	var alerta = "";

	if (formulario.nombre.value.length < 2)
	{
		alerta = alerta + "El nombre debe tener al menos 2 caracteres.\n";
	}
	else
	{
		if (formulario.nombre.value.length > 50)
		{
			alerta = alerta + "El nombre debe tener como mucho 50 caracteres.\n";
		}
	}

	if (formulario.telefono.value.length > 500)
	{
		alerta = alerta + "El campo del teléfono debe tener como mucho 500 caracteres.\n";
	}

	if (formulario.informacion_adicional.value.length > 5000)
	{
		alerta = alerta + "La información adicional debe tener como mucho 5000 caracteres.\n";
	}

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
// Mostrar imagen al lado del ratón
*************************************************************************/
var miniaturas = true;
function mostrarImagenRaton(ruta)
{
	if (!miniaturas)
	{
		document.getElementById("imagenraton").src = ruta;
		document.getElementById("capaimagenraton").style.display = "block";
		document.getElementById("capaimagenraton").style.top = (posY) + "px";
		document.getElementById("capaimagenraton").style.left = (posX-30-document.getElementById("imagenraton").width) + "px";
	}
}

function ocultarImagenRaton()
{
	if (!miniaturas)
		document.getElementById("capaimagenraton").style.display = "none";
}

function registrarCoordenadas(event)
{
	if (!miniaturas)
	{
		document.getElementById("capaimagenraton").style.top = (event.clientY)+document.documentElement.scrollTop + "px";
		document.getElementById("capaimagenraton").style.left = (event.clientX-30-document.getElementById("imagenraton").width)+document.documentElement.scrollLeft + "px";
	}
}

function permutarMiniaturas()
{
	miniaturas = !miniaturas;

	var tds;
	if (!miniaturas)
	{
		tds = document.getElementsByTagName("td");
		for (i = 0; i < tds.length; i++)
		{
			if (tds[i].className == "columnafoto")
			{
				tds[i].style.display = 'none';
			}
		}
	}
	else
	{
		tds = document.getElementsByTagName("td");
		for (i = 0; i < tds.length; i++)
		{
			if (tds[i].className == "columnafoto")
				tds[i].style.display = '';
		}
	}

	ajax=nuevoAjax();
	ajax.open("POST", "operarmodelo.php",true);
	ajax.onreadystatechange = function()
	{
		/*if (ajax.readyState==4)
		{
			// Aquí deberá comprobarse si la petición AJAX ha sido correcta.
			if(ajax.responseText!="OK")
			{
				alert ("No se ha podido eliminar la foto.\n" + ajax.responseText);
			}
			else
			{
				alert (ajax.responseText);
			}
		}*/
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("operacion=miniaturas");
}

/************************************************************************
// Eliminar un catálogo
*************************************************************************/
function eliminarCatalogo(id)
{
	var alerta = "¿Está seguro de que quiere eliminar el catálogo? No podrá volver a imprimirlo ni editarlo.";
	if (confirm(alerta))
	{
		ajax=nuevoAjax();
		ajax.open("POST", "operarcatalogo.php",true);
		ajax.onreadystatechange = function()
		{
			if (ajax.readyState==4)
			{
				// Aquí deberá comprobarse si la petición AJAX ha sido correcta.
				if(ajax.responseText!="OK")
				{
					alert ("No se ha podido eliminar el catálogo.\n");
				}
				else
				{
					document.getElementById('catalogo'+id).style.visibility = "hidden";
				}
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("operacion=borrar&id="+id);
	}
}

/************************************************************************
// Seleccionar catálogo
*************************************************************************/
function seleccionarCatalogo(id)
{
	ajax=nuevoAjax();
	ajax.open("POST", "operarcatalogo.php",true);
	ajax.onreadystatechange = function()
	{
		if (ajax.readyState==4)
		{
			// Aquí deberá comprobarse si la petición AJAX ha sido correcta.
			var trs = document.getElementsByTagName("tr");
			for (i = 0; i < trs.length; i++)
			{
				trs[i].style.backgroundImage = 'none';
				trs[i].style.fontWeight = 'normal';
			}
			if(ajax.responseText=="SELECCIONADO")
			{
				document.getElementById('catalogo'+id).style.backgroundImage = "url('estilo/seleccionado.png')";
				document.getElementById('catalogo'+id).style.fontWeight = 'bold';
			}
			else if(ajax.responseText=="DESSELECCIONADO")
			{

			}
			else
			{
				alert ("No se pudo seleccionar el catálogo.\n");
			}
		}
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("operacion=seleccionar&id="+id);
}

/************************************************************************
// Permutael estado de un modelo en el catálogo abierto
*************************************************************************/
function permutarModeloCatalogo(id)
{
	//var alerta = "¿Está seguro de hacer el cambio?";
	//if (confirm(alerta))
	//{
		ajax=nuevoAjax();
		ajax.open("POST", "operarcatalogo.php",true);
		ajax.onreadystatechange = function()
		{
			if (ajax.readyState==4)
			{
				// Aquí deberá comprobarse si la petición AJAX ha sido correcta.
				if(ajax.responseText=="INSERTADO")
				{
					document.getElementById("permutarModeloCatalogo"+id).src = 'estilo/dentro.png';
				}
				else if (ajax.responseText=="QUITADO")
				{
					document.getElementById("permutarModeloCatalogo"+id).src = 'estilo/fuera.png';
				}
				else
				{
					alert (ajax.responseText);
				}
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("operacion=permutarmodelo&id_modelo="+id);
	//}
}

/*setTimeout (function () { cambiarOpacidad(capa, 65) },4750);
function cambiarOpacidad (obj, nivel)
{
		obj.style.opacity = "." + nivel;
		obj.style.filter = "alpha(opacity = " + nivel + ")";
}*/