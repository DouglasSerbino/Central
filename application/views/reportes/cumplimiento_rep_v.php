<?php
if('mostrar' == $this->generar_cache_m->preparar_cache($Cache))
{
$meses_v = array('01' => 'ENERO', '02' => 'FEBRERO', '03' => 'MARZO', '04' => 'ABRIL', '05' => 'MAYO', '06' => 'JUNIO', '07' => 'JULIO', '08' => 'AGOSTO', '09' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
	<head>
		<title>REPORTE GERENCIAL</title>
		<link rel="shortcut icon" href="/html/img/ico-cg.png" />
		<script type="text/javascript" src="/html/js/jquery-1.7.1.js"></script>
		<script type="text/javascript" src="/html/js/jquery-ui-1.8.17.js"></script>
		<script type="text/javascript" src="/html/js/acciones.js?n=1"></script>
		<script type="text/javascript" src="/html/js/jquery.flot.js"></script>
		<script type="text/javascript" src="/html/js/jquery.flot.pie.js"></script>
		<script type="text/javascript" src="/html/js/venta.js?n=1"></script>
		<link rel="stylesheet" href="/html/css/reporte_gerencial.css" />
		<link rel="stylesheet" href="/html/css/extra.css" />
		<link rel="stylesheet" href="/html/css/estilo.002.css" />
		<style>
			label{
				font-size: 13px;
			}
			strong
			{
				font-size: 14px;
			}
			
			#titulo{
				position: absolute;
				font-size: 20px;
				margin-top: 8px;
				margin-left: 420px;
				color: #000000;
			}
		</style>
	</head>
	<body>
		<div id="encabezado" class='soy_encabezado'><img src="/html/img/lgtps/<?=$this->session->userdata('grupo')?>_blanco.png" width="115" alt="<?=$this->session->userdata('grupo')?>" /></div>
		
		<div id="contenedor" >
			<div id="titulo"><center>REPORTE GERENCIAL <?=($cod_cliente== 'gen')?' GENERAL':'<br >'.$Cliente['nombre'].' - '.$meses_v[$mes].'('.$anho.')'?></center></div>
			<br /><br />
<?

$Info = array();
for($a= 1; $a <= 12; $a++)
{
	$Ttrabajos = 0;
	$puntuales = 0;
	$atrasados = 0;
	$reprocesos = 0;
	if($a < 10)
	{
		$a = '0'.$a;
	}
	if(isset($Porcentajes[$a]['pedidos_tot']))
	{
		$Ttrabajos = $Porcentajes[$a]["pedidos_tot"];
	}
	
	if(isset($Porcentajes[$a]['pedidos_tie']))
	{
		$puntuales = $Porcentajes[$a]["pedidos_tie"];
	}
	
	if(isset($Porcentajes[$a]["pedidos_atra"]))
	{
		$atrasados = $Porcentajes[$a]["pedidos_atra"];
	}
	
	if(isset($Porcentajes[$a]["pedidos_rep"]))
	{
		$reprocesos = $Porcentajes[$a]["pedidos_rep"];
	}

	$Info['entregas_tiempo'][$a]['tiempo_1'] = 0;
	$Info['entregas_atrasadas'][$a]['atrasadas_1'] = 0;
	$Info['entregas_reprocesos'][$a]['reprocesos_1'] = 0;
	if($Ttrabajos > 0)
	{
		//Calcular el porcentaje de los trabajos a tiempo, reprocesos y atrasados.
		$Info['entregas_tiempo'][$a]['tiempo_1'] = round((($puntuales / $Ttrabajos) * 100), 0, PHP_ROUND_HALF_DOWN);
		$Info['entregas_atrasadas'][$a]['atrasadas_1'] = round((($atrasados / $Ttrabajos) * 100), 0, PHP_ROUND_HALF_DOWN);
		$Info['entregas_reprocesos'][$a]['reprocesos_1'] = (0==$reprocesos)?0:(round((($reprocesos / $Ttrabajos) * 100), 0, PHP_ROUND_HALF_DOWN));
	}
}
?>
		<br /><br />
		
		<table style="width:900px;">
			<tr>
				<td style='width: 250px;'>
					<table style='width: 350px; background: #77a22f; color: #ffffff;'>
						<tr>
							<td>
								<strong style="font-size: 14px;">TRABAJOS DESARROLLADOS: <?=(isset($Porcentajes[$mes]["pedidos_tot"]))?$Porcentajes[$mes]["pedidos_tot"]:0?></strong>
							<br />
						<?php
							$Item = array(1 => 'Total de entregas', 2 => 'Entregas a Tiempo', 3 => 'Entregas Atrasadas', 4 => 'Reproceso');
							$Tipo = array(1 => 'tot', 2 => 'tie', 3 => 'atr', 4 => 'rep');
							$TPedido = array(1 => 'pedidos_tot', 2 => 'pedidos_tie', 3 => 'pedidos_atra', 4 => 'pedidos_rep');
							
							for($a = 1; $a <= 4; $a++)
							{
							?>
								<a class="ico_blanco toolizq" href="/reportes/cumplimiento_rep_ent/index/<?=$Tipo[$a]?><?=$enviar?>/<?=$anho?>/<?=$mes?>" target='_blank'>
								<span><?=$Item[$a]?></span></a>&nbsp;
								<label><?=$Item[$a]?>:</label>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<strong><?=(isset($Porcentajes[$mes][$TPedido[$a]]))?$Porcentajes[$mes][$TPedido[$a]]:0?></strong><br>
							<?php
							}
						?>
							</td>
						</tr>
					</table>
					<div style='height: 5px;'></div>
					<table style='width: 350px; background: #e6e6e6;'>
						<tr style='background: #fdb930;'>
							<td colspan='2'>
								<center><strong>DETALLE DE TRABAJOS</strong></center>
							</td>
						</tr>
						<?
						foreach($Trabajos_finales as $Datos)
						{
							$id = $Datos['id_matsol'];
							if($id == 20)
							{
								$Material = 'Prueba de Color';
							}
							else
							{
								$Material = $Datos['matsol'];
							}
							?>
						<tr style='background: #e6e6e6;'>
							<td colspan='2'><label><?=$Material?>: <strong><?=$Datos['tped']?></strong></label></td>
						</tr>
						<?php
						}
				?>
						<tr style='background: #fff;'>
							<td colspan='2' style='height: 5px;'></td>
						</tr>
						<tr style='background: #fdb930;'>
							<td colspan='2'>
								<center><strong>PROMEDIOS</strong></center>
							</td>
						</tr>
						<tr>
							<td>Promedio Planificaci&oacute;n:</td>
							<td style='width: 100px;'><strong><?=$tiempos['Plani']?> </strong></td>
						</tr>
						<tr>
							<td>Promedio Entrega Artes:</td>
							<td><strong><?=$tiempos['Arte']?> d&iacute;as</strong></td>
						</tr>
						<tr>
							<td>Promedio Tiempo Aprobaci&oacute;n:</td>
							<td><strong><?=$tiempos['Aprobacion']?> d&iacute;as</strong></td>
						</tr>
						<tr>
							<td style='width: 150px;'>Promedio entrega Elementos Finales:</td>
							<td><strong><?=$tiempos['Final']?> d&iacute;as</strong></td>
						</tr>
					</table>
					
				</td>
				<td style='width: 550px;'>
				
<?php
if(30 < $Info['entregas_atrasadas'][$mes]['atrasadas_1'])
{
	echo '<br>';
}
?>
					<div id="grafico-pastel" style="width:450px;height:400px; margin-left: 0px; position: absolute;"></div><script language='javascript' type='text/javascript'>
					$.plot($('#grafico-pastel'), [{ label: 'Puntual',  data: [[1,<?=$Info['entregas_tiempo'][$mes]['tiempo_1']?>]] }, { label: 'Atrasados',  data: [[1,<?=$Info['entregas_atrasadas'][$mes]['atrasadas_1']?>]] }], { series:{ pie:{ show: true } }, legend:{ show: false} });
					$('#pieLabel0 div').css({'font-size':'22px','marginLeft':'-30px', 'border':'none'});
					$('#pieLabel1 div').css({'font-size':'16px','marginLeft':'20px', 'border':'none'});
					</script>
				</td>
				<td style='width: 220px; color: rgb(203,75,75); text-align: right; font-weight: bold; font-size: 14px;'>
					<div style='width: 100px; float: right; margin-top: 160px; margin-right: 0px;'>
						<label style='margin-left: 100px;'><?=$Info['entregas_reprocesos'][$mes]['reprocesos_1']?>%<br></label>
						<div style='width: 50px; margin-left: 90px; bottom: 20px;margin-right: 10px; height: <?=$Info['entregas_reprocesos'][$mes]['reprocesos_1']?>px; background: rgb(203,75,75);'></div>
						<label style='margin-left: 70px;'>Reprocesos<br></label>
					</div>
				</td>
			</tr>
		</table>
	
	<br /><br /><br />
	<strong>REPORTE ANUAL DE CUMPLIMIENTO</strong>
	<div id="corte_pagina"></div>
	<div id="grafico-linea" style="width:925px;height:325px;"></div>
	<script language='javascript' type='text/javascript'>
		var entregas_tiempo = [];
		var entregas_atrasadas = [];
		var reprocesos = [];
		var info = '';
<?php
foreach($Info['entregas_tiempo'] as $mes => $informacion)
{
?>
		entregas_tiempo.push([<?=($mes+0)?>,<?=(isset($mes))?$informacion['tiempo_1']:0?>]),
<?
}

foreach($Info['entregas_atrasadas'] as $mes => $informacion)
{
?>
		entregas_atrasadas.push([<?=($mes+0)?>,<?=(isset($mes))?$informacion['atrasadas_1']:0?>]),
<?
}
foreach($Info['entregas_reprocesos'] as $mes => $informacion)
{
?>
		reprocesos.push([<?=($mes+0)?>,<?=(isset($mes))?$informacion['reprocesos_1']:0?>]),
<?
}
?>
		info = $.plot($('#grafico-linea'),
			[
				{
				data: [[0, 95], [13,95]],
				lines: { show: true },
				points: { show: true },
				label: 'Meta Cumplimiento <strong>95%</strong>'
				},
				{
					data: entregas_tiempo,
					points: { show: true },
					label: 'Porcentaje Entregas a Tiempo'
				},
				{
					data: entregas_atrasadas,
					points: { show: true },
					label: 'Porcentaje de Entregas a Atrasadas'
				},
				{
					data: [[0, 5], [13, 5]],
					points: { show: true },
					label: 'Porcentaje de Reprocesos'
				},
				{
					data: reprocesos,
					points: { show: true },
					label: 'Reprocesos'
				},
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
					max: 100
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
					$('<div class="data-point-label">' + el[1] + mas + '%</div>').css(
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
		
		function OcuMos(idtr)
		{
			$('.tr_escondido'+idtr).toggle();
			$('.exp_col'+idtr+' span').toggle();
		};
		
	</script>
	</div>
	</body>
</html>

		<br style="clear: both;" />

<?php
	$this->generar_cache_m->generar_cache($Cache);
}
?>
