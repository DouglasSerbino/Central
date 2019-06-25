function montar_sortables(){
	$(".trab_sortable").sortable({ items: 'li:not(.no_sortable)', connectWith: 'ul' }).disableSelection();
	var $tabs = $('#tabs').tabs();
	var $tab_items = $("ul:first li",$tabs).droppable({
		accept: "ul li",
		hoverClass: "ui-state-hover",
		drop: function(ev, ui) {
			var $item = $(this);
			var $list = $($item.find('a').attr('href')).find('.connectedSortable');
			
			ui.draggable.hide('slow', function() {
				$tabs.tabs('select', $tab_items.index($item));
				$(this).appendTo($list).show('slow');
			});
		}
	});
	
	$('#tablero_equipo').show();
	$('#tab-procesando').hide();
}

function cambiar_equipo(){
	window.location = '/carga/equipo/index='+$('#agregados').attr('checked')+'/'+$('#puesto').val();
}

function guarda_distribucion(departamento)
{
	
	var Pedidos = {'usu':{}};
	var Id_Usuario = '';
	var Id_Pedido = '';
	
	$('#tabs-'+departamento+' ul').each(function()
	{
		
		if('' != $(this).attr('id'))
		{
			Id_Usuario = $(this).attr('id');
			Id_Usuario = Id_Usuario.split('-');
			
			Pedidos['usu'][Id_Usuario[1]] = new Array();
			
			$('#sortable-'+Id_Usuario[1]+' li').each(function()
			{
				
				if(undefined != $(this).attr('id'))
				{
					Id_Pedido = $(this).attr('id');
					Id_Pedido = Id_Pedido.split('-');
					Pedidos['usu'][Id_Usuario[1]].push(Id_Pedido[1]);
				}
				
			});
			
		}
		
	});
	
	
	
	var Datos = 'datos='+JSON.stringify(Pedidos);
	
	$.ajax({
		type: "POST",
		url: "/carga/departamento/asignar",
		data: Datos,
		success: function(msg)
		{
			if(msg == "ok")
			{
				alert("Cambios realizados con Exito.");
				window.location.reload();
			}
			else alert("Ocurrio un error: "+msg+".<br />Haga una captura de pantalla para realizar una verificacion del problema.");
		},
		error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
	});
	
	
}

function ver_cambiar_fecha(pedido, proceso, fecha)
{
	$('#id_pedido').val(pedido);
	$('#correlativo').empty();
	$('#correlativo').append(proceso);
	$('#fecha_entrega').val(fecha);
	$('#fecha_anterior').val(fecha)
	
	$('#fecha-trabajo').css('top', (window.pageYOffset + 10));
	$('#fecha-trabajo').show();
	
}

function cambiar_fecha()
{
	
	var datos = 'fecha_entrega='+$('#fecha_entrega').val();
	datos += '&quien_solicita='+$.trim($('#quien_solicita').val());
	datos += '&justifica_fecha='+$.trim($('#justifica_fecha').val());
	datos += '&fecha_anterior='+$.trim($('#fecha_anterior').val());
	
	$.ajax({
		type: "POST",
		url: "/pedidos/tiempo/accion/fecha/"+$('#id_pedido').val(),
		data: datos,
		success: function(msg)
		{
			if(msg == "ok")
			{
				alert('Fecha cambiada con exito.');
				$('#fecha-trabajo').hide();
			}
			else alert("Ocurrio un error: "+msg+".<br />Haga una captura de pantalla para realizar una verificacion del problema.");
		},
		error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
	});
	
}

function finalizar_trabajo(pedido)
{
	
	if(confirm('El pedido ser\xe1 finalizado.\r\nDesea continuar?'))
	{
		
		$.get('/pedidos/tiempo/accion/terminar/'+pedido, function(d){
			if('ok' == d)
			{
				alert('El trabajo fue finalizado con \xe9xito.');
				$('#tr_ca_'+pedido).hide();
			}
		});
		
	}
}

function asignar_grupos(id_usuario)
{//Direccionar la pagina para asignar los usuarios a los grupos
	window.location = "/usuarios/modificar/asignacion_grupos/" + $("#grupo_asig").val() + "/" + id_usuario;
}


function tiempo_asignado(id_peus, ttiempou, id_usuario)
{
	
	tiempo = prompt("Digite el nuevo tiempo que durara el trabajo:\r\nFormato hh:mm");
	puedo_seguir = true;
	
	if(!tiempo)
		puedo_seguir = false;
	
	if(tiempo == "")
	{
		puedo_seguir = false;
		alert("Debe ingresar el nuevo tiempo.");
	}
	
	if(puedo_seguir)
	{
		caracteres = tiempo.length;
		horas = "";
		minutos = "";
		cual = 1;
		o = 1;
		
		for(i = 0; i < caracteres; i++)
		{
			letra = tiempo.substring(i, o);
			o++;
			
			if(isNaN(letra))
			{
				cual = 2;
			}
			else
			{
				if(cual == 1)
				{
					horas = horas + letra;
				}
				else
				{
					minutos = minutos + letra;
				}
			}
		}
		
		if(horas == "")
		{
			horas = "00";
		}
		if(minutos == "")
		{
			minutos = "00";
		}
		if(minutos.length == 1)
		{
			minutos = minutos + "0";
		}
		
		$.ajax({
		type: "POST",
		url: "/pedidos/tiempo/cambiar_tiempo",
		data: "id_peus="+id_peus+'&horas='+horas+"&minutos="+minutos+"&id_usuario="+id_usuario,
		success: function(msg)
		{
			if(msg != '')
			{
				horas = msg.split('-');
				$('#tp-'+id_peus).empty();
				$('#tp-'+id_peus).append('[' + horas[0] + ' h]');
				$('#tu-'+id_peus).empty();
				$('#tu-'+id_peus).append('[' + horas[1] + ' h]');
				
			}
			else alert("Ocurrio un error: "+msg+".<br />Haga una captura de pantalla para realizar una verificacion del problema.");
		},
		error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
	});
	}
}

function atras_adelante_carga(id_span)
{
	span_v = id_span.split("-");
	valor = span_v[1];
	$("#puesto option[value="+valor+"]").attr("selected",true); 
	$('#formseguimiento').submit();
}