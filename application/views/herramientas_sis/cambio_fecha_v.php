<script type="text/javascript" src="/html/js/jquery.flot.js"></script>
<script type="text/javascript" src="/html/js/jquery.flot.pie.js"></script>
<script>
		function enviar(form)
		{
				$('#'+form).submit();
				
		}
		function eliminar_cambio(proceso, cliente, tiempo)
		{
				if(confirm("Desea Eliminar?"))
				{
						var datos = 'proceso='+proceso;
						datos += '&cliente='+cliente;
						datos += '&tiempo='+tiempo;
						
						$.ajax({
							type: "POST",
							url: "/herramientas_sis/cambio_fecha/eliminar_cambio/",
							data: datos,
							success: function(msg)
							{
								if(msg == "ok")
								{
									window.location = '/herramientas_sis/cambio_fecha/';
								}
								else alert("Ocurrio un error: "+msg+".<br />Haga una captura de pantalla para realizar una verificacion del problema.");
							},
							error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
						});
				}
		}
</script>
<form method='post' action='/herramientas_sis/cambio_fecha/index'>
<select name='mes'>
<?php
		foreach($Meses as $a => $Datos)
		{
?>
		<option value='<?=$a?>' <?=($a==$mes)?' selected="selected"':''?>><?=$Datos?></option>
<?php
		}
?>
</select>
		<input type='text' name='anho' value='<?=$anho?>' style='width: 40px;'>
		<input type='submit' value='Enviar'>
</form>
<br />
<?php
foreach($Cambios as $a => $Datos2)
{
		if($a == 'si')
		{
?>
		<strong>Activos</strong>
		<table class='tabular' style='width: 80%;'>
				<tr>
						<th>#</th>
						<th style='width: 17%;'>Proceso</th>
						<th>Planificador</th>
						<th>Nueva Fecha</th>
						<th>Solicita</th>
						<th>Motivo</th>
						<th>Opcion</th>
				</tr>
				
<?php
		}
		else
		{
?>
		<br /><br /><strong>Inactivos</strong>
		<table class='tabular' style='width: 80%;'>
				<tr>
					<th>#</th>
						<th style='width: 17%;'>Proceso</th>
						<th>Planificador</th>
						<th>Nueva Fecha</th>
						<th>Solicita</th>
						<th>Motivo</th>
						<th></th>
				</tr>				
<?php
		}
		$e = 0;
		$conta = 1;
		//print_r($Datos2);
		foreach($Datos2 as $Datos)
		{
?>
				<tr>
					<td><?=$conta?></td>
						<td><?=$Datos['cod_cliente']?>-<?=$Datos['proceso']?></td>
						<td><?=$Datos['usuario']?></td>
						<td><?=$this->fechas_m->fecha_ymd_dmy($Datos['fecha'])?></td>
						<td><?=$Datos['solicita']?></td>
						<td><?=$Datos['opcion']?></td>
<?php
		if($a == 'si')
		{
				
?>
				<form id='form<?=$e?>' method='post' action='/pedidos/tiempo/accion/fecha/<?=$Datos['id_pedido']?>'>
						<input type='hidden' name='fecha_entrega' value='<?=$this->fechas_m->fecha_ymd_dmy($Datos['fecha'])?>'>
						<input type='hidden' name='quien_solicita' value='<?=$Datos['solicita']?>'>
						<input type='hidden' name='justifica_fecha' value='<?=$Datos['opcion']?>'>
						<input type='hidden' name='fecha_anterior' value='<?=$Datos['fecha_anterior']?>'>
						<input type='hidden' name='cliente' value='<?=$Datos['cod_cliente']?>'>
						<input type='hidden' name='proceso' value='<?=$Datos['proceso']?>'>
						<input type='hidden' name='cambio_fecha' value='si'>
						<td>
								<a onclick="enviar('form<?=$e?>')" class="iconos iterminado toolder"><span>Aprobar cambio</span></a>
								<a onclick="eliminar_cambio('<?=$Datos['proceso']?>', '<?=$Datos['cod_cliente']?>', '<?=$Datos['anho_mes']?>')" class="iconos ieliminar toolder"><span>Eliminar Peticion</span></a>
						</td>
				</form>
<?php
		$e++;
		}
?>
				</tr>
<?php
		$conta++;
		}
?>
		</table>
<?php
}

?>


<br />
<div id="grafico" style="width:925px;height:325px;"></div>

</div>

	<script language='javascript' type='text/javascript'>
		
		var venta_mensual = [];
<?php
$Maximo_Valor = 100;
if(0 < count($Grafica))
{
		$Maximo_Valor = max($Grafica) + 50;
		$a = 1;
	foreach($Grafica as $informacion)
	{
?>
		venta_mensual.push([<?=($a+0)?>,<?=$informacion?>]),
<?
$a++;
	}
}
?>
		info = $.plot($('#grafico'),
			[
				{
					data: venta_mensual,
					points: { show: true },
					label: 'Cambios por Mes'
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
					$('<div class="data-point-label">' + formatNumber(el[1], '') + '</div>').css(
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
		$("#grafico").bind("plothover", function (event, pos, item)
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
					showTooltip(item.pageX, item.pageY, formatNumber(y, ''));
				}
			}
			else
			{
				$("#tooltip").remove();
				previousPoint = null;
			}
		});
		
	</script>