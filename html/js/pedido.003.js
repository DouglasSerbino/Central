
/**
 *Funciones usadas en la parte de creacion y modificacion de pedidos
 *Creado por Daniel Echeverria (Daes 12)
 *Requiere la libreria JQUERY
 */
//Declaracion de variables globales
var cotizacion_mostrar = false;
var puestos_originales = new Array();
var puesto_asignado = 0;
var texto_submit = '';

//Pintar o despintar un puesto de trabajo
function poner_color_fila(cheque,objeto){
	
	if($("#"+cheque).attr("checked"))//Si el cheque que invoca la funcion esta chequeado
	{
		$("#"+objeto).addClass("selec");//Le aplico la clase selec
		$("#"+objeto+' label').css('fontWeight', 'bold');
	}
	else//Si no
	{
		$("#"+objeto).removeClass("selec");//Le quito la clase selec
		$("#"+objeto+' label').css('fontWeight', 'normal');
	}
	//calcular_fecha_entrega('');//Llamo a la funcion requerida para ver las fechas de entrega reales
}


//Calcular la fecha de entrega posible para los trabajos
function calcular_fecha_entrega()
{

	
	var submit_label = $('#btn_submit').val();
	$('#btn_submit').val('Validando...');
	
	var tiempo_convertidos = new Array();//Almacena los tiempos convertidos a minutos
	var puestos = false;//Debe existir uno o mas puestos
	var fecha_entrega = $('#fecha_entrega').val();
	var id_usuario = '';
	var tiempo_usuario = '';
	var usuarios = '';
	var mandar_tiempo_usuario = '';
	productos = '';
	planchas_v = new Array(18, 26, 29, 30, 38, 57, 58);//Estos son los items de la cotizacion de planchas que afectan su tiempo
	pulgas = 24;//Cada minuto planchas es capas de procesar 29.76in2 de fotopolimero
	
	
	$('#ruta_trabajo input[type=checkbox]').each(function()
	{
		
		if('none' == $('#fecha_entrega').css('display'))
		{
			
			if($(this).attr('checked'))
			{
				
				puestos = true;//Encontramos un puesto de trabajo :)
				var id_chk = $(this).attr('id');
				id_chk = id_chk.split('_');
				id_usuario = $('#slc_'+id_chk[1]+' option:selected').val();
				tiempo_usuario = ($('#tie_'+id_chk[1]).val());
				
				var tiempos = tiempo_usuario.split(':');
				
				tiempo_usuario = (tiempos[0] * 60) + parseInt(tiempos[1]);
				
				tiempo_convertidos['tie_'+id_chk[1]] = tiempo_usuario;
				
			}
			
		}
		
	});
	
	
	
	$('#ruta_trabajo input[type=checkbox]').each(function()
	{
		if('none' == $('#fecha_entrega').css('display'))
		{
			return;
		}
		
		var id_chk = $(this).attr('id');
		id_chk = id_chk.split('_');
		
		if($(this).attr('checked'))
		{
			puestos = true;//Encontramos un puesto de trabajo :)
			
			if(id_usuario != '' && mandar_tiempo_usuario != '')
			{
				usuarios += "-";
				mandar_tiempo_usuario += "-";
			}
			
			id_usuario = $('#slc_'+id_chk[1]+' option:selected').val();
			tiempo_usuario = ($('#tie_'+id_chk[1]).val());
			
			var tiempos = tiempo_usuario.split(':');
			horas_foto = 0;//Variable de las horas
			
			if("tie_11" == "tie_"+id_chk[1])
			{
				if($('#cotizacion').attr('checked'))
				{
					var productos = '';
					//Los datos para la para cotizacion debe estar llenos y solo incluir numero
					$('#coti_trabajo input[type=checkbox]').each(function()
					{
						if($(this).attr('checked'))
						{
							if(productos != '')
							{
								productos += "-";
							}
							
							var id_chk = $(this).attr('prueba');
							var id_chkp = $(this).attr('id');
							id_chkp = id_chkp.split('_');
							productos += id_chk;
							
							for(b = 0; b < 7; b++)
							{//Bucle para recorrer el vector planchas_v
								if(planchas_v[b] == id_chk)
								{//Si esta posicion del vector coincide con el id_producto
									cantidad_solicitada = $("#canmat_"+id_chkp[1]).val();//Tomo la cantidad de material solicitada de ese producto
									horas_foto += cantidad_solicitada / pulgas;//horas_foto += (cantidad_solicitada * desperdicio) / pulgas por minuto
								}
							}//Fin bucle
						}
					});
					
				}
				
				tiempo_usuario = parseInt(horas_foto+260);
			}
			else
			{
				tiempo_usuario = (tiempos[0] * 60) + parseInt(tiempos[1]);
			}
			
			
			
			tiempo_convertidos['tie_'+id_chk[1]] = tiempo_usuario;
			
			
			
			if(parseInt($('[name=puesto_asignado]:checked').val()) > parseInt(id_chk[1]))
			{
				return;
			}
			
			
			if('hidden' == $('#tie_'+id_chk[1]).attr('type'))
			{//Si es una caja de texto
				tiempo_usuario = 0;
			}
			//Hay que validar
			usuarios += id_usuario;
			mandar_tiempo_usuario += tiempo_usuario;
			
		}
	});
	
	
	var continuar = true;
	
	
	if(confirm('El Pedido ser\xe1 ingresado, desea continuar?'))
	{
		for(var puesto in tiempo_convertidos)
		{
			$('#'+puesto).val(tiempo_convertidos[puesto]);
		}

		$('textarea#observaciones').val($('#ifr-agr_trab_indi_html').contents().find('#agr_trab_indi_html').html());
		
		$('#btn_submit').val('Procesando...');
		$('#btn_submit').attr('disabled', true);
		$('#form_pedido').submit();
	}
	else
	{
		$('#btn_submit').val(submit_label);
	}
	
	
	return continuar;
	
	
}


//Pasar la hora ingresada en el textbox a un formato valido
function validar_hora(caja){
	
	var hora_ingresada = $('#'+caja).val();//Tomo el valor ingresado por el usuario
	
	var caracteres = hora_ingresada.length;//Cuantos caracteres tiene?
	var horas = "";//Guardara las horas
	var minutos = "";//Guardara los minutos
	var cual = 1;//Bandera
	var o = 1;//Me servira para tomar datos con substring, siempre un valor mas que i
	//stringObject.substring(start,stop) <-Asi funciona substring
	for(i = 0; i < caracteres; i++)
	{//Un bucle para recorrer la hora ingresada
		letra = hora_ingresada.substring(i, o);//Tomo la letra correspondiente
		o++;//Aumento la o para la proxima vuelta
		
		if(isNaN(letra))
		{
			cual = 2;//Si no es letra, debe ser : o . y me indica que hasta ahi llegan las horas y vienen los minutos
		}
		else{//Si es un numero debe ser tomado en cuenta
			if(cual == 1)
			{
				horas = horas + '' + letra;//Si la bandera es 1 debe guardar horas
			}
			else
			{
				if(1 < minutos.length)
				{//Si tiene ya dos digitos
					//No puede seguir
					break;
				}
				minutos = minutos + '' + letra;//Si la bandera es 2 debo guardar minutos
			}
		}
	}//Fin bucle
	
	if(horas == "")
	{
		horas = "00";//Si no hay horas, lo pongo a cero
	}
	if(minutos == "")
	{
		minutos = "00";//Si no hay minutos, lo pongo a cero
	}
	
	horas = parseInt(horas);
	
	if(59 < minutos)
	{//Algun chistoso?
		horas++;
		//No tengo tiempo para tonteras
		minutos = '00';
	}
	
	if(minutos.length == 1)
	{
		minutos = minutos + "0";//Si los minutos que resultaron es menor que 10 debo ponerles un cerillo delante "07"
	}
	
	$('#'+caja).val(horas + ":" + minutos);//Asigno la hora resultante al textbox que hizo la peticion
	
}


//Validacion del pedido a agregar
function validar_pedido(Accion)
{
	
	texto_submit = $('#btn_submit').val();
	
	//falta Deshabilitar boton
	
	
	
	//Primero validar que no este vacia la fecha de entrega
	if(
		'' == $('[name=fecha_entrega]').val()
		|| '00-00-0000' == $('[name=fecha_entrega]').val()
	)
	{
		alert('Favor establecer una fecha de entrega.');
		$('[name=fecha_entrega]').focus();
		return false;
	}
	
	
	//ACCIONES VALIDAS SOLAMENTE PARA MODIFICACION DE PEDIDO
	if('Modificar Pedido' == Accion)
	{
		//Validacion de puesto seleccionado como asignado
		var continuar = false;
		$('#ruta_trabajo input[type=radio]').each(function()
		{
			if($(this).attr('checked'))
			{
				//Si hay un radio seleccionado es la primer condicion para continuar
				
				var id_radio = $(this).attr('id');
				id_radio = id_radio.split('_');
				if($('#chk_'+id_radio[1]).attr('checked'))
				{
					continuar = true;
				}
			}
		});
		
		//Si no se especifico un puesto asignado
		if(!continuar)
		{
			alert('No ha seleccionado un puesto al cual asignar este Pedido.');
			$('#ruta_trabajo input[type=radio]:first').focus();
			return false;
		}
	}
	//FIN MODIFICACION DE PEDIDO
	
	
	//Si hay un departamento seleccionado, debe tener hora de realizacion
	//Si es que el departamento lo requiere
	var continuar = true;//Bandera para informar de errores
	var campo = '';//Hacia que caja regresare
	var puestos = false;//Debe existir uno o mas puestos
	$('#ruta_trabajo input[type=checkbox]').each(function()
	{
		if($(this).attr('checked'))
		{
			puestos = true;//Encontramos un puesto de trabajo :)
			var id_chk = $(this).attr('id');
			id_chk = id_chk.split('_');
			
			if('hidden' != $('#tie_'+id_chk[1]).attr('type'))
			{//Si es una caja de texto
				//Hay que validar
				if('00:00' == $('#tie_'+id_chk[1]).val() || '0:00' == $('#tie_'+id_chk[1]).val())
				{
					continuar = false;
					campo = '#tie_'+id_chk[1];
					return false;
				}
			}
		}
	});
	
	//Si no hay puestos de trabajo
	if(!puestos)
	{
		if(!confirm('No hay puestos seleccionados para este pedido.\r\nDesea Continuar?'))
		{
			return false;
		}
	}
	
	//Si alguno puesto no tiene hora asignada
	if(!continuar)
	{
		alert('Favor especificar el tiempo de los puestos agregados a la ruta.');
		$(campo).focus();
		return false;
	}
	
	
	if($('#cotizacion').attr('checked'))
	{
		var productos = false;
		var continuar = true;
		//Los datos para la para cotizacion debe estar llenos y solo incluir numero
		$('#coti_trabajo input[type=checkbox]').each(function()
		{
			if($(this).attr('checked'))
			{
				productos = true;
				var id_chk = $(this).attr('id');
				id_chk = id_chk.split('_');
				
				if(isNaN($("#premat_"+id_chk[1]).val()))
				{
					alert('Favor digitar solamente numeros.');
					$("#premat_"+id_chk[1]).focus();
					continuar = false;
					return false;
				}
				
				if(isNaN($("#canmat_"+id_chk[1]).val()))
				{
					alert('Favor digitar solamente numeros.');
					$("#canmat_"+id_chk[1]).focus();
					continuar = false;
					return false;
				}
			}
		});
		
		
		if(!continuar)
		{
			return false;
		}
		
		/*if(!productos)
		{
			$('#cotizacion').attr('checked', false);
			$('#coti_trabajo input[type=checkbox]').attr('checked', false);
		}*/
		
	}
	
	
	
	calcular_fecha_entrega()
	
}


//Mostrar ocultar cotizacion
function ver_cotizacion()
{
	if($('#cotizacion').attr('checked'))
	{
		$('#coti_trabajo').show();
	}
	else
	{
		$('#coti_trabajo').hide();
	}
}



//Cuanto se cobrara por los materiales a realizar? Aqui la respuesta
function calcular_precio(id_cajas){
	
	precio = $("#premat_"+id_cajas).val();//Precio del material solicitado
	cantidad = $("#canmat_"+id_cajas).val();//Cantidad material solicitado
	
	if(isNaN(precio)){//Si no pusieron solo numeros en la caja
		alert("Debe ingresar solo valores numericos y el punto '.'");//Los reganho
		return false;//No puede procesarse
	}
	
	if(isNaN(cantidad)){//Si no pusieron solo numero en esta caja tampoco
		alert("Debe ingresar solo valores numericos y el punto '.'");//Los vuelvo a reganhar
		return false;//No puede procesarse
	}
	
	var total = (precio * cantidad) * 100;
	total = parseInt(total);
	total = total / 100;
	
	//Aplicacion del total
	$('#totmat_'+id_cajas).empty()
	$("#totmat_"+id_cajas).append('$' + total);
	
	
}


//Busco los procesos por su descripcion, si hay id_cliente cliente lo envio tambien
function buscar_pedido_desc()
{
	
	var producto = $.trim($('#producto').val());
	var cliente = $('#clientes').val();
	$('#productos').empty();
	
	if(2 < producto.length)
	{
		
		if('' != cliente)
		{
			cliente = '/' + cliente;
		}
		
		$.ajax(
		{
			type: "POST",
			url: "/pedidos/buscar/descripcion"+cliente,
			data: "producto="+producto,
			success: function(msg)
			{
				if("" == msg)
				{
					$('#productos').append('<option value="">--</option>');
				}
				else
				{
					var myObject = JSON.parse(msg);
					var total_procesos = myObject['proc'].length;
					
					for(i = 0; i < total_procesos; i++)
					{
						$('#productos').append('<option value="'+myObject['proc'][i][0]+'">'+myObject['proc'][i][1]+'</option>');
					}
				}
				
			},
			error: function(msg)
			{
				alert('Lo sentimos. Ocurri\xf3 un error en la acci\xf3n solicitada.');
				$('#productos').append('<option value="">--</option>');
			}
		});
		
	}
	else
	{
		$('#productos').append('<option value="">--</option>');
	}
	
	
}

function cotizacion_ver()
{//En el detalle de pedido se puede mostrar u ocultar la cotizacion
	if(cotizacion_mostrar)//Si se esta visualizando la cotizacion
		$("#ver_cotizacion").hide();//Oculto la cotizacion
	else//Si la cotizacion esta oculta
		$("#ver_cotizacion").show();//Muestro la cotizacion
	cotizacion_mostrar = !cotizacion_mostrar;//Cambio de estado
	
}


var cambios_consumo = 'cons';
function fin_trabajo(Puesto,Pedido,Peus)
{
	
	if('DESPACHO' == Puesto)
	{
		$('#finalizar_sap').show();
		$('#fs_pedido').focus();
	}
	else
	{
		if('cons' == cambios_consumo)
		{
			poner_consumo();
		}
		if('camb' == cambios_consumo)
		{
			ver_cambios();
		}
	}
	
}



function poner_consumo()
{
	$('#reportar_material').show();
	$('#codigo_material_0').focus();
}



function ver_cambios()
{
	
	$('#tabla_check_observ').empty();
	$('#tabla_check_observ').append($('#obsrv_resaltada').html());
	$('#check_observaciones').show();
	
}



function fin_pedido()
{
	if(confirm('El pedido ser\xe1 finalizado, desea continuar?'))
	{
		//alert($('#ruta_finalizar').val());
		window.location = $('#ruta_finalizar').val();
	}
}


function finalizar_sap(id_pedido)
{
	
	var variables = 'pedido_sap='+$('#fs_pedido').val();
	variables += '&venta='+$('#fs_venta').val();
	
	
	$.ajax(
	{
		type: "POST",
		url: "/pedidos/pedido_sap/modificar/"+id_pedido,
		data: variables,
		success: function(msg)
		{
			if("ok" == msg)
			{
				window.location = $('#ruta_finalizar').val();
			}
			else
			{
				alert('Lo sentimos. Ocurri\xf3 un error en la acci\xf3n solicitada.');
			}
			
		},
		error: function(msg)
		{
			alert('Lo sentimos. Ocurri\xf3 un error en la acci\xf3n solicitada.');
		}
	});
	
}

function reportar_consumo(id_pedido)
{
	
	$('#btn_guar_consu').attr('disabled', true);
	
	
	var variables = '';
	var cantidad_consumo = 0;
	
	for(i = 0; i < 6; i++)
	{
		
		if('--' != $('#id_material_'+i).val() && '' != $('#id_material_'+i).val())
		{
			
			cantidad_consumo = $('#cantidad_material_'+i).val();
			
			if(0 == cantidad_consumo || isNaN(cantidad_consumo))
			{
				alert('Debe especificar una cantidad del material consumido.');
				$('#cantidad_material_'+i).focus();
				return false;
			}
			
			if('' != variables)
			{
				variables += '&';
			}
			variables += 'material_'+i+'='+$('#id_material_'+i).val();
			variables += '&cantidad_'+i+'='+$('#cantidad_material_'+i).val();
			if($('#repro_material_'+i).attr('checked'))
			{
				variables += '&reproceso_'+i+'=on';
			}
			else
			{
				variables += '&reproceso_'+i+'=';
			}
		}
		
	}
	
	if('' == variables)
	{
		alert('No ha reportado consumo para este pedido.');
		$('#codigo_material_0').focus();
		$('#btn_guar_consu').attr('disabled', false);
		return false;
	}
	
	
	$.ajax(
	{
		type: "POST",
		url: "/pedidos/pedido_consumo/reportar/"+id_pedido,
		data: variables,
		success: function(msg)
		{
			if("ok" == msg)
			{
				window.location = $('#ruta_finalizar').val();
			}
			else
			{
				$('#btn_guar_consu').attr('disabled', false);
				alert('Lo sentimos. Ocurri\xf3 un error en la acci\xf3n solicitada.');
			}
			
		},
		error: function(msg)
		{
			$('#btn_guar_consu').attr('disabled', false);
			alert('Lo sentimos. Ocurri\xf3 un error en la acci\xf3n solicitada.');
		}
	});
	
	
}

//Eliminar un pedido especifico
function eliminar_pedido(id_pedido, id_proceso)
{
	//De verdad lo queres eliminar?
	if(confirm("Este Pedido sera Eliminado y Toda la Informacion relacionada a el.\nEliminar Pedido."))
	{
		window.location = "/pedidos/pedido_eliminar/index/"+id_pedido+"/"+id_proceso;
	}
}

//Eliminar un pedido especifico
function revivir_pedido()
{
	id_pedido = $('#id_pedido').val();
	id_proceso = $('#id_proceso').val();
	fecha = $('#nfecha').val();
	//De verdad lo queres eliminar?
	if(confirm("Desea revivir el pedido?."))
	{
		window.location = "/pedidos/pedido_revivir/index/"+id_pedido+"/"+id_proceso+"/"+fecha;
	}
}


//Eliminar un pedido especifico
function retoque(id_pedido)
{
	//De verdad lo queres eliminar?
	if(confirm("Desea enviar el trabajo a Retoque?."))
	{
		window.location = "/pedidos/tiempo/accion/retoque/"+id_pedido;
	}
}


function trabajos(pagina, inicio)
{
	tipo = $('#usuario option:selected').val();
	window.location = '/pedidos/historial_trabajos/index/'+tipo+'/'+pagina+'/'+inicio;
}