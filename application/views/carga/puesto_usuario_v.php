<script type="text/javascript" src="/html/js/jquery.flot.js"></script>
<script type="text/javascript" src="/html/js/jquery.flot.pie.js"></script>

<style>
	.data-point-label{
		font-weight: bold;
	}
</style>



<?
//Lastimosamente estos datos no estan tan listos
$Trabajos_Usuario = 0;
if(isset($Trabajos[$Id_Usuario]))
{
	$Trabajos_Usuario = $Trabajos[$Id_Usuario];
}
if(!isset($TotaTrab))
{
	$TotaTrab = 0;
}
$Rech_Usuario = 0;
$Rech_Porcentaje = 0;
$Trab_Porcentaje = 100;
if(isset($Rechazos[$Id_Usuario]))
{
	$Rech_Usuario = $Rechazos[$Id_Usuario];
	if(0 < $Trabajos_Usuario)
	{
		$Rech_Porcentaje = ($Rech_Usuario * 100) / $Trabajos_Usuario;
	}
	$Rech_Porcentaje =number_format($Rech_Porcentaje, 2);
	$Trab_Porcentaje -= $Rech_Porcentaje;
}
?>




<strong><?=$Usuario[0]['nombre']?></strong> [<?=$Usuario[0]['puesto']?>] &nbsp; <?=('Anual'==$Mes)?' Anual':$Meses[$Mes]?> - <?=$Anho?>


<br /><br />
<div id="tiem_usu" style="width:400px;height:240px;float:left;margin-right:50px;"></div>
<div id="trab_rec" style="width:350px;height:200px;float:left;"></div>

<script>
	var d2 = [[0.5, <?=(isset($TProgramado[$Id_Usuario]['horas']))?intval($TProgramado[$Id_Usuario]['horas']):0?>], [1.5, <?=isset($TUtilizado[$Id_Usuario]['habil']['horas'])?intval($TUtilizado[$Id_Usuario]['habil']['horas']):0?>], [2.5, <?=number_format(($THabil / 60), 0)?>]];
	var p = $.plot(
		$("#tiem_usu"),
		[
			{
				label: "TIEMPOS",
				data: d2,
				color: '#5f8758',
				bars: { show: true, fill: true, fillColor: '#8fc386' }
			}
		],
		{
			xaxis:
			{
				min: 0,
				ticks:[[0, ""],[1, "PROGRAMADO"], [2, "UTILIZADO"], [3, "HABIL"], [4, ""], [5, ""]],
				max: 5
			}
		}
	);
	
	
	$.each(p.getData()[0].data, function(i, el)
	{
		var o = p.pointOffset({x: el[0], y: el[1]});
		$('<div class="data-point-label">' + el[1] + 'h</div>').css(
		{
			position: 'absolute',
			left: o.left + 4,
			top: o.top - 17,
			display: 'none'
		}).appendTo(p.getPlaceholder()).fadeIn('slow');
	});
	
	
	$.plot($('#trab_rec'),
			[
				{
					label: 'Rechazos <?=$Rech_Porcentaje?>%',  data: [[1,<?=$Rech_Usuario?>]]
				},
				{
					label: 'Trabajos <?=$Trab_Porcentaje?>%',  data: [[1,<?=$Trabajos_Usuario?>]]
				}
			],
			{
				series:{ pie:{ show: true } },
				legend:{ show: true }
			}
		);
	
</script>





<br style="clear: both;" /><br />
<br style="clear: both;" /><br />

<strong>Total de Trabajos: <?=$TotaTrab?></strong>

<br />
<?
if(isset($ListClie))
{
	foreach($ListClie as $Cod_Cliente => $Cliente)
	{
?>

<strong><?=$Cliente?></strong>

<table class="tabular" style="width: 100%;">
	<tr>
		<th style="width: 90px;">Proceso</th>
		<th>Trabajo</th>
		<th style="width: 90px;">Finalizado</th>
		<th style="width: 60px;">Progr.</th>
		<th style="width: 60px;">Real</th>
	</tr>
<?
		foreach($ListTrab[$Cod_Cliente] as $Trabajo)
		{
			$Trabajo['fecha_fin'] = substr($Trabajo['fecha_fin'], 0, 10);
			$Trabajo['fecha_fin'] = explode('-', $Trabajo['fecha_fin']);
?>
	<tr>
		<td><?=$Cod_Cliente.'-'.$Trabajo['proceso']?></td>
		<td><?=$Trabajo['nombre']?></td>
		<td><?=$Trabajo['fecha_fin'][2].'-'.$Trabajo['fecha_fin'][1].'-'.$Trabajo['fecha_fin'][0]?></td>
		<td><?=$this->fechas_m->minutos_a_hora($Trabajo['tiempo_asignado'])?>h</td>
		<td><?=(isset($ListTiem[$Trabajo['id_pedido']]))?$this->fechas_m->minutos_a_hora($ListTiem[$Trabajo['id_pedido']]):'0:00'?>h</td>
	</tr>
<?
		}
?>
</table>

<br />
<?
	}
}
?>




<br />
<strong>Pedidos Rechazados: <?=count($ListRech)?></strong>

<br />
<table class="tabular" style="width: 100%;">
	<tr>
		<th style="width: 90px;">Proceso</th>
		<th>Trabajo</th>
	</tr>
<?
if(isset($ListRech))
{
	foreach($ListRech as $Trabajo)
	{
?>
	<tr>
		<td><a href="javascript:ventana_externa('/pedidos/pedido_detalle/index/<?=$Trabajo['id_pedido']?>');" class="iconos iexterna toolizq"><span>Ver detalle en Ventana externa</span></a>
		<?=$Trabajo['codigo_cliente'].'-'.$Trabajo['proceso']?></td>
		<td><?=$Trabajo['nombre']?></td>
	</tr>
<?
	}
}
?>
</table>