<script type="text/javascript" src="/html/js/jquery.flot.js"></script>
<script type="text/javascript" src="/html/js/jquery.flot.pie.js"></script>
<script type="text/javascript" src="/html/js/inventario.js?n=1"></script>
<script type="text/javascript">
	$(function()
	{
		$("[name=date1]").datepicker({dateFormat: 'dd-mm-yy', showButtonPanel: true, onSelect: function() { /*calcular_fecha_entrega();*/ } });
	});
</script>

<div class="informacion">	
	<select name="id_material" id="id_material" onchange="ir_pag()">
<?
if($Materiales > 0)
{
	foreach($Materiales as $Datos_material)
	{
		$id_mate = $Datos_material["id_inventario_material"];
		if($id_mate == $Id_material)
		{
			$cantidad = $Datos_material["existencias"];
			$tipo = $Datos_material["tipo"];
			$unidad = $Datos_material["cantidad_unidad"];
			$numero_individual = $Datos_material['numero_individual'] ;
			$numero_cajas = $Datos_material['numero_cajas'];
		}
	
?>
		<option value="<?=$id_mate?>" <?=($id_mate == $Id_material)?' selected="selected"':''?>><?=$Datos_material["codigo_sap"]." - ".$Datos_material["nombre_material"]?></option>
<?php
	}
}
?>
	</select>
	<select name='anho' id='anho' onchange="ir_pag()">
<?php
	for($a = 2009; $a <= date('Y'); $a++)
	{
?>
		<option value='<?=$a?>' <?=($anho==$a)?' selected="selected"':''?>><?=$a?></option>
<?php
	}
?>
	</select>
<?php
if(0 < $numero_individual)
{
	if(0 < $numero_cajas)
	{
		$total_cajas2 = ($cantidad / $numero_individual / $numero_cajas);
	}
}

$Consumo_mensual = 0;
$cobertura = 0;
if(isset($Consumo[$Id_material]))
{
	$Consumo_mensual = $Consumo[$Id_material]['consumo_cajas'];
	if(0 < $Consumo_mensual)
	{
		$cobertura = number_format((($total_cajas2 * 24) / $Consumo_mensual), 0);
	}
}

	$Existencia = $cantidad;
	switch($tipo)
	{
		case 'IN2':
			$mos_tipo = 'cajas';
			$Existencia = number_format(($cantidad / ($numero_individual * $numero_cajas)) , 0);
			break;
		case 'PZA':
			$mos_tipo = 'Cajas';
			break;
		default:
			$mos_tipo = $tipo;
			break;
	}
$Ped_Semestral = number_format($Consumo_mensual * 6 * $numero_cajas * $numero_individual, 0);
?>
	<table>
		<tr>
			<th style='color: #040c4c;'>INFORMACI&Oacute;N DE INVENTARIO</th>
		</tr>
		<tr>
			<td>Existencias en Bodega: &nbsp; <strong><?=$Existencia?> <?=$mos_tipo?> ( <?=number_format($cantidad, 2)?> <?=$tipo?> )</strong></td>
			<td>Cobertura: &nbsp; <strong><?=$cobertura?> dias	</strong></td>
		</tr>
		<tr>
			<td>Consumo del Mes: &nbsp; <strong> ( <?=number_format($Consumo_mensual, 2)?> <?=$mos_tipo?> )</strong></td>
			<td>Pedido Semestral: &nbsp; <strong><?=number_format($Consumo_mensual * 6, 2).' '.$mos_tipo." ($Ped_Semestral $tipo)"; ?></strong></td>
		</tr>
	</table>
	
	<?php
if(count($Informacion_material_detalle) != 0)
{
?>
	<div id='corte_pagina'></div>
	<table style='width: 100%;'>
		<tr>
			<th style='color: #040c4c;'>INFORMACION T&Eacute;CNICA</th>
		</tr>
		<tr>
			<th style='width:32%'>Proveedor</th>
			<th style='width:15%'>Tipo</th>
			<th style='width:23%'>IN2 &oacute; PZAS por Placas</th>
			<th style='width:23%'>Placas por Caja</th>
			<th style='width:15%'>Tama&ntilde;o</th>
		</tr>
<?php
	foreach($Informacion_material_detalle as $Datos)
	{
?>
		<tr>
			<td><?=$Datos['proveedor_nombre']?></td>
			<td><?=$Datos['nombre_tipo']?></td>
			<td><?=$Datos['numero_individual']?> <?=$Datos['tipo']?></td>
			<td><?=$Datos['numero_total']?></td>
			<td><?=strtoupper($Datos['tamanho'])?></td>
		</tr>
<?php
	}
?>
	</table>
<?php
}
?>
	<div id='corte_pagina'></div>
	<form name="miform" action="/inventario/inventario_lot/agregar_lote" method="post" onsubmit="return validar_lot()">
		
		<input type="hidden" name="id_inventario_material" value="<? echo $Id_material; ?>" />
		
		<strong style='color: #040c4c;'>AGREGAR LOTES DE MATERIAL</strong><br />
		
		Pedido: <input type="text" name="pedido" id='pedido' size="15" /> &nbsp; 
		Cantidad: <input type="text" name="cantidad" id='cantidad' size="10" /> &nbsp; 
		Fecha de Ingreso: <input type="text" name="date1" id="date1" size="12" value="" readonly="readonly" /> &nbsp; 
		<input type="submit" class="boton" value="Agregar" />
		
	</form>
<br />
<strong style='color: #040c4c;'>CONSUMO ANUAL DEL MATERIAL</strong><br />
<div id="grafico-actual" style="width:925px;height:300px;"></div>

</div>

	<script language='javascript' type='text/javascript'>
		
		var venta_mensual = [];
		var info = '';
<?php
$Maximo_Valor = 0;
if(0 < count($ConsumoMensual))
{
	foreach($ConsumoMensual as $mes => $informacion)
	{
		if($Maximo_Valor < $informacion['cantidad'])
		{
			$Maximo_Valor = $informacion['cantidad'];
		}
?>
		venta_mensual.push([<?=($mes+0)?>,<?=(isset($mes))?$informacion['cantidad']:500?>]),
<?
	}
}
?>
		info = $.plot($('#grafico-actual'),
			[
				{
					data: venta_mensual,
					points: { show: true },
					label: 'Consumo Mensual'
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
					min: 0,
					tickFormatter: function(valor, axis)
					{
						return formatNumber(valor, '');
					},
					max: <?=$Maximo_Valor?>
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
		
		
		
		var previousPoint = null;
		$("#grafico-actual").bind("plothover", function (event, pos, item)
		{
			$("#x").text(pos.x.toFixed(2));
			$("#y").text(pos.y.toFixed(2));
			
			if(item)
			{
				if(previousPoint != item.datapoint)
				{
					previousPoint = item.datapoint;
					$("#tooltip").remove();
					var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);
					showTooltip(item.pageX, item.pageY, (formatNumber(y) + ' <?=$tipo?>'));
				}
			}
			else
			{
				$("#tooltip").remove();
				previousPoint = null;
			}
		});
		
	</script>
	
	<br /><br /><br /><br />
	
	<div id='corte_pagina'></div>
	<strong style='color: #010323;'>REPORTE DE MATERIAL</strong><br />
	
<?
//======================================
//Inicio Rotacion
?>
		<table class='tabla_i'>
			<tr>
				<th>Ingresos</th>
				<th>Salidas</th>
			</tr>
<?
$cantidad = 0;
//Busco los lotes correspondientes a este material y al rango especifico
foreach($Lotes_material as $Datos_lotes)
{
?>
			<tr>
				<td style='width:225px;'>
				Cantidad: <strong><?=$Datos_lotes["unidades"]?></strong><br />
				Pedido <strong><?=$Datos_lotes["pedido"]?></strong><br />
				Fecha: <strong><?=date('d-m-Y', strtotime($Datos_lotes["fecha_ingreso"]))?></strong>
				</td>
<?php	
				$i = 0;
				$dias_trans = 0;
				if(0 < $Datos_lotes["estado"])
				{
					//Si este lote aun no ha sido finalizado; le agrego la rotacion a la fecha de hoy
					$i = 1;
					$dias_trans = (strtotime(date('Y-m-d')) - strtotime($Datos_lotes["fecha_ingreso"]));
					$dias_trans = ((($dias_trans / 60) / 60) / 24);
				}
				else
				{
						//Calcular los dias transcurridos entre la fecha de ingreso y el ultimo movimiento
						$dias_trans = (strtotime($Datos_lotes["fecha_salida"]) - strtotime($Datos_lotes["fecha_ingreso"]));		
						$dias_trans = ((($dias_trans / 60) / 60) / 24);
						$i++;
				}
?>	
			<td>
			Rotaci&oacute;n: <strong><?=number_format($dias_trans, 2)?></strong>
<?php
	//print_r($Datos_lotes);
	if($Datos_lotes['req'] != '')
	{
		foreach($Datos_lotes['req'] as $Datos_lot)
		{
		 ?>
				<br />Requisici&oacute;n: <strong><?=$Datos_lot['numero_requ']?></strong> &nbsp;
				<?=('0000-00-00'!=$Datos_lot["fecha_salida"])?date('d-m-Y', strtotime($Datos_lot["fecha_salida"])):''?>
				<?=$Datos_lot['nombre']?> &nbsp; &raquo; &nbsp; <?=$Datos_lot['cantidad']?>( <?=number_format($Datos_lot['cantidad'] * $unidad, 2).' '.$tipo?> )
	<?php
		}
	}
	?>
			</td>
			</tr>
<?php
}
?>
	</table>
	<br /><strong>P&aacute;ginas:
<?=$Paginar?>
</strong>
 
</div>