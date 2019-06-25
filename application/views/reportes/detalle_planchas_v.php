<?php
//print_r($Planchas);

if(0 == count($Planchas))
{
	echo '<h1>No hay consumo este dia</h1>'; 
}
else
{
	//print_r($Planchas[$Dia]['clientes']);
?>
<script type="text/javascript" src="/html/js/jquery.flot.js"></script>
<script type="text/javascript" src="/html/js/jquery.flot.pie.js"></script>
<strong><h1><?=$Dia?>-<?=$Mes?>-<?=$Anho?></h1></strong>
<?php
$TClientes = count($Planchas[$Dia]['clientes']);
?>
<div id="grafico-linea" style="width:<?=((($TClientes*2)*75)>250)?(($TClientes*2)*80):450?>px;height:250px; left: -100px;"></div>
<script language='javascript' type='text/javascript'>
	
	var p =$.plot($('#grafico-linea'),
		[
			{
				data: [[null], <?php
$Produc_Porc = array();
$I = 0;
$mayor = 0;
foreach($Planchas[$Dia]['clientes'] as $Datos)
{
	if($mayor < $Datos['real'])
	{
		$mayor = $Datos['real'];
	}
	
	$Produc_Porc[] = '['.$I.'.5,'.$Datos['real'].']';
	$I+=2;
}
echo implode(', ', $Produc_Porc);
?>, [null]],
				bars: { show: true },
				label: 'Pulgadas Utilizadas',
			},
			{
				data: [[null], <?php
$Produc_Porc = array();
$I = 1;
$mayor2 = 0;
foreach($Planchas[$Dia]['clientes'] as $Datos)
{
	if($mayor2 < $Datos['cotizado'])
	{
		$mayor2 = $Datos['cotizado'];
	}
	$Produc_Porc[] = '['.$I.'.5,'.(isset($Datos['cotizado'])?$Datos['cotizado']:0).']';
	$I+=2;
}
echo implode(', ', $Produc_Porc);
?>, [null]],
				bars: { show: true },
				label: ' Pulgadas Cotizadas'
			}
		],
		{
			xaxis:
			{
				min: 0,
				ticks:
				[
					<?php
$Produc_Porc = array();
$I = 1.5;
foreach($Planchas[$Dia]['clientes'] as $Datos)
{
	$Produc_Porc[] = '['.$I.', "'.$Datos['nomcliente'].'"]';
	$I+=2;
}
echo implode(', ', $Produc_Porc);
?>
				],
				max: <?=($TClientes*2)+1?>
			},
			yaxis:
			{
				min: 0,
				max: <?=($mayor<$mayor2)?$mayor2+4000:$mayor+4000?>
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
				$('<div class="data-point-label">' + formatNumber(el[1], '') + mas + ' in2</div>').css(
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
?>