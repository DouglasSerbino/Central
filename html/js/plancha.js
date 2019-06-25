function agregar_f()
{
	txtcantidad = $('#cantidad').val();
	txtancho = $('#ancho').val();
	txtalto = $('#alto').val();
	
	//Elimina los espacios en blanco de la caja de texto
	txtcantidad = txtcantidad.replace(/ /g, '')
	txtancho = txtancho.replace(/ /g, '')
	txtalto = txtalto.replace(/ /g, '')
	
	if(isNaN(txtalto))
	{
		alert("Debe digitar solo valores numericos.")
		$('#alto').focus();
		return false
	}
	if(isNaN(txtancho))
	{
		alert("Debe digitar solo valores numericos.")
		$('#ancho').val();
		return false
	}
	if(isNaN(txtcantidad))
	{
		alert("Debe digitar solo valores numericos.")
		$('#cantidad').focus();
		return false
	}
	
	//Al estar correcta la caja de texto se envia
	return true;
}

function de_regre(codigo)
{
	window.location = "/planchas/consultar_material/index/"+codigo+"/nombre_tipo"
}

function busque_f()
{
	txtalto = $('#alto').val();
	txtancho = $('#ancho').val();
	txtancho = txtancho.replace(/ /g, '');
	txtalto = txtalto.replace(/ /g, '');
	if(txtancho == '' || txtalto == '')
	{
		alert("Por favor ingrese las medidas de la plancha a buscar.");
		return false;
	}
	
	if(isNaN(txtalto) || isNaN(txtancho)){
		alert("Debe digitar solo valores numericos.");
		return false;
	}
	
	return true;
}

function validar_m()
{
	txtcodigo = $('#codigo').val();
	txtaltura = $('#altura').val();
	txtubicacion = $('#ubicacion').val();
	//Elimina los espacios en blanco de la caja de texto
	txtcodigo = txtcodigo.replace(/ /g, '')
	txtaltura = txtaltura.replace(/ /g, '')
	txtubicacion = txtubicacion.replace(/ /g, '')
	
	//Si los campos estan en blanco
	if(txtcodigo == "")
	{
		alert("Por favor digite el codigo de la plancha a ingresar.")
		$('#codigo').focus()
		return false;
	}
	if(txtaltura == ""){
		alert("Por favor ingrese la altura de la plancha.")
		$('#altura').focus()
		return false
	}
	if(txtubicacion == ""){
		alert("Por favor ingrese el lugar en que se almacenara.")
		$('#ubicacion').focus()
		return false
	}
	
	//Al estar correcta la caja de texto se envia
	$('#miform').submit();
}

function plancha_grafico()
{
	cod_plancha = $("#cod_plancha").val();
	anho = $("#anho").val();
	window.location = "/planchas/plancha_gra/index/"+cod_plancha+"/"+anho;	
}

function plancha_historial()
{
	if(confirm("Se agregara al sistema el total de este mes de pulgadas para cada plancha.\r\nDesea coninuar?"))
	{
		window.location = '/planchas/consultar_material/agregar_datos';	
	}
}
