
<script type="text/javascript" src="/html/js/jquery.flot.js"></script>
<script type="text/javascript" src="/html/js/jquery.flot.pie.js"></script>
<style>
	.manita
	{
		cursor: pointer;
	}
</style>
<!--label class='manita' style='margin-left: 740px; position: absolute;' onclick='ocultar()'><strong>Comparar con A&ntilde;o Anterior</strong></label-->
<br />

<div class='ocultar'>
<?php

/*
?>

<strong>Acumulado <?=date('Y')?></strong>
<br />
Proyectado: $<?=number_format($Total_Proyeccion)?>
&nbsp; &nbsp; &nbsp;
Venta: $<?=number_format($Total_Venta)?>
&nbsp; &nbsp; &nbsp;
Cumplimiento: 
<?php
if(0 < $Total_Proyeccion)
{
	echo number_format((($Total_Venta*100)/$Total_Proyeccion), 2);
}
else
{
	echo number_format((($Total_Venta*100)), 2);
}
*/
?>
	<script language='javascript' type='text/javascript'>
	
	
	
	function graf(datos, titulos, Maximo_Valor, tipo_graf)
	{
		var venta_mensual = [];
		var proyeccion = [];
		var reprocesos = [];
		var info = '';
		
		var datos = JSON.parse(datos);
		var eeee = [];
		var oooo = [];
		
		for(i in datos.venta_mensual)
		{
			venta_mensual.push([i, datos.venta_mensual[i]]);
			proyeccion.push([i, datos.proyeccion[i]]);
		}

		info = $.plot($('#'+tipo_graf),
			[
				{
					data: venta_mensual,
					points: { show: true },
					label: titulo[0]
				},
				{
					data: proyeccion,
					points: { show: true },
					label: titulo[1]
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
					max: Maximo_Valor
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
		for(z = 0; z < 1; z++)
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
					$('<div class="data-point-label">' + formatNumber(el[1], '$') + '</div>').css(
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
		
		var previousPoint = null;
		$("#"+tipo_graf).bind("plothover", function (event, pos, item)
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
					showTooltip(item.pageX, item.pageY, formatNumber(y, '$'));
				}
			}
			else
			{
				$("#tooltip").remove();
				previousPoint = null;
			}
		});
	}
	
	
</script>
	
<?php
$VentaM = 0;
$Info = array();
$Info2 = array();
$Proyeccion = 0;
$Total_Venta = 0;
$Total_Proyeccion = 0;

if(count($Actual) != 0)
{
	foreach($Actual as $Index => $Valor)
	{
		$venta_temp = 0;
		$Info['venta_mensual'][$Index] = 0;
		$Info2['venta_mensual'][$Index] = 0;
		if(isset($Valor['venta']))
		{
			$venta_temp = $Valor['venta'];
			$Info['venta_mensual'][$Index] = $venta_temp;
			$Info2['venta_mensual'][$Index] = $venta_temp;
		}
		
		$Info['proyeccion'][$Index] = 0;
		if(isset($Valor['proyeccion']))
		{
			$Info['proyeccion'][$Index] = $Valor['proyeccion'];
		}
		
		$Total_Venta += $venta_temp;
		$Total_Proyeccion += $Info['proyeccion'][$Index];
	}
}

if(max($Info['proyeccion']) < max($Info['venta_mensual']))
{
	$Maximo_Valor = max($Info['venta_mensual']) + 20000;
}
else
{
	$Maximo_Valor = max($Info['proyeccion']) + 20000;
}

?>

<strong>Acumulado <?=(date('Y'))?></strong>
<br />
Proyectado: $<?=number_format($Total_Proyeccion)?>
&nbsp; &nbsp; &nbsp;
Venta: $<?=number_format($Total_Venta)?>
&nbsp; &nbsp; &nbsp;
Cumplimiento:

<?=(0 < $Total_Proyeccion)?(number_format((($Total_Venta*100)/$Total_Proyeccion), 2)):(number_format((($Total_Venta*100)), 2))?> %

<div id="graf_act" style="width:925px;height:325px;"></div>

<script>
	var hola = [];
	
	var titulo = [];
	titulo = ['Ventas Mensuales', 'Proyecciones Mensuales'];
	graf('<?=json_encode($Info)?>', titulo, '<?=$Maximo_Valor?>', 'graf_act');
</script>
	

<?php
/************************************
 *Ventas anteriores
************************************/

$Maximo_Valor = 0;
$Info = array();
$Total_Venta = 0;
$Total_Proyeccion = 0;
if(0 != count($Anterior))
{
	foreach($Anterior as $Index => $Valor)
	{
		$venta_temp = 0;
		$Info['venta_mensual'][$Index] = 0;
		$Info2['proyeccion'][$Index] = 0;
		if(isset($Valor['venta']))
		{
			$venta_temp = $Valor['venta'];
			$Info['venta_mensual'][$Index] = $venta_temp;
			$Info2['proyeccion'][$Index] = $venta_temp;
		}
		
		$Info['proyeccion'][$Index] = 0;
		if(isset($Valor['proyeccion']))
		{
			$Info['proyeccion'][$Index] = $Valor['proyeccion'];
		}
		
		$Total_Venta += $venta_temp;
		$Total_Proyeccion += $Info['proyeccion'][$Index];
	}
}

if(max($Info['proyeccion']) < max($Info['venta_mensual']))
{
	$Maximo_Valor = max($Info['venta_mensual']) + 20000;
}
else
{
	$Maximo_Valor = max($Info['proyeccion']) + 20000;
}
?>

<div class='ocultar'>
<br /><br /><br /><br />

<strong>Acumulado <?=(date('Y') - 1)?></strong>
<br />
Proyectado: $<?=number_format($Total_Proyeccion)?>
&nbsp; &nbsp; &nbsp;
Venta: $<?=number_format($Total_Venta)?>
&nbsp; &nbsp; &nbsp;
Cumplimiento:
<?=(0 < $Total_Proyeccion)?(number_format((($Total_Venta*100)/$Total_Proyeccion), 2)):(number_format((($Total_Venta*100)), 2))?> %

<div id="graf_ant" style="width:925px;height:325px;"></div>

<script>
	var titulo = [];
	titulo[0] = 'Ventas Mensuales ant';
	titulo[1] = 'Proyecciones Mensuales ant';
	graf('<?=json_encode($Info)?>', titulo, '<?=$Maximo_Valor?>', 'graf_ant');
</script>

<?php
$VentaM =  max($Info2['venta_mensual']); 
$VentaM2 = max($Info2['proyeccion']); 
if(max($Info2['venta_mensual']) < max($Info2['proyeccion']))
{
	$Maximo_Valor = $VentaM2 + 20000;
}
else
{
	$Maximo_Valor = $VentaM + 20000;
}

?>
<br /><br /><br /><br />
	<strong>Comparaci&oacute;n A&ntilde;o <?=date('Y')?> vs A&ntilde;o <?=date('Y') - 1?></strong>
<div id="graf_comp" style="width:925px;height:325px;"></div>
<br /><br />

<script>
	var titulo = [];
	titulo[0] = 'Anho 2012';
	titulo[1] = 'Anho 2013';
	graf('<?=json_encode($Info2)?>', titulo, <?=$Maximo_Valor?>, 'graf_comp');
	function ocultar()
	{
		$('.ocultar').toggle();
		$('#comparacion').toggle();
	}
</script>