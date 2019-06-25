<?php
if('mostrar' == $this->generar_cache_m->preparar_cache($Cache))
{
?>

<script type="text/javascript" src="/html/js/jquery.flot.js"></script>
<script type="text/javascript" src="/html/js/jquery.flot.pie.js"></script>
<script type="text/javascript" src="/html/js/venta.js?n=1"></script>

<?php
$Total_Trabajos = array('total' => 0, 'promedio' => 0);
$Total_Rechazos = array('total' => 0, 'promedio' => 0);
$Total_Operadores = 0;
$Total_Programado = 0;
$Total_Utilizado = array('total' => 0, 'promedio' => 0);
$Total_Disponible = array('total' => 0, 'promedio' => 0);
$Total_Extras = 0;
$Productividad = 0;
$Operadores = array();
$Deptos_Select = array();


foreach($Usuarios as $Dpto)
{
	
	
	foreach($Dpto['usuarios'] as $Id_Usuario => $Usuario)
	{
		
		if('n' == $Usuario['programable'] || 'n' == $Dpto['tiempo'])
		{
			continue;
		}
		
		$Deptos_Select[$Dpto['dpto']] = $Dpto['dpto'];
		
		if($Dpto_Mostrar != $Dpto['dpto'] && 'Todos' != $Dpto_Mostrar)
		{
			continue;
		}
		
		if(!isset($Trabajos[$Id_Usuario]))
		{
			continue;
		}
		
		$Operadores[$Id_Usuario]['usuario'] = $Usuario['usuario'];
		$Operadores[$Id_Usuario]['trabajos'] = 0;
		$Operadores[$Id_Usuario]['rechazos'] = 0;
		
		$Total_Operadores++;
		
		
		
		$Total_Trabajos['total'] += $Trabajos[$Id_Usuario];
		
		if(isset($Rechazos[$Id_Usuario]))
		{
			$Total_Rechazos['total'] += $Rechazos[$Id_Usuario];
			if(isset($Trabajos[$Id_Usuario]))
			{
				if(0 < $Trabajos[$Id_Usuario])
				{
					$Operadores[$Id_Usuario]['rechazos'] = ($Rechazos[$Id_Usuario] * 100) / $Trabajos[$Id_Usuario];
					$Operadores[$Id_Usuario]['rechazos'] = number_format($Operadores[$Id_Usuario]['rechazos'], 2);
				}
			}
		}
		
		if(isset($TUtilizado[$Id_Usuario]['habil']['minutos']))
		{
			$Total_Utilizado['total'] += $TUtilizado[$Id_Usuario]['habil']['minutos'];
			$Total_Disponible['total'] += (10560 - $TUtilizado[$Id_Usuario]['habil']['minutos']);
			
			$Operadores[$Id_Usuario]['trabajos'] = (($TUtilizado[$Id_Usuario]['habil']['minutos'] / 60) * 100) / 176;
			if(100 < $Operadores[$Id_Usuario]['trabajos'])
			{
				$Operadores[$Id_Usuario]['trabajos'] = 100;
			}
			$Operadores[$Id_Usuario]['trabajos'] = number_format($Operadores[$Id_Usuario]['trabajos'], 2);
		}
		else
		{
			$Total_Disponible['total'] += 10560;
		}
		
	}
	
	
	if(isset($Extras[$Dpto['dpto']]))
	{
		
		if('n' == $Dpto['tiempo'])
		{
			continue;
		}
		
		if($Dpto_Mostrar != $Dpto['dpto'] && 'Todos' != $Dpto_Mostrar)
		{
			continue;
		}
		
		$Total_Extras += $Extras[$Dpto['dpto']]['horas'];
	}
}



if(0 < $Total_Operadores)
{
	if(0 < $Total_Trabajos['total'])
	{
		$Total_Rechazos['promedio'] = number_format((($Total_Rechazos['total'] * 100) / $Total_Trabajos['total']), 2);
	}
	$Total_Trabajos['promedio'] = ceil($Total_Trabajos['total'] / $Total_Operadores);
	
	
	$Productividad = ((($Total_Utilizado['total'] / $Total_Operadores) / 60) * 100) / 176;
	$Productividad = ($Productividad * 100) / 85;
	$Productividad = number_format($Productividad, 2);
	
	
	$Total_Utilizado['promedio'] = $this->fechas_m->minutos_a_hora(intval($Total_Utilizado['total']  / $Total_Operadores));
	
	$Total_Disponible['promedio'] = intval($Total_Disponible['total'] / $Total_Operadores);
	if($Total_Disponible['promedio'] < 0)
	{
		$Total_Disponible['promedio'] = 0;
	}
	$Total_Disponible['promedio'] = $this->fechas_m->minutos_a_hora($Total_Disponible['promedio']);
	
	
}

?>



<form name="infog_form" id="infog_form" action="/reportes/infografia" method="post" class="no_imprime">
	<select name="dpto_mostrar">
		<option value="Todos">Todos</option>
<?php
foreach($Deptos_Select as $Dpto)
{
?>
		<option value="<?=$Dpto?>"<?=($Dpto_Mostrar==$Dpto)?' selected="selected"':''?>><?=$Dpto?></option>
<?php
}
?>
	</select>
	
	<select name="mes">
<?php
foreach($Meses as $NuMes => $NoMes)
{
?>
		<option value="<?=$NuMes?>"<?=($Mes==$NuMes)?' selected="selected"':''?>><?=$NoMes?></option>
<?php
}
?>
	</select>
	
	<input type="text" name="anho" value="<?=$Anho?>" size="5" />

	<input type="submit" value="Ver Reporte" />
	
	<br /><br />	
	


<strong style="font-size: 19px;"><?=$Dpto_Mostrar?></strong>
<br />

<strong>Informaci&oacute;n General</strong>
<br />

<div class="datos_infografia">
	
	<div id="operador">
		<img src="/html/img/infogr/users.png" />
		&nbsp; <strong class="grande"><?=$Total_Operadores?></strong>
		<br />
		Operadores
		<br />
		<input type="checkbox" id="chk_operador" style="display: none;" />
		<blockquote style="display: none;">
<?php
foreach($Operadores as $Id_Usuario => $Datos)
{/*<a href="/reportes/infografia/puestos/<?=$Id_Usuario?>/<?=$Anho?>/<?=$Mes?>" ><?=$Datos['usuario']?></a><br />*/
?>
			<span class="ver_carga" info="<?=$Id_Usuario?>" usu="<?=$Datos['usuario']?>"><?=$Datos['usuario']?></span><br />
<?php
}
?>
		</blockquote>
	</div>
	
	<!-- **************************************** -->
	<table style="display: none;clear: both;width: 100%; margin: 25px;" id="ver_carga" class="tabular">
		<thead>
			<tr>
				<th colspan="5">CARGA: <span id="ver_carga_usuario"></span> <span id="ver_carga_cerrar">[ x ]</span></th>
			</tr>
			<tr>
				<th>Proceso</th>
				<th>Trabajo</th>
				<th>Ingreso</th>
				<th>Entrega</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<!-- **************************************** -->

	<div id="total_trab">
		<img src="/html/img/infogr/folder.png" />
		&nbsp; <strong class="grande"><?=$Total_Trabajos['promedio']?></strong>
		<br />
		Promedio de Trabajos Realizados
		<br />
		<input type="checkbox" id="chk_total_trab" style="display: none;" />
		<blockquote style="display: none;"><strong>Formula:</strong> Total de Trabajos del departamento (<?=$Total_Trabajos['total']?>) entre el total de Operadores (<?=$Total_Operadores?>).</blockquote>
	</div>
	
	<div id="porc_rech">
		<img src="/html/img/infogr/error.png" />
		&nbsp; <strong class="grande"><?=$Total_Rechazos['promedio']?>%</strong>
		<br />
		Promedio de Rechazos
		<br />
		<input type="checkbox" id="chk_porc_rech" style="display: none;" />
		<blockquote style="display: none;"><strong>Formula:</strong> Total de Rechazos del departamento (<?=$Total_Rechazos['total']?>) contra el total de Trabajos del departamento (<?=$Total_Trabajos['total']?>).</blockquote>
	</div>
	
	<div id="hor_ext">
		<img src="/html/img/infogr/calendar.png" />
		&nbsp; <strong class="grande"><?=number_format($Total_Extras, 2)?></strong>
		<br />
		Horas Extras Realizadas
		<br />
		<input type="checkbox" id="chk_hor_ext" style="display: none;" />
		<blockquote style="display: none;"><strong>Formula:</strong> Sumatoria de las horas extras realizadas por el departamento en el mes.</blockquote>
	</div>
	
	<div id="ind_prod">
		<img src="/html/img/infogr/load_up.png" />
		&nbsp; <strong class="grande">85%</strong>
		<br />
		Meta: Indice Global de Productividad
		<br />
		<input type="checkbox" id="chk_ind_prod" style="display: none;" />
		<blockquote style="display: none;"><strong>Formula:</strong> 85% del total de<br />horas h&aacute;biles en el mes: 176h.</blockquote>
	</div>
	
	<div id="rea_prod">
		<img src="/html/img/infogr/load<?=(100<=intval($Productividad))?'_up':''?>.png" />
		&nbsp; <strong class="grande"><?=$Productividad?>%</strong>
		<br />
		Real: Porcentaje de Productividad
		<br />
		<input type="checkbox" id="chk_rea_prod" style="display: none;" />
		<blockquote style="display: none;"><strong>Formula:</strong> Sumatoria de las Horas utilizadas en el departamento (<?=$this->fechas_m->minutos_a_hora($Total_Utilizado['total'])?>h) entre el total de operadores (<?=$Total_Operadores?>) contra el 85% de las Horas h&aacute;biles del mes (149:36h).</blockquote>
	</div>
	
	<div id="hor_uti">
		<img src="/html/img/infogr/clock.png" />
		&nbsp; <strong class="grande"><?=$Total_Utilizado['promedio']?></strong>
		<br />
		Promedio de Horas Utilizadas
		<br />
		<input type="checkbox" id="chk_hor_uti" style="display: none;" />
		<blockquote style="display: none;"><strong>Formula:</strong> Sumatoria del Total de Horas utilizadas en el departamento (<?=$this->fechas_m->minutos_a_hora($Total_Utilizado['total'])?>h) entre el total de operadores (<?=$Total_Operadores?>).</blockquote>
	</div>
	
	<div id="hor_dis">
		<img src="/html/img/infogr/clock.png" />
		&nbsp; <strong class="grande"><?=$Total_Disponible['promedio']?></strong>
		<br />
		Promedio de Horas Disponibles
		<br />
		<input type="checkbox" id="chk_hor_dis" style="display: none;" />
		<blockquote style="display: none;"><strong>Formula:</strong> Sumatoria del Total de Horas disponibles en el departamento (<?=$this->fechas_m->minutos_a_hora($Total_Disponible['total'])?>h) entre el total de operadores (<?=$Total_Operadores?>).</blockquote>
	</div>
	
	<br style="clear: both;" />
	
</div>

<br /><br />


<?php
if('Todos' != $Dpto_Mostrar and 'Planchas Fotopol&iacute;meras' != $Dpto_Mostrar)
{
?>
<strong>Tiempos y Rechazos</strong>

<br />
<div id="grafico-linea" style="width:780px;height:250px;"></div>
<script language='javascript' type='text/javascript'>
	
	var p =$.plot($('#grafico-linea'),
		[
			{
				data: [[0, 85], [9,85]],
				lines: { show: true },
				points: { show: true },
				label: 'Meta Productividad'
			},
			{
				data: [[null], <?php
$Produc_Porc = array();
$I = 0;
foreach($Operadores as $Datos)
{
	$Produc_Porc[] = '['.$I.'.5,'.$Datos['trabajos'].']';
	$I++;
}
echo implode(', ', $Produc_Porc);
?>, [null]],
				bars: { show: true },
				label: 'Tiempo Utilizado'
			},
			{
				data: [[null], [null]],
				bars: { show: true },
				label: 'Tiempo Utilizado'
			},
			{
				data: [[null], <?php
$Produc_Porc = array();
$I = 1;
foreach($Operadores as $Datos)
{
	$Produc_Porc[] = '['.$I.','.$Datos['rechazos'].']';
	$I++;
}
echo implode(', ', $Produc_Porc);
?>, [null]],
				lines: { show: true },
				points: { show: true },
				label: 'Rechazos'
			}
		],
		{
			xaxis:
			{
				min: 0,
				ticks:
				[
					[0, ""],
					<?php
$Produc_Porc = array();
$I = 1;
foreach($Operadores as $Datos)
{
	$Produc_Porc[] = '['.$I.', "'.$Datos['usuario'].'"]';
	$I++;
}
echo implode(', ', $Produc_Porc);
?>,
					[8, ""], [9, ""]
				],
				max: 9
			},
			yaxis:
			{
				min: 0,
				max: 100,
				tickSize: 25
			}
		}
	);
	
	
	var total_barras = p.getData();
	total_barras = total_barras.length;
	for(z = 0; z < total_barras; z++)
	{
		$.each(p.getData()[z].data, function(i, el)
		{
			if('' != el)
			{
				var o = p.pointOffset({x: el[0], y: el[1]});
				var mas = '';
				if(100 == el[1])
				{
					mas = '+';
				}
				$('<div class="data-point-label">' + el[1] + mas + '%</div>').css(
				{
					position: 'absolute',
					left: o.left + 4,
					top: o.top - 17,
					display: 'none',
					"font-size": "11px"
				}).appendTo(p.getPlaceholder()).fadeIn('slow');
			}
		});
	}
	
</script>
<?php

}
//print_r($Planchas);
if('Planchas Fotopol&iacute;meras' == $Dpto_Mostrar)
{
?>
<select name='icli' id='icli'>
	<option value='todos' <?=('todos'==$ICliente)? 'selected = "selected"':''?>>Todos</option>
<?php
foreach($ClientesPlanchas as $Datos)
{
?>
	<option value='<?=$Datos['id_cliente']?>' <?=($ICliente==$Datos['id_cliente'])?' selected="selected"':''?>><?=$Datos['codigo_cliente']?> - <?=$Datos['nombre']?></option>
<?php
}
?>
</select>
</form>
<br />
<strong>REPORTE MENSUAL</strong>

<br />

<div id="grafico-actual" style="width:925px;height:325px;"></div>

<div class="limpiar"></div><br />

<script language='javascript' type='text/javascript'>
		
		var pulgas = [];
		var coti = [];
		var info = '';
<?php
$mayor = 0;
for($a =1; $a <=31; $a++)
{
	if($a<10)
	{
		$a = '0'.$a;
	}
	
	if(isset($Planchas[$a]['totalR']))
	{
		if($mayor < $Planchas[$a]['totalR'])
		{
			$mayor = $Planchas[$a]['totalR'];
		}
?>
		pulgas.push([<?=($a)?>,<?=$Planchas[$a]['totalR']?>]),
<?php
	}
	
	if(!isset($Planchas[$a]['totalR']))
	{
?>
		pulgas.push([<?=($a)?>,0]),
<?php
	}
	
	
	if(isset($Planchas[$a]['totalC']))
	{
		if($mayor < $Planchas[$a]['totalC'])
		{
			$mayor = $Planchas[$a]['totalC'];
		}
?>
		coti.push([<?=($a)?>,<?=$Planchas[$a]['totalC']?>]),
<?php
	}
	
	if(!isset($Planchas[$a]['totalC']))
	{
?>
		coti.push([<?=($a)?>,0]),
<?php
	}
}

?>
		info = $.plot($('#grafico-actual'),
			[
				{
				data: [[0, 22400], [32,22400]],
				lines: { show: true },
				points: { show: true },
				label: 'Meta Cumplimiento <strong>36,300 IN2 DIARIAS</strong>'
				},
				{
					data: pulgas,
					points: { show: true },
					label: 'Pulgadas Consumidas'
				},
				{
					data: coti,
					points: { show: true },
					label: 'Pulgadas Cotizadas'
				}
			],
			{
				xaxis:
				{
					min: 0,
					ticks:
					[
						[0, ""],
						<?php
						for($a =1; $a < 31; $a++)
						{
							$url = '<a href="/reportes/detalle_planchas/index/'.$Anho.'/'.$Mes.'/'.$a.'" target="_blank">'.$a.'</a>';
						?>
							[<?=$a?>, <?=('todos'!=$ICliente)?"'$url'":$a?>],
						<?php
						}
						?>
						[31, 31]
					],
					max: 32
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
					max: <?=($mayor<36300)?40000:$mayor+2000?>
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
					$('<div class="data-point-label">' + formatNumber(el[1], '') + ' in2</div>').css(
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
					showTooltip(item.pageX, item.pageY, formatNumber(y, '') + ' in2');
				}
			}
			else
			{
				$("#tooltip").remove();
				previousPoint = null;
			}
		});
</script>

<strong>REPORTE ANUAL</strong>
<div id="grafico-actual2" style="width:925px;height:325px;"></div>

<script language='javascript' type='text/javascript'>
		
		var pulgas = [];
		var coti = [];
		var info = '';
<?php
$mayor = 0;
for($a =1; $a <=31; $a++)
{
	if($a<10)
	{
		$a = '0'.$a;
	}
	
	if(isset($PlanchasAn[$a]))
	{
		if($mayor < $PlanchasAn[$a]['totalR'])
		{
			$mayor = $PlanchasAn[$a]['totalR'];
		}
?>
		pulgas.push([<?=($a)?>,<?=$PlanchasAn[$a]['totalR']?>]),
<?php
	}
	
	if(!isset($PlanchasAn[$a]))
	{
?>
	pulgas.push([<?=($a)?>,0]),
<?php
	}
	
	
	if(isset($PlanchasAn[$a]))
	{
		if($mayor < $PlanchasAn[$a]['totalC'])
		{
			$mayor = $PlanchasAn[$a]['totalC'];
		}
?>
		coti.push([<?=($a)?>,<?=$PlanchasAn[$a]['totalC']?>]),
<?php
	}
	
	if(!isset($PlanchasAn[$a]))
	{
?>
	coti.push([<?=($a)?>,0]),
<?php
	}
}

?>
		info = $.plot($('#grafico-actual2'),
			[
				{
				data: [[0, 672000], [13,672000]],
				lines: { show: true },
				points: { show: true },
				label: '<strong>Meta Cumplimiento <strong>672,000 IN2</strong>',
				},
				{
					data: pulgas,
					points: { show: true },
					label: 'Pulgadas Consumidas'
				},
				{
					data: coti,
					points: { show: true },
					label: 'Pulgadas Cotizadas'
				}
			],
			{
				xaxis:
				{
					min: 0,
					ticks:
					[
						[0, ""],
						<?php
						for($a =1; $a < 13; $a++)
						{

								if($a < 10)
								{
									$a = '0'.$a;
								}
							?>
								[<?=$a?>, "<?=$Meses[$a]?>"],
							<?php
						}
						?>
						[31, 31]
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
					max: 800000,
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
					$('<div class="data-point-label">' + formatNumber(el[1], '') + ' in2</div>').css(
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
		$("#grafico-actual2").bind("plothover", function (event, pos, item)
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
					showTooltip(item.pageX, item.pageY, formatNumber(y, '')+' in2');
				}
			}
			else
			{
				$("#tooltip").remove();
				previousPoint = null;
			}
		});
	</script>

</div>
<?php
}
?>


<style>
	.datos_infografia div{
		text-align: center;
		float: left;
		width: 270px;
		padding: 10px 5px;
		margin: 5px;
		background: #f9f9f9;
		border: 2px solid #979797;
		-moz-border-radius: 5px; -khtml-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px;
	}
	.grande{
		font-size: 25px;
	}
	blockquote{
		border-top: 1px solid #8f8f8f;
		margin-top: 10px;
	}
</style>

<style media="print">
	.datos_infografia div{
		width: 220px;
	}
</style>

<script>
	$(function()
	{
		$('.datos_infografia div').click(function()
		{
			if(!$('#chk_'+$(this).attr('id')).attr('checked'))
			{
				$('#'+$(this).attr('id')+' blockquote').show('blind');
				$('#chk_'+$(this).attr('id')).attr('checked', true);
			}
			else
			{
				$('#'+$(this).attr('id')+' blockquote').hide('blind');
				$('#chk_'+$(this).attr('id')).attr('checked', false)
			}
		});
		
		$("#icli").change(function(){
			$('#infog_form').submit();
		});
	});

	$('.ver_carga').click(function()
	{
		
		$('#ver_carga tbody').empty();
		$('#ver_carga_usuario').empty().append($(this).attr('usu'));
		$('#ver_carga').show();
		var iUsu = $(this).attr('info');
		
		$.ajax({
			type: "POST",
			url: "/carga/trabajos/listar/"+iUsu,
			success: function(msg)
			{
				var Informacion = JSON.parse(msg);

				var Fila = '';

				for(i in Informacion)
				{
					Fila = '<tr>';
					Fila = Fila + '<td>';
					Fila = Fila + '<a href="/pedidos/especificacion/ver/'+Informacion[i].id_pedido+'/n" target="_blank" class="iconos idocumento toolizq"><span>Hoja de Planificaci&oacute;n</span></a>';
					Fila = Fila + '<strong><a href="/pedidos/pedido_detalle/index/'+Informacion[i].id_pedido+'" target="_blank">';
					Fila = Fila + Informacion[i].codigo_cliente+'-'+Informacion[i].proceso;
					Fila = Fila + '</a></strong></td>';
					Fila = Fila + '<td>'+Informacion[i].nombre+'</td>';
					Fila = Fila + '<td>'+Informacion[i].fecha_entrada+'</td>';
					Fila = Fila + '<td>'+Informacion[i].fecha_entrega+'</td>';
					Fila = Fila + '</tr>';

					$('#ver_carga tbody').append(Fila);
				}


			},
			error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
		});
		
	});

	$('#ver_carga_cerrar').click(function()
	{
		$('#ver_carga').hide();
	});
</script>

<?php
	$this->generar_cache_m->generar_cache($Cache);
}

?>








<style type="text/css">
@import url(https://fonts.googleapis.com/css?family=Lato:700);

*,
*:before,
*:after {
  box-sizing: border-box;
}
.set-size {
  font-size: 10em;
}
.charts-container:after {
  clear: both;
  content: "";
  display: table;
}
.pie-wrapper {
  height: 1em;
  width: 1em;
  float: left;
  margin: 15px;
  position: relative;
}
.pie-wrapper:nth-child(3n+1) {
  clear: both;
}
.pie-wrapper .pie {
  height: 100%;
  width: 100%;
  clip: rect(0, 1em, 1em, 0.5em);
  left: 0;
  position: absolute;
  top: 0;
}
.pie-wrapper .pie .half-circle {
  height: 100%;
  width: 100%;
  border: 0.13em solid #3498db;
  border-radius: 50%;
  clip: rect(0, 0.5em, 1em, 0);
  left: 0;
  position: absolute;
  top: 0;
}
.pie-wrapper .label {
  background: #93a7bc;
  border-radius: 50%;
  bottom: 0.4em;
  color: #2a3138;
  cursor: default;
  display: block;
  font-size: 0.25em;
  left: 0.4em;
  line-height: 2.6em;
  position: absolute;
  right: 0.4em;
  text-align: center;
  top: 0.4em;
}
.pie-wrapper .label .smaller {
  color: #2a3138;
  font-size: .45em;
  padding-bottom: 20px;
  vertical-align: super;
}
.pie-wrapper .shadow {
  height: 100%;
  width: 100%;
  border: 0.1em solid #bdc3c7;
  border-radius: 50%;
}
/*.pie-wrapper.progress-90 .pie {
  clip: rect(auto, auto, auto, auto);
}*/
.pie-wrapper.progress-90 .pie .half-circle {
  border-color: #34495e;
}
.pie-wrapper.progress-90 .pie .right-side {
  display: none;
  /*-webkit-transform: rotate(108deg);
          transform: rotate(108deg);*/
}
.pie-wrapper.progress-90 .pie .left-side {
	-webkit-transform: rotate(180deg);
          transform: rotate(180deg);
}
</style>


<!--div class="set-size charts-container">
	<div class="pie-wrapper progress-90">
		<span class="label">90<span class="smaller">%</span></span>
		<div class="pie">
			<div class="left-side half-circle"></div>
			<div class="right-side half-circle"></div>
		</div>
	</div>
</div>

<br /><br /><br /-->




