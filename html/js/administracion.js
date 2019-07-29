function haps_agr_sap_prev(id_pedido, fecha_entrada, id_cliente){//Voy a mostrar un cuadro solicitando la informacion del pedido
	//Necesito la siguiente informacion: Pedido Sap, Venta total, Fecha en que se realizo el pedido (tomare la que viene en el ajax anterior, si el usuario lo quiere la puede cambiar)
	$('#id_pedido').val(id_pedido);//Agrego al campo oculto el id_pedido a ingresar
	$('#id_cliente').val(id_cliente);//Agrego al campo oculto el id_cliente a ingresar
	$('#informacion_sap :text').val('');//Vacio todos los campos
	$('#fecha').val(fecha_entrada);//Agrego al campo fecha la fecha de ingreso de este pedido
	$('#haps-informacion_sap').show();//Muestro la caja con los campos a llenar
	$('#pedido_sap').focus();//Pongo el foco en el campo primero
}

function ocultar_ventana(ventana){//Voy a ocultar una ventana... XP
	$('#'+ventana).hide();//Oculto la ventana
}


function guardar_chat()
{
	var msj = $.trim($('#msj').val());
	$.ajax(
	{
		type: "POST",
		url: "/herramientas_sis/chat/guardar_chat/"+msj,
		data: '',
		success: function(msg)
		{
			var myObject = JSON.parse(msg);
				var total_procesos = myObject['proc'].length;
				//alert(total_procesos);
				$('#m_msj').empty()
				$('#m_msj').append('<a href="javascript:cerrar_ventana()"><span id="cerrar_ventana">Cerrar</span></a><br />');
				for(i = 0; i < total_procesos; i++)
				{
					$('#m_msj').append('<font style="font-size:11px; text-aling: left;"><strong>'+myObject['proc'][i][0]+'</strong>=> '+myObject['proc'][i][1]+'</font><br />');
				}
			$('#m_msj').css("display", "block");
			
		},
		error: function(msg)
		{
			alert('Lo sentimos. Ocurri\xf3 un error en la acci\xf3n solicitada.');
		}
	});
}

function cerrar_ventana(){
	$("#m_msj").hide();
}