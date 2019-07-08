
<select id="bsc_objetivo">
<?php
foreach($Def_Objetivos as $Id_Perspectiva => $Perspectiva)
{
?>
	<optgroup label="<?=$Perspectiva['Nom']?>">
<?php
	foreach($Perspectiva['Objs'] as $Index => $Objetivo)
	{
?>
		<option value="<?=$Index?>"<?=($Id_Bsc_Objetivo==$Index)?' selected="selected"':''?>><?=$Objetivo['Nom']?> [<?=$Objetivo['Con']?>]</option>
<?php
	}
?>
	</optgroup>
<?php
}
?>
</select>

<input type="text" value="<?=$Anho?>" id="bsc_anho" size="5" />

<input type="button" value="Ver Gr&aacute;fica" onclick="window.location='/balance/grafica/index/'+$('#bsc_objetivo').val()+'/'+$('#bsc_anho').val()" />





<div id="grafico1"></div>
<script>
	$('#grafico1').gbarras(
	{
		'ymaximo': <?=$Maximo_Valor?>,
		'barras': [<?=implode(',', $Cantidad_Barras)?>],
		'xleyenda': ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		'series': ['Proyectado','Real']
	});
</script>



<br />
<table class="tabular" style="width: 450px;text-align: right;">
	<tr>
		<th style="width: 14%;">Mes</th>
		<th style="width: 32%;">Proyecci&oacute;n</th>
		<th style="width: 32%;">Real</th>
		<th>Porcentaje</th>
	</tr>
<?php
foreach($Datos[$Id_Bsc_Objetivo] as $Mes => $Valores)
{
	$Porcentaje = 0;
	if(0 < $Valores['proy'])
	{
		if('+' == $Condicion)
		{
			$Porcentaje = $Valores['real'] * 100 / $Valores['proy'];
		}
		else
		{
			$Porcentaje = ( -($Valores['real'] / $Valores['proy']) + 2) * 100;
		}
	}
	$Imagen = 'r';
	if($Porcentaje > 90.99)
	{
		$Imagen = 'v';
	}
	elseif($Porcentaje > 74.99)
	{
		$Imagen = 'a';
	}
?>
	<tr>
		<td style="text-align: left;"><img src="/html/img/semaforo_<?=$Imagen?>.png" /> <?=$Meses[$Mes]?></td>
		<td><?=number_format($Valores['proy'],2)?></td>
		<td><?=number_format($Valores['real'],2)?></td>
		<td><?=number_format($Porcentaje, 2)?>%</td>
	</tr>
<?php
}
?>
</table>




