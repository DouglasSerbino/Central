//Funciones que sirven para la creacion de los procesos.

var this_grupo = '';

//Da un lapso de tiempo entre una busqueda y otra para comprobar procesos
var espera = true;

//Busco el cliente correspondiente al codigo ingresado
function vercliente(id_cliente)
{
	var codigo_cliente = $("#codigo_cliente").val();
	//Si no han digitado un id_cliente no puedo hacer la busqueda
	if(codigo_cliente == "")
	{
		$("#cliente").val("");
	}
	else
	{
		//Si hay algo escrito.
		//Funcion get de Jquery, envio el codigo del cliente
		$.get("/procesos/proceso_cli/cliente/"+codigo_cliente, function(d){
			//Cambio el value del campo cliente por el resultado de la busqueda
			var info_cliente = d.split('[-]');
			$('#cliente').val(info_cliente[0]);
			$('#atrapa').empty();
			$('#atrapa').append(info_cliente[1]);
			$("#nombre_cliente").val($('#atrapa').text());
			
			if('Agregar Pre-Ingreso' == $('#agr_pre_ingreso').val())
			{
				verifica_proceso();
			}
			buscar_cotizacion();
		});
	}
	
}

//Generador de correlativos para los procesos nuevos
function genera_correlativo()
{
	//Capturo el id del cliente al cual se generara correlativo
	codigo_cliente = $("#codigo_cliente").val();
	
	//Si no han digitado un id_cliente no puedo hacer la busqueda
	if(codigo_cliente == "")
	{
		alert("Debe digitar el codigo del cliente para realizar la busqueda.");
	}
	else
	{//Si hay algo escrito.
		//Funcion get de Jquery, envio el codigo del cliente
		$.get("/procesos/proceso_cli/generar_correlativo/"+codigo_cliente, function(d)
		{
			proceso_ajax = d;
			if(proceso_ajax.length == 1)
			{
				proceso_ajax = "000"+proceso_ajax;
			}
			if(proceso_ajax.length == 2)
			{
				proceso_ajax = "00"+proceso_ajax;
			}
			if(proceso_ajax.length == 3)
			{
				proceso_ajax = "0"+proceso_ajax;
			}
			//Cambio el value del campo cliente por el resultado de la busqueda.
			$("#proceso").val(proceso_ajax);
			
			if('Agregar Pre-Ingreso' == $('#agr_pre_ingreso').val())
			{
				verifica_proceso();
			}
		});
	}

}
function listado_id_cliente()
{//Direccionar la pagina al listado de proceso del cliente seleccionado
	window.location = "/procesos/listado_procesos/index/" + $("#id_cliente").val();
}

function verifica_proceso()
{
	if(!espera)
	{
		return false;
	}
	
	espera = false;
	var proceso = $.trim($("#proceso").val());
	$("#proceso").val(proceso);
	$("#nuevo_proc").hide();
	$("#proceso_proc").hide();
	$("#producto").val("");
	if('brasal' != this_grupo)
	{
		$("#agr_pre_ingreso").attr("disabled", true);
		$("#producto").attr("disabled", true);
	}
	
	if($("#proceso").val() != "" && $("#cliente").val() != "")
	{
		$.ajax({
			type: "POST",
			url: "/ventas/preingreso/validar_proceso",
			data: 'proceso='+$("#proceso").val()+'&cliente='+$("#cliente").val(),
			success: function(d)
			{
				if("nuevo" == d)
				{
					$("#nuevo_proc").show();
					if('Agregar Pre-Ingreso' == $("#agr_pre_ingreso").val())
					{
						//$("#producto").attr("disabled", false);
					}
					$("#producto").focus();
					if('brasal' != this_grupo)
					{
						$("#agr_pre_ingreso").attr("disabled", false);
						$("#producto").attr("disabled", false);
					}
				}
				else
				{
					var info = d.split('[an]');
					if(2 != info.length)
					{
						alert('Lo sentimos. Ocurri\xf3 un error en la acci\xf3n solicitada.');
					}
					else
					{
						$("#producto").val(info[0]);
						
						if('procesando' == info[1])
						{
							$("#proceso_proc").show();
						}
						else
						{
							if('brasal' != this_grupo)
							{
								$("#agr_pre_ingreso").attr("disabled", false);
								$("#producto").attr("disabled", true);
							}
							
							if('Agregar Pre-Ingreso' == $("#agr_pre_ingreso").val())
							{
								//if(confirm('Desea copiar la informacion de la planificacion anterior para este trabajo?')){
									copiar_especificaciones($('#codigo_cliente').val(), $('#proceso').val());
								//}
							}
						}
					}
					
				}
				
				//buscar_cotizacion();
				
				setTimeout("espera_true()",500);
			},
			error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
		});
		
	}
	else
	{
		espera = true;
	}
	
}

function espera_true()
{
	espera = true;
}

function copiar_especificaciones(cliente, proceso)
{
	
	$('#lista_especificaciones input[type=checkbox]').attr('checked', false);
	$('#lista_especificaciones input[type=text]').val('');
	
	
	
	$.ajax({
			type: "POST",
			url: "/pedidos/especificacion/informacion",
			data: 'proceso='+proceso+'&cliente='+cliente,
			success: function(d)
			{
				var especs = JSON.parse(d);
				
				$('#iti_'+especs['general']['id_tipo_impresion']).attr('checked', true);
				$('#embobinado_cara').val(especs['general']['embobinado_cara']);
				$('#embobinado_dorso').val(especs['general']['embobinado_dorso']);
				$('#unidad_medida option:[value='+especs['general']['unidad_medida']+']').attr('selected', 'selected')
				$('#alto_arte').val(especs['general']['alto_arte']);
				$('#ancho_arte').val(especs['general']['ancho_arte']);
				$('#ancho_fotocelda').val(especs['general']['ancho_fotocelda']);
				$('#alto_fotocelda').val(especs['general']['alto_fotocelda']);
				$('#color_fotocelda').val(especs['general']['color_fotocelda']);
				$('#lado_impresion').val(especs['general']['lado_impresion']);
				//$('#emulsion_negativo_'+especs['general']['emulsion_negativo']).attr('checked', true);
				$('#codb_tipo').val(especs['general']['codb_tipo']);
				$('#codb_num').val(especs['general']['codb_num']);
				$('#codb_magni').val(especs['general']['codb_magni']);
				$('#codb_posicion').val(especs['general']['codb_posicion']);
				$('#codb_bwr').val(especs['general']['codb_bwr']);
				$('#repet_ancho').val(especs['general']['repet_ancho']);
				$('#repet_alto').val(especs['general']['repet_alto']);
				$('#separ_ancho').val(especs['general']['separ_ancho']);
				$('#separ_alto').val(especs['general']['separ_alto']);
				
				
				for(var color in especs['colores'])
				{
					$('#color_'+color).val(especs['colores'][color]['color']);
					if('on' == especs['colores'][color]['solicitado'])
					{
						$('#solicitado_'+color).attr('checked', true);
					}
					if('on' == especs['colores'][color]['tono'])
					{
						$('#tono_'+color).attr('checked', true);
					}
					if('on' == especs['colores'][color]['linea'])
					{
						$('#linea_'+color).attr('checked', true);
					}
					$('#angulo_'+color).val(especs['colores'][color]['angulo']);
					$('#lineaje_'+color).val(especs['colores'][color]['lineaje']);
					$('#anilox_'+color).val(especs['colores'][color]['anilox']);
					$('#bcm_'+color).val(especs['colores'][color]['bcm']);
					$('#sticky_'+color).val(especs['colores'][color]['sticky']);
				}
				
				//Pintamiento de cajas
				for(i = 1; i <= 10; i++)
				{
					pintar_caja(i);
				}
				
				$('#radio').val(especs['distorsion']['radio']);
				$('#polimero option:[value="'+especs['distorsion']['polimero']+'"]').attr('selected', 'selected')
				$('#stickyback option:[value="'+especs['distorsion']['stickyback']+'"]').attr('selected', 'selected')
				$('#k').val(especs['distorsion']['k']);
				$('#pb').val(especs['distorsion']['pb']);
				$('#pa').val(especs['distorsion']['pa']);
				$('#dp').val(especs['distorsion']['dp']);
				$('#dn').val(especs['distorsion']['dn']);
			},
			error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
		});
	
	
	
	
	
	
}

function buscar_cotizacion()
{
	
	$('#div_cotizaciones').empty();
	
	$.get("/productos/listar_productos/index/"+$("#cliente").val(), function(d){
		
		var productos = JSON.parse(d);
		
		var nueva_tabla = '<table id="coti_trabajo" class="tbordes selecciones">';
		nueva_tabla += '<tr><th>Material</th><th style="width:120px;">Precio</th><th style="width:80px;">Cantidad</th><th style="width:100px;">Total</th></tr>';
		
		for(var producto in productos['prod'])
		{
			
			nueva_tabla += '<tr id="mat_'+productos['prod'][producto]['idc']+'">';
			nueva_tabla += '<td>';
			nueva_tabla += '<input type="checkbox" name="chmat_'+productos['prod'][producto]['idc']+'" id="chmat_'+productos['prod'][producto]['idc']+'" onclick="poner_color_fila(\'chmat_'+productos['prod'][producto]['idc']+'\',\'mat_'+productos['prod'][producto]['idc']+'\')" />';
			nueva_tabla += '<label for="chmat_'+productos['prod'][producto]['idc']+'">'+productos['prod'][producto]['pro']+'</label>';
			nueva_tabla += '</td>';
			nueva_tabla += '<td>';
			nueva_tabla += '<strong>$</strong>';
			nueva_tabla += '<input type="text" size="4" name="premat_'+productos['prod'][producto]['idc']+'" id="premat_'+productos['prod'][producto]['idc']+'" value="'+productos['prod'][producto]['pre']+'" onblur="calcular_precio(\''+productos['prod'][producto]['idc']+'\')" /></td>';
			nueva_tabla += '<td><input type="text" size="4" name="canmat_'+productos['prod'][producto]['idc']+'" id="canmat_'+productos['prod'][producto]['idc']+'" value="'+productos['prod'][producto]['can']+'" onblur="calcular_precio(\''+productos['prod'][producto]['idc']+'\')" /></td>';
			nueva_tabla += '<td id="totmat_'+productos['prod'][producto]['idc']+'">$'+productos['prod'][producto]['pre']+'</td>';
			nueva_tabla += '</tr>';
			
		}
		nueva_tabla += '</table>';
		
		$('#div_cotizaciones').append(nueva_tabla);
		
	});
	
}

function guardar_preingreso()
{
	var cliente = $('#codigo_cliente').val();
	
	
	if('' == $('#fecha_entrega').val())
	{
		
		alert('Por favor especifique la fecha solicitada.');
		return false;
		
	}
	
	var producto = $.trim($('#producto').val());
	$('#producto').val(producto);
	
	if('' == $('#producto').val())
	{
		
		alert('No ha digitado un nombre para el Proceso. Favor asigne un nombre antes de poder continuar.');
		return false;
		
	}
	
	
	var continuar = false;
	//Los datos para la para cotizacion debe estar llenos y solo incluir numero
	$('#coti_trabajo input[type=checkbox]').each(function()
	{
		if($(this).attr('checked'))
		{
			continuar = true;
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
		if(!confirm('No ha seleccionado items de la cotizaci\xf3n.\r\nDesea continuar?'))
		{
			return false;
		}
	}
	
	if(confirm('El preingreso ser\xe1 agregado. Desea continuar?'))
	{
		$('#form_espec_repro').submit();
	}
}