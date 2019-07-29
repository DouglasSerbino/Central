<?php
if('mostrar' == $this->generar_cache_m->preparar_cache($Cache))
{
?>
<script type="text/javascript" src="/html/js/jquery.flot.js"></script>
<script type="text/javascript" src="/html/js/jquery.flot.pie.js"></script>
<script type="text/javascript" src="/html/js/venta.js?n=1"></script>
<link rel="stylesheet" href="/html/css/responsive.css" />
<script>
	$(function()
	{
		$('#exp_col').click(function(){
				$('.tr_escondido').toggle();
				$('#exp_col span').toggle();
			});
	});
</script>
<style>
	.manita
	{
		cursor: pointer;
	}
</style>
	<strong><?=$clientes['nombre']?> - Reprocesos &nbsp; <span id="exp_col" class='manita'><span style="display:none;">[-]</span><span>[+]</span></span></strong>

<div class="informacion">
<table class='tabular' style='width: 95%;'>
	<tr>
		<td style='width: 18%;'><strong>Proceso</strong></td>
		<td><strong>Producto</strong></td>
		<td style='width: 12%; text-align: center;'><strong>Fecha de Pedido</strong></td>
		<td style='width: 12%; text-align: center;'><strong>Fecha Entregado</strong></td>
		<td style='width: 25%; text-align: center;'><strong>Motivo de Reproceso</strong></td>
	</tr>
<?php
$Total_Materiales = array();
$Total_Tiempos = 0;
$Info_reprocesos = array();
//Array necesarios para crear el grafico de lineas.
$reprocesos_gra = array();
$grafico_reprocesos = array();
$Reprocesos_Anuales_Num = count($pedidos_grafico);


//Foreach para obtener el total de pedidos por mes.
foreach($pedidos_grafico as $Datos_gra)
{
	$fecha = substr($Datos_gra["fecha_entrega"],5,2);
	$reprocesos_gra[$fecha]['fecha'] = $fecha;
	$reprocesos_gra[$fecha]['info'][$Datos_gra['id_pedido']]['id_pedido'] = $Datos_gra["id_pedido"];
}
//Ordeno el array de forma ascendente.
asort($reprocesos_gra);

//Recorro el array $reprocesos_gra para obtener el total de pedidos por mes.
foreach($reprocesos_gra as $Datos_repro)
{
	$grafico_reprocesos[($Datos_repro['fecha']+0)] = count($Datos_repro['info']);
}

//Recorro el array para asignar los pedidos al mes que les corresponde.
foreach($informacion_pedidos as $Datos)
{
	$fecha = substr($Datos["fecha_entrega"],5,2);
	$Info_reprocesos[$fecha]['fecha'] = $fecha;
	$Info_reprocesos[$fecha]['info'][$Datos['id_pedido']]['id_pedido'] = $Datos["id_pedido"];
	$Info_reprocesos[$fecha]['info'][$Datos['id_pedido']]['fecha_reale'] = $Datos["fecha_reale"];
	$Info_reprocesos[$fecha]['info'][$Datos['id_pedido']]['codigo_cliente'] = $Datos["codigo_cliente"];
	$Info_reprocesos[$fecha]['info'][$Datos['id_pedido']]['proceso'] = $Datos["proceso"];
	$Info_reprocesos[$fecha]['info'][$Datos['id_pedido']]['fecha_entrada'] = $Datos["fecha_entrada"];
	$Info_reprocesos[$fecha]['info'][$Datos['id_pedido']]['nombre'] = $Datos["nombre"];
	$Info_reprocesos[$fecha]['info'][$Datos['id_pedido']]['detalle'] = $Datos["detalle"];
}
//Ordeno el array de forma ascendente.
asort($Info_reprocesos);
//print_r($Info_reprocesos);
$total_reprocesos = 0;

//Recorro el array $Info_reprocesos para mostrar los pedidos en el mes que les
//corresponde.
foreach($Info_reprocesos as $Datos_pedidos)
{		
	$total_reprocesos = $total_reprocesos + count($Datos_pedidos['info']);
?>
	<table style="width: 95%;">
	<tr>
		<th colspan='4'><br />REPROCESOS EN EL MES DE <?php=(isset($Meses[$Datos_pedidos['fecha']]))?strtoupper($Meses[$Datos_pedidos['fecha']]):date('M')?> (<?=count($Datos_pedidos['info'])?>)</th>
	</tr>
<?php
	foreach($Datos_pedidos['info'] as $Datos_pedido)
	{
?>
	<tr>
		<td style='width: 18%;'>
			<a class="iconos idocumento toolder" href="/pedidos/especificacion/ver/<?=$Datos_pedido["id_pedido"]?>/n"></a>
			<a href="/pedidos/pedido_detalle/index/<?=$Datos_pedido["id_pedido"]?>" title="Ver Detalle del Pedido"><strong><?=$Datos_pedido["codigo_cliente"]."-".$Datos_pedido["proceso"]?></strong></a>
		</td>
		<td>
			<?=$Datos_pedido["nombre"]?>
		</td>
		<td style='text-align: center; width: 12%;'><?=('0000-00-00' != $Datos_pedido['fecha_entrada'])?date('d-m-Y', strtotime($Datos_pedido["fecha_entrada"])):'00-00-0000'?></td>
		<td style='text-align: center; width: 12%;'><?=('0000-00-00' != $Datos_pedido['fecha_reale'])?date('d-m-Y', strtotime($Datos_pedido["fecha_reale"])):'00-00-0000'?></td>
		<td style='text-align: left; width: 25%;'><?=$Datos_pedido['detalle']?></td>
	</tr>
	<tr>
		<td colspan='5' align='center'>
		<table class='tabular' style='width: 99%;'>
			<tr class='tr_escondido' style='display:none;'>
				<th style='width: 20%;'>Puesto</th>
				<th>Fecha</th>
				<th>Inicio</th>
				<th>Fin</th>
				<th>Horas</th>
				<th>Turno</th>
			</tr>
<?php
foreach($informacion_general as $Datos_general)
{
	foreach($Datos_general as $Datos)
	{
		if($Datos_pedido['id_pedido'] == $Datos['id_pedido'])
		{
			//date('d-m-Y H:i:s', strtotime('- 2 hour', strtotime('2012-01-01 12:30:00')));
			$turno = 1;
			$fecha_hora = $this->fechas_m->fecha_subdiv($Datos['inicio']);
			$horas = $this->fechas_m->minutos_a_hora($Datos["tiempo_usuario"]);
			$fin = date('H:i', strtotime("+ ".$Datos["tiempo_usuario"]." minutes", strtotime($Datos['inicio'])));
			
			$Total_Tiempos += $Datos["tiempo_usuario"];
			
			if($fecha_hora['hora'] < 8 || $fecha_hora["hora"] >= 17)
			{
				$turno = 2;
			}
?>
			<tr class='tr_escondido' style='display:none;'>
				<td><?=$Datos['puesto']?> <?=$Datos['usuario']?></td>
				<td><?=$fecha_hora['dia']?>-<?=$fecha_hora['mes']?> <?=$fecha_hora['anho']?></td>
				<td><?=$fecha_hora['hora']?>:<?=$fecha_hora['minuto']?></td>
				<td><?=$fin?></td>
				<td><?=$horas?></td>
				<td><?=$turno?></td>
			</tr>
<?php
			
		}
	}
}
?>
		</table>
<?php
if(count($informacion_materiales != 0))
{
?>	
	<table style='width: 99%;'>
		<tr class='tr_escondido' style='display:none;'>
			<th>Codigo</th>
			<th>Nombre del Material</th>
			<th>Cantidad</th>
			<th>Tipo</th>
		</tr>
	<?php
	foreach($informacion_materiales as $Datos_mat)
	{
		foreach($Datos_mat as $Datos_material)
		{
			if($Datos_pedido['id_pedido'] == $Datos_material['id_pedido'])
			{
				
				if(!isset($Total_Materiales[$Datos_material['id_inventario_material']]))
				{
					$Total_Materiales[$Datos_material['id_inventario_material']]['sap'] = $Datos_material['codigo_sap'];
					$Total_Materiales[$Datos_material['id_inventario_material']]['nom'] = $Datos_material['nombre_material'];
					$Total_Materiales[$Datos_material['id_inventario_material']]['can'] = $Datos_material['cantidad'];
					$Total_Materiales[$Datos_material['id_inventario_material']]['tip'] = $Datos_material['tipo'];
				}
				else
				{
					$Total_Materiales[$Datos_material['id_inventario_material']]['can'] += $Datos_material['cantidad'];
				}
				
	?>
		<tr class='tr_escondido' style='display:none;'>
			<td><?=$Datos_material['codigo_sap']?></td>
			<td><?=$Datos_material['nombre_material']?></td>
			<td><?=$Datos_material['cantidad']?></td>
			<td><?=$Datos_material['tipo']?><br />&nbsp;</td>
		</tr>
	<?php
			}
		}
	}
	?>
			</table>
<?php
}
?>
		</td>
	</tr>
<?php
}
?>
</table>
<?php
}
?>
</table>
</div>


<br /><br />
<strong style='color:#0f0f0f;'>TIEMPOS UTILIZADOS: <?=$this->fechas_m->minutos_a_hora($Total_Tiempos)?> h</strong>
<br >
<strong style='color:#0f0f0f;'>TOTAL DE REPROCESOS EN EL A&Ntilde;O: <?=$Reprocesos_Anuales_Num?></strong>
<br /><br />
<strong style='color:#0f0f0f;'>TOTAL DE MATERIALES</strong>

<table style="width: 100%;" class="tabular">
	<tr>
		<th>Codigo</th>
		<th>Nombre del Material</th>
		<th>Cantidad</th>
		<th>Tipo</th>
	</tr>
<?php
if(0 < count($Total_Materiales))
{
	foreach($Total_Materiales as $Material)
	{
?>
	<tr>
		<td><?=$Material['sap']?></td>
		<td><?=$Material['nom']?></td>
		<td><?=$Material['can']?></td>
		<td><?=$Material['tip']?></td>
	</tr>
<?php
	}
}
else
{
?>
	<tr>
		<td colspan="4">No se reportaron materiales.</td>
	</tr>
<?php
}
//print_r($grafico_reprocesos);
?>
</table>
<br />

<strong>REPORTE ANUAL DE REPROCESOS</strong>
	<div id="grafico-linea" style="width:800px;height:300px;"></div>
	<script language='javascript' type='text/javascript'>
		
		var entregas_tiempo = [];
		var entregas_atrasadas = [];
		var reprocesos = [];
		var info = '';
<?php
$num_mayor = max($grafico_reprocesos);
for($e= 1; $e<=12; $e++)
{
	if(isset($grafico_reprocesos[$e]))
	{
?>
		reprocesos.push([<?=($e+0)?>,<?=$grafico_reprocesos[$e]?>]),
<?php
	}
	else
	{
?>
	reprocesos.push([<?=($e+0)?>,0]),
<?php
	}
}
?>
		info = $.plot($('#grafico-linea'),
			[
				{
					data: reprocesos,
					points: { show: true },
					label: 'Porcentaje de Reprocesos'
				}
			],
			{
				xaxis:
				{
					min: 0,
					ticks:
					[
						[0, ""],
						[1, "Ene"], [2, "Feb"], [3, "Mar"], [4, "Abr"],
						[5, "May"], [6, "Jun"], [7, "Jul"], [8, "Ago"],
						[9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dic"],
						[13, ""]
					],
					max: 13
				},
				series:
				{
					lines: { show: true },
					points: { show: true }
				},
				grid: { hoverable: true },
				yaxis:
				{
					tickFormatter: function(valor, axis)
					{
						return formatNumber(valor, '');
					},
					max: <?=$num_mayor+3?>
				}
			}
		);
		
		function showTooltip(x, y, contents)
		{
			$('<div id="tooltip">' + contents + '</div>').css(
			{
				position: 'absolute',
				display: 'none',
				top: y + 10,
				left: x + 10,
				border: '1px solid #fdd',
				padding: '2px',
				'background-color': '#fee',
				opacity: 0.80
			}).appendTo("body").fadeIn(200);
		}
		
		var total_item = info.getData();
		total_item = total_item.length;
		for(z = 0; z < total_item; z++)
		{
			$.each(info.getData()[z].data, function(i, el, infor)
			{
				if('' != el)
				{
					var o = info.pointOffset({x: el[0], y: el[1]});
					var mas = '';
					if(100 == el[1])
					{
						mas = '+';
					}
					$('<div class="data-point-label">' + el[1] + mas + '</div>').css(
					{
						position: 'absolute',
						left: o.left + 4,
						top: o.top - 17,
						display: 'none',
						"font-size": "12px"
					}).appendTo(info.getPlaceholder()).fadeIn('slow');
				}
			});
		}
		
	</script>
</div>
		<br style="clear: both;" />
<?php
	$this->generar_cache_m->generar_cache($Cache);
}
?>