<script type="text/javascript" src="/html/js/venta.js?n=1"></script>
<script type="text/javascript" src="/html/js/jquery.flot.js"></script>
<script type="text/javascript" src="/html/js/jquery.flot.pie.js"></script>
<style>
	h2{
		font-size: 16px;
		font-weight: bold;
	}
	#contiene_todo{
		width: 930px;
		position: absolute;
	}
	.datos_infografia{
		top: 0px;
		left: 0px;
		width: 100%;
		display: none;
		position: absolute;
	}
	.datos_infografia div{
		margin: 5px;
		width: 205px;
		cursor: pointer;
		padding: 10px 5px;
		text-align: center;
		border-radius: 5px;
		background: #f9f9f9;
		display: inline-block;
		border: 2px solid #979797;
	}
	.grande{
		font-size: 25px;
	}
	blockquote{
		margin-top: 10px;
		border-top: 1px solid #8f8f8f;
	}
	.resu_lateral, .resu_informacion{
		top: 0px;
		width: 100%;
		padding: 6px;
		display: none;
		background: #fff;
		position: absolute;
	}
	.resu_lateral{
		left: 0px;
		width: 130px;
	}
	.resu_lateral span{
		cursor: pointer;
	}
	.resu_lateral span:hover{
		text-decoration: underline;
	}
	.resu_informacion{
		right: -2px;
		width: 782px;
	}
	#resu_cargando{
		z-index: 99;
		/*display: none;*/
		color: #937A27;
		font-weight: bold;
		position: absolute;
	}

</style>

<?php
$Info = array();
$Num_extras = array();
$Num_extras_comp = array();
$Num_repro = array();
$Num_repro_comp = array();
$Num_ventas = array();
$Num_ventas_comp = array();

foreach($Informacion_reportes as $aaa => $Datos)
{
	$Num_extras[$aaa] = $Datos['extras'];
	$Num_repro[$aaa] = $Datos['reprocesos'];
	
	$Num_ventas[] = $Datos['cafi'];
	$Num_ventas[] = $Datos['cdiv'];
	$Num_ventas[] = $Datos['ccom'];
	$Num_ventas[] = $Datos['csto'];
}

foreach($Informacion_reportes_comp as $aaa => $Datos_comp)
{
	$Num_repro_comp[$aaa] = $Datos_comp['reprocesos'];
	$Num_extras_comp[$aaa] = $Datos_comp['extras'];
	
	$Num_ventas_comp[] = $Datos_comp['cafi'];
	$Num_ventas_comp[] = $Datos_comp['cdiv'];
	$Num_ventas_comp[] = $Datos_comp['ccom'];
	$Num_ventas_comp[] = $Datos_comp['csto'];
}

$Mayor_extras = max($Num_extras) + 100;
$Mayor_extras_comp = max($Num_extras_comp) + 100;
$Mayor_repro = max($Num_repro) + 10;
$Mayor_repro_comp = max($Num_repro_comp) + 10;
$Mayor_ventas = max($Num_ventas) + 10000;
$Mayor_ventas_comp = max($Num_ventas_comp) + 10000;
?>
<form action="/reportes/resumen" method="post">
	<strong>Filtros:</strong>
	Rango <select name="rango">
		<option value="anual"<?=('anual'==$Rango)?' selected="selected"':''?>>Anual</option>
		<option value="mes"<?=('mes'==$Rango)?' selected="selected"':''?>>Mensual</option>
	</select>
	Mes <select name="mes">
<?
foreach($Meses as $iMes => $nMes)
{
?>
		<option value="<?=$iMes?>"<?=($iMes==$Mes)?' selected="selected"':''?>><?=$nMes?></option>
<?
}
?>
	</select>
	A&ntilde;o
	<input type="text" name="anho" size="5" value="<?=$Anho?>" />
	<input type="submit" />
</form>

<div id="contiene_todo">
	
	<div class="datos_infografia">
		
		<div ver="resu_cumplimiento" class="resu_cumplimiento">
			<!--img src="/html/img/infogr/load.png" /-->
			<strong class="grande"></strong>
			<br />
			Cumplimiento
		</div>
		
		<!--div ver="resu_entregas" class="resu_entregas">
			<!--img src="/html/img/infogr/load.png" /-- >
			<strong class="grande"></strong>
			<br />
			Entregas para <?=date('d-m-Y')?>
		</div-->
		
		<div ver="resu_extras" class="resu_extras">
			<!--img src="/html/img/infogr/load.png" /-->
			<strong class="grande"></strong>
			<br />
			Horas Extras
		</div>
		
		<div ver="resu_reprocesos" class="resu_reprocesos">
			<!--img src="/html/img/infogr/load.png" /-->
			<strong class="grande"></strong>
			<br />
			Reprocesos
		</div>
		
		<div ver="resu_ventas" class="resu_ventas">
			<!--img src="/html/img/infogr/load.png" /-->
			<strong class="grande"></strong>
			<br />
			Ventas
		</div>
		
		<br style="clear: both;" />
		
	</div>
	
	
	
	<div class="resu_lateral">
		<strong>Reportes</strong> <span class="cierra_ventana toolizq">[x]<span>Regresar a Resumen</span></span>
		<br />
		<div class="resu_opciones">
			<span ver="resu_cumplimiento">Cumplimiento</span>
			<br />
			<span ver="resu_extras">Horas Extras</span>
			<br />
			<span ver="resu_reprocesos">Reprocesos</span>
			<br />
			<span ver="resu_ventas">Ventas</span>
		</div>
	</div>
	
	<div class="resu_informacion" id="resu_cumplimiento">
		<strong style='float:right;' onclick="javascript:$('#reporte-grafico-cumpli-comp, #anho_compc').toggle((function () {grafico_extras_comp()}));" >Comparaci&oacute;n A&ntilde;o Anterior</strong>
		<strong>A&ntilde;o <?=$Anho?></strong><br />
		<div id='reporte-grafico' style='width:750px;height:225px; margin-left: 20px;'></div>
		<br style='clear:left' />
		<strong id='anho_compc' style='display:none;'>A&ntilde;o <?=($Anho -1 )?></strong>
		<div id='reporte-grafico-cumpli-comp' style='width:750px;height:250px; margin-left: 24px; display: none;'></div>
		<br style='clear:left' />
		<h2>Cumplimiento</h2>
		<table class="tabular" style='width: 70%;'>
			<tr>
				<th style='width: 125px;'>Cliente</th>
				<th style='width: 125px;'>Total Pedidos</th>
				<th style='width: 150px;'>Entregas a Tiempo</th>
				<th style='width: 150px;'>Entregas Atrasadas</th>
				<th>Productividad</th>
			</tr>
		</table>
	</div>
	
	<div class="resu_informacion" id="resu_extras">
		<strong style='float:right;' onclick="javascript:$('#reporte-grafico-extras-comp, #anho_compe').toggle((function () {grafico_extras_comp()}));" >Comparaci&oacute;n A&ntilde;o Anterior</strong>
		<strong style='float:left;'>A&ntilde;o <?=$Anho?></strong><br />
		<div id='reporte-grafico-extras' style='width:750px;height:250px; margin-left: 24px;'></div>
		<br style='clear:left' />
		<strong id='anho_compe' style='display:none;'>A&ntilde;o <?=($Anho -1 )?></strong>
		<div id='reporte-grafico-extras-comp' style='width:750px;height:250px; margin-left: 24px; display:none;'></div>
		<br style='clear:left' />
		<h2>Horas Extras</h2>
		<table style='width: 50%;'>
			
		</table>
	</div>
	
	<div class="resu_informacion" id="resu_reprocesos">
		<strong style='float:right;' onclick="javascript:$('#reporte-grafico-repro-comp, #anho_compr').toggle((function () {grafico_reprocesos_com()}));" >Comparaci&oacute;n A&ntilde;o Anterior</strong>
		<strong style='float:left;'>A&ntilde;o <?=$Anho?></strong><br />
		<div id='reporte-grafico-reprocesos' style='width:725px;height:250px; margin-left: 24px;'></div>
		<br style='clear:left' />
		<strong id='anho_compr' style='display:none;'>A&ntilde;o <?=($Anho -1 )?></strong>
		<div id='reporte-grafico-repro-comp' style='width:750px;height:250px; margin-left: 24px; display:none;'></div>
		<br style='clear:left' />		
		
		<h2>Reprocesos</h2>
		<table class="tabular" style='width: 55%;'>
		<tr>
			<th style='width: 20%;'>Cliente</th>
			<th style='width: 25%;'>Pedidos</th>
			<th style='width: 25%;'>Reprocesos</th>
			<th>Porcentaje</th>
		</tr>
		</table>
	</div>
	
	<div class="resu_informacion" id="resu_ventas">
		<strong style='float:right;' onclick="javascript:$('#reporte-grafico-ventas-comp, #anho_compv').toggle((function () {ventas_grafico_comp()}));" >Comparaci&oacute;n A&ntilde;o Anterior</strong>
		<strong>A&ntilde;o <?=$Anho?></strong><br />
		<div id='reporte-grafico-ventas' style='width:775px;height:250px; margin-left: 45px;'></div>
		<br style='clear:left' />
		<strong id='anho_compv' style='display:none;'>A&ntilde;o <?=($Anho -1 )?></strong>
		<div id='reporte-grafico-ventas-comp' style='width:775px;height:250px; margin-left: 45px; display:none;'></div>
		<br style='clear:left' />
		<h2>Ventas</h2>
		<table class="tabular" style='width: 70%;text-align: right;'>
		<tr>
			<th>&nbsp;</th>
			<th>Proyeccion</th>
			<th>Venta</th>
			<th>Porcentaje</th>
		</tr>
		</table>
	</div>
	
	
	<div id="resu_cargando">Obteniendo informaci&oacute;n...</div>
	
	
</div>

<script>
	//Busquedas permitidas
	var informacion = new Array(
		'general',
		'cumplimiento',
		'extras',
		'reprocesos',
		'ventas'
	);
		//'entregas',
	//La informacion se busca por turnos
	var i_informacion = 0;
	
	//Busqueda de la informacion de cada elemento
	function resu_informacion()
	{
		
		$('#resu_cargando').append('.');

		$.get(
			'/reportes/resumen/informacion/'+informacion[i_informacion]+'/<?=$Anho.'-'.$Mes?>/<?=$Rango?>',
			function(msg)
			{
				if('general' == informacion[i_informacion])
				{
				<?php
				if(0 < count($Informacion_reportes))
				{
					
						if($Mes < 10)
						{
							$Mes = $Mes + 0;
						}
						$Tiempo = $Informacion_reportes[$Mes]['ttiempo'];
						$Trabajos = $Informacion_reportes[$Mes]['ttrabajos'];
						$Extras = $Informacion_reportes[$Mes]['extras'];
						$Reprocesos = $Informacion_reportes[$Mes]['reprocesos'];
						$Afi = $Informacion_reportes[$Mes]['cafi'];
						$Com = $Informacion_reportes[$Mes]['ccom'];
						$Div = $Informacion_reportes[$Mes]['cdiv'];
						$Sto = $Informacion_reportes[$Mes]['csto'];
						$Proy = $Informacion_reportes[$Mes]['proy'];
						$Total = number_format(($Afi + $Com + $Div + $Sto), 2, '.', '');
						
						if(0 < $Proy)
						{
							$Porc = number_format(($Total * 100 / $Proy), 2);
						}
						else
						{
							$Porc = number_format(($Total * 100), 2);
						}
						
					}
					if($Tiempo != 0)
					{
						$Tiempo = number_format(($Tiempo * 100 / $Trabajos), 2);
					}
					else
					{
						$Tiempo = 0;
					}
				?>
					$('#resu_cumplimiento h2').append(' &nbsp; '+<?=$Tiempo?>+'%');
					$('.resu_cumplimiento strong').append(<?=$Tiempo?>+'%');
					$('.resu_entregas strong').append(msg[1]);
					$('#resu_extras h2').append(' &nbsp; '+<?=(0 != $Extras)?$Extras:0?>+'h');
					$('.resu_extras strong').append(<?=(0 != $Extras)?$Extras:0?>+'h');
					$('#resu_reprocesos h2').append(' &nbsp; '+<?=(0 != $Reprocesos)?$Reprocesos:0?>+'%');
					$('.resu_reprocesos strong').append(<?=(0 != $Reprocesos)?number_format(($Reprocesos*100/$Trabajos) , 2):0?>+'%');
					$('#resu_ventas h2').append(' &nbsp; '+<?=$Porc?>+'%');
					$('.resu_ventas strong').append(<?=$Porc?>+'%');
				}
				
				if('cumplimiento' == informacion[i_informacion])
				{
					msg = JSON.parse(msg);
					
					var totales = new Array();
					totales['pedidos'] = 0;
					totales['puntuales'] = 0;
					totales['atrasados'] = 0;
					for(cod_cliente in msg)
					{
						$('#resu_cumplimiento table').append('<tr><td><strong>'+cod_cliente+'</strong></td><td>'+msg[cod_cliente].to+'</td><td>'+msg[cod_cliente].ti+'</td><td>'+msg[cod_cliente].at+'</td><td>'+msg[cod_cliente].po+'%</td></tr>');
						totales['pedidos'] = totales['pedidos'] + parseInt(msg[cod_cliente].to);
						totales['puntuales'] = totales['puntuales'] + parseInt(msg[cod_cliente].ti);
						totales['atrasados'] = totales['atrasados'] + parseInt(msg[cod_cliente].at);
					}
					var porcentaje = 100;
					if(0 < totales['pedidos'])
					{
						porcentaje = totales['puntuales'] * 100 / totales['pedidos'] * 100;
						porcentaje = parseInt(porcentaje) / 100;
					}
					$('#resu_cumplimiento table').append('<tr><th>Total</th><th>'+totales['pedidos']+'</th><th>'+totales['puntuales']+'</th><th>'+totales['atrasados']+'</th><th>'+porcentaje+'%</th></tr>');
					$('#resu_cumplimiento').append(grafico());
				}
				
				if('reprocesos' == informacion[i_informacion])
				{
					msg = JSON.parse(msg);
					
					var totales = new Array();
					totales['pedidos'] = 0;
					totales['reprocesos'] = 0;
					for(cod_cliente in msg)
					{
						$('#resu_reprocesos table').append('<tr><td><strong>'+cod_cliente+'</strong></td><td>'+msg[cod_cliente].to+'</td><td>'+msg[cod_cliente].re+'</td><td>'+msg[cod_cliente].po+'%</td></tr>');
						totales['pedidos'] = totales['pedidos'] + parseInt(msg[cod_cliente].to);
						totales['reprocesos'] = totales['reprocesos'] + parseInt(msg[cod_cliente].re);
					}
					var porcentaje = 100;
					if(0 < totales['pedidos'])
					{
						porcentaje = totales['reprocesos'] * 100 / totales['pedidos'] * 100;
						porcentaje = parseInt(porcentaje) / 100;
					}
					$('#resu_reprocesos table').append('<tr><th>Total</th><th>'+totales['pedidos']+'</th><th>'+totales['reprocesos']+'</th><th>'+porcentaje+'%</th></tr>');
					$('#resu_reprocesos').append(grafico_reprocesos());
				}
				
				
				
				if('ventas' == informacion[i_informacion])
				{
					msg = JSON.parse(msg);
					
					for(tipo_clie in msg['cli'])
					{
						$('#resu_ventas table').append('<tr><td>'+msg['cli'][tipo_clie].tip+'</td><td>$'+msg['cli'][tipo_clie].pro+'</td><td>$'+msg['cli'][tipo_clie].ven+'</td><td>'+msg['cli'][tipo_clie].por+'%</td></tr>');
					}
					$('#resu_ventas table').append('<tr><th>Total</th><th>$'+msg.pro+'</th><th>$'+msg.tot+'</th><th>'+msg.por+'%</th></tr>');
					$('#resu_ventas').append(ventas_grafico());
					
				}
				
				
				if('extras' == informacion[i_informacion])
				{
						msg = JSON.parse(msg);
						
						$('#resu_extras table').append('<tr><th>Departamento</th><th>Total de Horas</th></tr>');
						for(cod_cliente in msg)
						{
							var total = 0;
							$('#resu_extras table').append('<tr><td>'+msg[cod_cliente].dpto+'</td><td>'+msg[cod_cliente].tot+'</td></tr>');
						}
						$('#resu_extras').append(grafico_extras('princ'));
				}
				
				
				i_informacion++;
				if(i_informacion in informacion)
				{
					resu_informacion();
				}
				else
				{
					
					//Todos los div deben tener el mismo alto
					var alto_max = 0;
					$('#contiene_todo>div').each(function()
					{
						if(alto_max < $(this).height())
						{
							alto_max = $(this).height();
						}
					});
					alto_max = alto_max + 20;
					$('#contiene_todo>div').each(function()
					{
						$(this).height(alto_max);
					});
					
					//Presentacion de la informacion obtenida
					$('#resu_cargando').hide('fade', 1000, function(){ $('.datos_infografia').show('fade', 1000); });
				}
				
			}
		);
		
	}
	
	//Obtener informacion
	resu_informacion();
	
	
	
	//Div mostrado actualmente
	var div_actual = '';
	//Div que se oculta
	var div_ocultar = '';
	
	$('.datos_infografia').css('z-index',0);
	$('.resu_informacion').css('z-index',2);
	$('.resu_lateral').css('z-index',5);
	$('#resu_cargando').css('z-index', 5);
	
	//Muestra el detalle
	$('.datos_infografia div').click(function()
	{
		div_actual = $(this).attr('ver');
		$('.resu_lateral').show('slide',{direction: 'left'}, function(){ $('#'+div_actual).show('slide',{direction: 'right'},1000); });
	});
	
	//Oculta el detalle
	$('.cierra_ventana').click(function()
	{
		$('#'+div_actual).hide('slide',{direction: 'right'}, function(){ $('.resu_lateral').hide('slide',{direction: 'left'}); });
	});
	
	//Cambiar "diapositiva"
	$('.resu_opciones span').click(function(){
		if(div_actual == $(this).attr('ver'))
		{
			return false;
		}
		
		div_ocultar = div_actual;
		div_actual = $(this).attr('ver');
		$('.resu_informacion').css('z-index',1);
		$('#'+div_ocultar).css('z-index',2);
		$('#'+div_actual).css('z-index',3);
		$('#'+div_actual).show('slide',{direction: 'right'},1000, function(){ $('#'+div_ocultar).hide(); });
	});
</script>
<?php
include('graficos_reporte_resumen_v.php');
?>

<br /><br />&nbsp;