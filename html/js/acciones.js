
$(document).ready(function()
{
	
	if('credito' == $('#credito').attr('class'))
	{
		pie_pagina();
		setInterval(pie_pagina, 150000);
	}
	
	
	$('#menu-lateral>li').on('click', function()
	{
		if('' == $(this).attr('class'))
		{
			$(this).attr('class', 'activo');
			$(this).children('ul').show('blind');
		}
		else
		{
			$(this).attr('class', '');
			$(this).children('ul').hide('blind');
		}
	});
	
});


function pie_pagina()
{
	
	$.get('/pie', function(d)
	{
		var datos = d.split(',');
		
		//alert(datos[0]);
		$('#pie_rech strong').empty();
		var valor = 0;
		if(!isNaN(datos[0]))
		{
			valor = datos[0];
		}
		$('#pie_rech strong').append(parseInt(valor));
		
		if(undefined != datos[1])
		{
			var nombres = new Array('', 'pie_curi', 'pie_vent', 'pie_plan', 'pie_tare');
			for(i = 1; i <= 4; i++)
			{
				valor = 0;
				if(!isNaN(datos[i]))
				{
					valor = datos[i];
				}
				$('#'+nombres[i]+' strong').empty();
				$('#'+nombres[i]+' strong').append(parseInt(valor));
			}
		}
		
	})
	
}


$(function()
{
	
	$('#cambia_grupo_ipsofacto').bind('change', function()
	{
		$.get('/grupo/modificar/cambia_grupo/'+$(this).val(), function(d)
		{
			if('ok' != d)
			{
				alert('Ocurri\xf3 un error en la acci\xf3 solicitada.');
			}
			else
			{
				window.location.reload();
			}
		});
	});
	
	$('#chk_todos').click(function()
	{
		if($(this).attr('checked'))
		{
			$('#rech_a').hide();
			$('#rech_a_todos').show();
		}
		else
		{
			$('#rech_a').show();
			$('#rech_a_todos').hide();
		}
	});
	
	$('[name=id_tipo_impresion]').bind('change', function(){
		poner_angulos('');
	});
	
	$('#lineaje_1').blur(function(){
		for(i = 2; i <= 10; i++)//Recorro las casillas de angulos
		{
			$("#lineaje_"+i).val($(this).val());//Les aplico el lineaje debido
		}
	});
	
});




/**
 *Funcion que realiza validaciones a los formularios a ingresar.
*/
function validar(formulario)
{
	
	var valor = '';//Valor que tomara por cada elemento
	var campo = '';//Si un campo no cumple su condicion, se guarda cual es
	var continuar = true;//Puedo continuar?
	
	//Reviso todos los campos que son requeridos
	$('#'+formulario+' .requ').each(function()
	{
		
		if($(this).attr('disabled'))
		{
			return;
		}
		
		//Asigno el valor de este campo y le elimino los espacios al inicio y final
		valor = $.trim($(this).val());
		//Asigno el valor sin espacios al campo
		$(this).val(valor);
		
		//Si este campo requerido esta vacio
		if('' == valor)
		{
			//Guardo el nombre del campo
			campo = $(this).attr('name');
			//Cambio el valor de continuar para que se detenga la ejecucion del script
			continuar = false;
			//Detengo el bucle
			return false;
		}
	});
	
	//Si no se puede continuar
	if(!continuar)
	{
		//Hago saber al usuario
		alert('Favor completar los campos marcados como requeridos.');
		//Indico cual es el campo
		$('[name='+campo+']').focus();
		//Detengo la ejecucion del script
		return false;
	}
	
	
	var no_raros = /^[a-zA-Z0-9]+$/;
	//Algun dia voy a entender a perfeccion las expresiones regulares
	
	
	$('#'+formulario+' .no_raros').each(function()
	{
		if(no_raros.test($(this).val())==false)
		{
			continuar = false;
		}
	});
	
	if(!continuar)
	{
		alert('El c\xf3digo del Cliente solo debe contener letras y numeros.');
		return false;
	}
	
	
	//Reviso todos los campos que deben ser numericos
	$('#'+formulario+' .num').each(function()
	{
		
		if($(this).attr('disabled'))
		{
			return;
		}
		
		//Asigno el valor de este campo y le elimino los espacios al inicio y final
		valor = $.trim($(this).val());
		//Asigno el valor sin espacios al campo
		$(this).val(valor);
		
		//Si este campo posee un valor no numerico
		if(isNaN(valor))
		{
			//Guardo el nombre del campo
			campo = $(this).attr('name');
			//Cambio el valor de continuar para que se detenga la ejecucion del script
			continuar = false;
			//Detengo el bucle
			return false;
		}
	});
	
	//Si no se puede continuar
	if(!continuar)
	{
		//Hago saber al usuario
		alert('Este campo acepta solamente numeros.');
		//Indico cual es el campo
		$('[name='+campo+']').focus();
		//Detengo la ejecucion del script
		return false;
	}
	
	
	if(!confirm('La informaci\xf3n ser\xe1 guardada en el sistema.\r\nDesea continuar?'))
	{
		return false;
	}
	
	//Si todo sale bien, se permite enviar el formulario
	return true;
	
}


function rechazar(pedido, peus)
{
	
	$.get("/pedidos/ruta/rechazo/"+pedido+"/"+peus, function(d){
		
		$('#rech_pedido').val(pedido);
		$('#rech_peus').val(peus);
		
		var rutas = JSON.parse(d);
		
		$('#rech_a').empty();
		$('#rech_asignar').empty();
		
		$('#rechazar').show();
		$('#rechazar').css('top', (window.pageYOffset + 25));
		
		var puestos = '';
		var total = rutas['rut'].length;
		total--;
		
		
		
		for(var puesto in rutas['rut'])
		{
			
			seleccionado = '';
			
			if(puesto == total)
			{
				seleccionado = ' selected="selected"'
			}
			
			rech_asignar = '<option value="'+rutas['rut'][puesto]['ip']+'-'+rutas['rut'][puesto]['iu']+'"'+seleccionado+'>'+rutas['rut'][puesto]['us']+'</option>';
			rech_a = '<option value="'+rutas['rut'][puesto]['ip']+'-'+rutas['rut'][puesto]['us']+'-'+rutas['rut'][puesto]['iu']+'"'+seleccionado+'>'+rutas['rut'][puesto]['us']+'</option>';
			$('#rech_asignar').append(rech_asignar);
			$('#rech_a').append(rech_a);
			
		}
		
		//$('#rech_a').append(puestos);
		
	});
}

function rechazar_trabajo()
{
	
	var seleccionado = false;
	$('#rechazar :checkbox').each(function()
	{
		if($(this).attr('checked'))
		{
			seleccionado = true;
		}
	});
	
	if(!seleccionado)
	{
		alert('Favor seleccione una o m\xe1s de las opciones de rechazo.');
		return false;
	}
	
	$('#rechazar form').submit();
	
}

function ver_agregar_scan(pedido)
{
	
	$('#form_scan_archivos').empty();
	$('#scan_pedido').val(pedido);
	$('#form_scan').show();
	$('#form_scan input:first').focus();
	var tipo_adjunto = pedido.split('-'); 

	if(tipo_adjunto[1] == 'imagen_proceso')
	{
		$('#form_scan').css({'top':(170 + window.pageYOffset) + 'px'});
	}

	
	if(tipo_adjunto[1] != 'imagen_proceso')
	{
		$.get('/scan/listar_scan/archivos/'+pedido, function(d)
		{
			
			var archivos = JSON.parse(d);
			var a=0;
			for(var archivo in archivos['a'])
			{
				//alert(archivos['a'][archivo]['t']);
				
				var nuevo_archivo = '<div class="adjunto">';
				
				if(archivos['a'][archivo]['t'] == 'imagen')
				{
					nuevo_archivo += '<a href="'+archivos['a'][archivo]['url']+'" class="thickbox" title="">';
					nuevo_archivo += '<img width="100px" height="80px" src='+archivos['a'][archivo]['url']+' /></a>';
					nuevo_archivo += '<a href="/scan/obtener_scan/archivo/'+archivos['a'][archivo]['i']+'">';
					nuevo_archivo += archivos['a'][archivo]['n'];
					nuevo_archivo += '</a>';
				}
				else
				{
					nuevo_archivo += '<a href="/scan/obtener_scan/archivo/'+archivos['a'][archivo]['i']+'">';
					nuevo_archivo += '<span class="tipo_archivo ta'+archivos['a'][archivo]['t']+'"></span>';
					nuevo_archivo += '<br />'+archivos['a'][archivo]['n'];
					nuevo_archivo += '</a>';
				}
				//nuevo_archivo += '<br /><img src='+archivos['a'][archivo]['url'];
				//nuevo_archivo += ' />';
				nuevo_archivo += '</div>';
				a++
				$('#form_scan_archivos').append(nuevo_archivo);
			}
			
			
				
				tb_init('a.thickbox, area.thickbox, input.thickbox');//pass where to apply thickbox
				imgLoader = new Image();// preload image
				imgLoader.src = tb_pathToImage;
		});
	}
	
}

//Ocultar ventana de cargar scan
function ocultar_scan()
{
	$('#form_scan').hide();
};

function cambio_scan(adjunto)
{
	
	if(!(/MSIE (\d+\.\d+);/.test(navigator.userAgent)))
	{
		$('#span_'+adjunto).empty();
		$('#span_'+adjunto).append(document.getElementById(adjunto).files[0].name);
	}
	else
	{
		alert("La opci\xf3n de adjuntar archivos no est\xe1 disponible para internet explorer.\r\nLe animamos a utilizar un navegador moderno.");
	}
	
}

function cargar_scan(numero)
{
	//El mundo seria un lugar mejor si no existiese Internet Explorer, o si sus
	//programadores se pusieran al dia con todas las tecnologias :(
	if(!(/MSIE (\d+\.\d+);/.test(navigator.userAgent)))
	{
		
		for(i = 0; i < numero; i++)
		{
			if(!document.getElementById('archivo_'+i).files[0])
			{
				continue;
			}
			
			var archivo = document.getElementById('archivo_'+i).files[0];
			
			if(2097000 < archivo.size)
			{
				alert('El archivo "' + archivo.name + '" es muy pesado, favor reducir su tama\xf1o para proceder.\r\nLos archivo deben tener un tama\xf1o inferior a 2MB');
				return false;
			}
		}
		
		$('#formscan').submit();
		
	}
	else
	{
		alert("La opci\xf3n de adjuntar archivos no est\xe1 disponible para internet explorer.\r\nLe animamos a utilizar un navegador moderno.");
	}
}

function ventana_externa(enlace)
{
	window.open(enlace);
}

//Datos establecidos manualmente tomando como base los id de la tabla tipo_impresion
var angulos_offset = {"CYAN":"15","MAGENTA":"45","AMARILLO":"90","NEGRO":"75"};
var angulos_color = {
	"1" : angulos_offset,
	"2" : {"CYAN":"165","MAGENTA":"105","AMARILLO":"0","NEGRO":"45"},
	"3" : angulos_offset,
	"4" : angulos_offset
};

function poner_angulos(id_casilla)
{
	
	if('' == id_casilla)
	{
		for(i = 1; i <= 10; i++){//Recorro las diez casillas de color
			if(undefined != $("#color_"+i).val())
			{
				casilla_angulo($("#color_"+i).val(), i);
			}
		}//Fin bucle
	}
	else
	{
		casilla_angulo($("#color_"+id_casilla).val(), id_casilla);
		
	}
	
}

function casilla_angulo(color, id_casilla)
{
	
	color = color.toUpperCase();
	$('#color_'+id_casilla).val(color);
	
	if(angulos_color[$('[name=id_tipo_impresion]:checked').val()][color])
	{
		$('#angulo_'+id_casilla).val(angulos_color[$('[name=id_tipo_impresion]:checked').val()][color]);
	}
	else
	{
		//$('#angulo_'+id_casilla).val('');
	}
	
}

function pintar_caja(id_cheque){//Colorea de amarillo el color seleccionado
	if($("#solicitado_"+id_cheque).attr("checked"))//Si el cheque que invoca la funcion esta checkeado
	{
		$("#color_"+id_cheque).addClass("caja_amarilla");//Le pinto de amarillo
	}
	else//Si no esta chequeado
	{
		$("#color_"+id_cheque).removeClass("caja_amarilla")//Lo descoloreo
	}
}

function calcular_distorsion(){//Calcula la distorsión flexografíca.
	
	radio = parseFloat($("#radio").val());
	polimero = parseFloat($("#polimero").val());
	adhesivo = parseFloat($("#stickyback").val());
	
	k = {
		"0.045":6.3648,
		"0.067":9.8947833,
		"0.09":13.565428,
		"0.1":15.161361,
		"0.107":16.278514,
		"0.112":17.07648,
		"0.155":23.938992,
		"0.25":39.100353
	};
	$("#k").val(k[polimero]);
	
	//Lo siento no puedo explicar este codigo porque lo tome del sistema anterior
	if($("#pa").val() > 0 && adhesivo > 0 && polimero > 0)
	{
		radio = (($("#pa").val() / Math.PI) - (adhesivo * 25.4 * 2) - (polimero * 25.4 * 2)) / 2;
		pa = (radio + (polimero * 25.4) + (adhesivo * 25.4)) * 6.2832;
		dp = (k[polimero] / pa) * 100 - 100;
		$("#radio").val(redondear(radio, 4));
		$("#pb").val(redondear(radio * (Math.PI * 2), 3));
		$("#pa").val(redondear(pa, 4));
		//if(268 != IClie)
		//{
			$("#dp").val(redondear(Math.abs(dp), 3));
		//}
		$("#dn").val(redondear(dp + 100, 3));
	}
	
}

function redondear(num,decimal){//Su nombre lo dice todo
 return Math.round(num * Math.pow(10, decimal)) / Math.pow(10, decimal); 
}

function limpiar(){//Limpiar cajas de distorsion
	$("#polimero").attr({"selectedIndex": 0});
	$("#stickyback").attr({"selectedIndex": "0"});
	$("#radio").val("");
	$("#k").val("");
	$("#pb").val("");
	$("#pa").val("");
	$("#dp").val("");
	$("#dn").val("");
}


function formatNumber(num, prefix)
{
	//num = Math.floor(num * 100) / 100;
	//num = num.toFixed(2);
	num = Math.floor(num);
	prefix = prefix || '';
	num += '';
	var splitStr = num.split('.');
	var splitLeft = splitStr[0];
	var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '';
	var regx = /(\d+)(\d{3})/;
	while(regx.test(splitLeft))
	{
		splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
	}
	return convertido = prefix + splitLeft + splitRight;
}









(function($)
{
	$.fn.gbarras = function(opciones)
	{
		
		//Inicializar opciones en caso que no existan
		if(undefined == opciones)
		{
			opciones = {};
		}
		if(undefined == opciones.ymaximo || isNaN(opciones.ymaximo))
		{
			opciones.ymaximo = 100;
		}
		if(0 == opciones.ymaximo)
		{
			opciones.ymaximo = 1;
		}
		if(undefined == opciones.barras)
		{
			opciones.barras = [0];
		}
		if(undefined == opciones.xleyenda)
		{
			opciones.xleyenda = [''];
		}
		if(undefined == opciones.series)
		{
			opciones.series = [''];
		}
		
		//Total de series
		var series = opciones.series.length;
		
		//Se agrega la clase que inicializa el estilo
		this.addClass('estilo_grafico');
		
		//Se agrega las leyendas para el eje Y
		this.append('<div class="eje_y"></div>');
		var yseccion = opciones.ymaximo / 6;
		var yvalor = opciones.ymaximo;
		for(i = 1; i <= 7; i++)
		{
			this.children('.eje_y').append('<div>'+formatNumber(yvalor,'')+'</div>');
			yvalor = yvalor - yseccion;
			if(0 > yvalor)
			{
				yvalor = 0;
			}
		}
		
		//Area donde se presentan las barras
		this.append('<div class="cuerpo_grafico"><div></div><div></div><div></div><div></div><div></div><div></div></div>');
		
		//Presentacion de las leyendas del ejeX
		this.append('<div class="eje_x"></div>');
		var barras = opciones.xleyenda.length;
		var ancho_info_x = parseInt((830 / barras) * 100) / 100;
		for(i = 0; i < barras; i++)
		{
			this.children('.eje_x').append('<div style="width:'+ancho_info_x+'px;">'+opciones.xleyenda[i]+'</div>');
		}
		
		//Presentacion de las barras
		if(1 == series)
		{
			var ancho_info_x = parseInt((830 / barras) * 100) / 100;
			ancho_info_x = ancho_info_x - 4;
		}
		else
		{
			var ancho_info_x = 830 / barras;
			ancho_info_x = parseInt((ancho_info_x - (series)) / series * 100) / 100;
		}
		var der_inicio = 85;
		var alto_barra = 0;
		for(i = 0; i < barras; i++)
		{
			if(1 == series)
			{
				
				alto_barra = (opciones.barras[i] * 300) / opciones.ymaximo;
				this.append('<div class="barra barra4 opaco" valor="'+formatNumber(opciones.barras[i])+'" style="left:'+der_inicio+'px;height:'+alto_barra+'px;width:'+ancho_info_x+'px;">'+formatNumber(opciones.barras[i])+'</div>');
				der_inicio = der_inicio + 4 + ancho_info_x;
				
			}
			if(1 < series)
			{
				
				for(o = 0; o < series; o++)
				{
					//alert(opciones.series[i]);
					alto_barra = (opciones.barras[i][o] * 300) / opciones.ymaximo;
					this.append('<div class="barra barra'+o+' opaco" valor="'+formatNumber(opciones.barras[i][o])+' <br> '+opciones.series[o]+'" style="left:'+der_inicio+'px;height:'+alto_barra+'px;width:'+ancho_info_x+'px;"></div>');
					der_inicio = der_inicio + ancho_info_x;
				}
				der_inicio = der_inicio + 1 + series;
				
			}
		}
		
		
		//Leyendas
		if(1 < series)
		{
			this.append('<div class="leyendas"></div>');
			for(i in opciones.series)
			{
				this.children('.leyendas').append('<span class="barra'+i+' opaco"></span> '+opciones.series[i]);
			}
		}
		
		//Muestra la cantidad que representa cada barra al pasar el mouse
		this.children('.barra').mouseenter(function(e)
		{
			$(this).css({'filter': 'alpha(opacity=100)', '-moz-opacity': '1','opacity': '1'});
			var alto = e.pageY - 60;
			var izquierda = e.pageX;
			$('#gbarras_tool').html($(this).attr('valor'));
			$('#gbarras_tool').show();
			$('#gbarras_tool').css({'top':alto+'px','left':izquierda+'px'});
		}
		).mousemove(function(e)
		{
			var alto = e.pageY - 60;
			var izquierda = e.pageX;
			$('#gbarras_tool').css({'top':alto+'px','left':izquierda+'px'});
		}
		).mouseleave(function()
		{
			$(this).css({'filter': 'alpha(opacity=80)', '-moz-opacity': '0.80','opacity': '0.80'});
			$('#gbarras_tool').hide();
		});
		
		$('body').append('<div id="gbarras_tool">aaaaa</div>');
		
		
	};
})(jQuery);


