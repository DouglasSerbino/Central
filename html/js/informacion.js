//Busco el cliente correspondiente al codigo ingresado
function verinformacion(id_cliente)
{
	var codigo_cliente = $("#id_cliente").val();
	//Si no han digitado un id_cliente no puedo hacer la busqueda
	if(codigo_cliente == "")
	{
		$("#cliente").val('');
		$('#contacto').val('');
		$('#telefono').val('');
		$('#email').val('');
		$('#id_cliente_m').val();
	}
	else
	{
		//Si hay algo escrito.
		//Funcion get de Jquery, envio el codigo del cliente
		$.get("/ventas/preingreso/busquedad_cliente/"+codigo_cliente, function(d){
			//Cambio el value del campo cliente por el resultado de la busqueda
			var info_cliente = d.split('[-]');
			$('#cliente').val(info_cliente[0]);
			$('#contacto').val(info_cliente[1]);
			$('#telefono').val(info_cliente[2]);
			$('#email').val(info_cliente[3]);
			$('#id_cliente_mandar').val(info_cliente[4]);
		});
	}
}

function mostrar()
{
	document.mandar.submit();
}

function sumar(caja)
{
	var cadena = 0;
	var perla = 0;
	var beads = 0;
	if($('#cadena_'+caja).val() != '')
	{
		cadena = parseInt($('#cadena_'+caja).val());
	}
	if($('#perla_'+caja).val() != '')
	{
		perla = parseInt($('#perla_'+caja).val());
	}
	if($('#beads_'+caja).val() != '')
	{
		beads = parseInt($('#beads_'+caja).val());
	}
	var suma = (cadena + perla + beads);
	$('#total_'+caja).val(suma);
}