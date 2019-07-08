<script type="text/javascript" src="/html/js/plancha.js?n=1"></script>
<script type="text/javascript" src="/html/js/venta.js?n=1"></script>
<script type="text/javascript" src="/html/js/jquery.flot.js"></script>
<script type="text/javascript" src="/html/js/jquery.flot.pie.js"></script>

<?php
$inicio = 2009;
$leyenda = 'Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre,Octubre,Noviembre,Diciembre';

$datos = explode(',',$grafico_plancha);
$Mayor = max($datos);
$Menor = min($datos);
$titulos = explode(',', $leyenda);
?>
<div class="informacion">
	
	<select name="cod_plancha" id="cod_plancha">
		<option value="todo">Todos</option>
<?php
foreach($planchas as $Datos)
{
?>
		<option value="<?=$Datos["cod_plancha"]?>"<?=($Datos["cod_plancha"] == $cod_plancha)?' selected="selected"':''?>><?=$Datos["grosor"].' - '.$Datos["tipo"]?></option>
<?php	
}
?>
	</select>
	<input type='text' name="anho" id="anho" value='<?=$anho?>' size='6'>

	<input type="button" value="Ver Gr&aacute;fico" class="boton" onclick="plancha_grafico()" /><br />
	
		<div id="venta" style="width:800px;height:225px;"></div>
		
	<script>
		var p = $.plot(
			$("#venta"),
			[
<?php
$a = 0.5;
foreach($datos as $Datos)
{
?>
				{ data: [[<?=$a.','.$Datos?>]], bars: { show: true } },
<?php
$a = $a + 1;
}
?>
			],
			{
				xaxis:
				{
					min: 0,
					ticks:[
<?php
	$a = 1;
	foreach($titulos as $datos)
	{
		echo '['.$a.',"'.$datos.'"],';
		$a++;
	}
?>
					],
					max: 12
				},
				yaxis:
				{
					min:0, max: <?=($Mayor+500)?>
				}
			}
		);
		//, tickSize: <?=floor($Mayor/4)?>
		
		var total_barras = p.getData();
		total_barras = total_barras.length;
		for(z = 0; z < total_barras; z++)
		{
			$.each(p.getData()[z].data, function(i, el)
			{
				var o = p.pointOffset({x: el[0], y: el[1]});
				$('<div class="data-point-label">' + (el[1] + ' IN') + '</div>').css(
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
</div>
<?php
/*
if($grafico_plancha == "0,0,0,0,0,0,0,0,0,0,0,0" || $grafico_plancha == ",,,,,,,,,,,")
	echo "<strong>No hay datos contabilizados para este material o periodo.</strong>";
else
{
?>
		<object type="application/x-shockwave-flash" data="/html/img/grafico_barra.swf" width="785px" height="221px">
			<param name="FlashVars" value="datos=0,<?=$grafico_plancha?>&nombres=0,<?=$leyenda?>" />
			<param name="movie" value="/html/img/grafico_barra.swf" />
		</object>
<?php
}
*/
?>	
</div>
