<?php
if('mostrar' == $this->generar_cache_m->preparar_cache($Cache))
{
?>
<script type="text/javascript" src="/html/js/venta.js?n=1"></script>

<select name="mes" id="mes" onchange="ir_pagven5()">
<?php
foreach($meses_v as $iMes => $nMes)
{
?>
		<option value="<?=$iMes?>"<?=($mes==$iMes)?' selected="selected"':''?>><?=$nMes?></option>
<?php													
}
?>
	</select> &nbsp;
	
	<select name="anho" id="anho" onchange="ir_pagven5()">
<?php
$Fin = date('Y') + 2;
for($i = 2009; $i < $Fin; $i++)
{
?>
		<option value="<?=$i?>"<?=($anho == $i)?' selected="selected"':''?>><?=$i?></option>
<?php
}
?>
	</select>
	<br />

<script type="text/javascript" src="/html/js/jquery.flot.js"></script>

<div id="venta" class='ancho' style="width:930px;height:265px;"></div>
		
	<script>
		var p = $.plot(
			$("#venta"),
			[
				{ data:[] },
				{ data: [
					[0.5,<?=$Proyecciones?>],
					[1.5,<?=$Pendiente_facturar?>],
					[2.5,<?=($Pendiente_facturar + $Ventas_acumuladas)?>],
					[3.5,<?=$Ventas_acumuladas?>]],
					bars: { show: true }
				}
			],
			{
				xaxis:
				{
					min: 0,
					ticks:[
						[0, ""],[1,"PROYECCION"], [2,"PROCESO"], 
						[3,"ACUMULADO"], [4,"REAL"]
					],
					max:5
				},
				yaxis:
				{
					min:0/*, max: <?=($Maximo + 2000)?>*/
				}
			}
		);
		
		var total_barras = p.getData();
		total_barras = total_barras.length;
		for(z = 0; z < total_barras; z++)
		{
			$.each(p.getData()[z].data, function(i, el)
			{
				var o = p.pointOffset({x: el[0], y: el[1]});
				$('<div class="data-point-label">' + formatNumber(el[1], '$') + '</div>').css(
				{
					position: 'absolute',
					left: o.left + 4,
					top: o.top - 17,
					display: 'none',
					"font-size": "11px"
				}).appendTo(p.getPlaceholder()).fadeIn('slow');
			});
		}
		
	</script>

<?php
	$this->generar_cache_m->generar_cache($Cache);
}
