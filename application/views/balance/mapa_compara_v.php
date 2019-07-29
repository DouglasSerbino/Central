

<form action="/balance/mapa/comparar" method="post" id="formulario_mapa">
	
	Mostrando:
	<select name="mes_mapa" id="mes_mapa">
		<option value="anual">Anual</option>
	<?php
foreach($Meses as $iMes => $nMes)
{
	?>
		<option value="<?=$iMes?>"<?=($iMes==$Mes)?' selected="selected"':''?>><?=$nMes?></option>
	<?php
}
	?>
	</select>
	<input type="text" name="anho_mapa" id="anho_mapa" value="<?=$Ver_Anho?>" />
	
	<input type="checkbox" name="comparar" id="comparar" checked="checked" />
	<label for="comparar">Comparar a&ntilde;o anterior</label>
	
	&nbsp; &nbsp;
	<input type="button" value="Cambiar" onclick="cambia_mapa()" />
	
</form>

<script>
	function cambia_mapa()
	{
		if($('#comparar').attr('checked'))
		{
			$('#formulario_mapa').submit();
		}
		else
		{
			window.location = '/balance/mapa/index/'+$('#anho_mapa').val()+'/'+$('#mes_mapa').val();
		}
	}
</script>


<?php
foreach($Def_Objetivos as $Id_Perspectiva => $Perspectiva)
{
?>
<br /><strong><?=$Perspectiva['Nom']?></strong><br />
<div id="grafico<?=$Id_Perspectiva?>"></div>
<?php
	$Coma = false;
	$Labels = '';
	$Valores = '';
	$Maximo_Valor = 100;
	foreach($Perspectiva['Objs'] as $Id_Bsc_Objetivo => $Objetivo)
	{
		if(!$Coma)
		{
			$Coma = true;
		}
		else
		{
			$Labels .= ',';
			$Valores .= ',';
		}
		
		$Porc_Actual = 0;
		if(0 < $Comparar[$Ver_Anho][$Id_Bsc_Objetivo]['proy'])
		{
			if('+' == $Objetivo['Con'])
			{
				$Porc_Actual = $Comparar[$Ver_Anho][$Id_Bsc_Objetivo]['real'] * 100 / $Comparar[$Ver_Anho][$Id_Bsc_Objetivo]['proy'];
			}
			else
			{
				$Porc_Actual = ( -($Comparar[$Ver_Anho][$Id_Bsc_Objetivo]['real'] / $Comparar[$Ver_Anho][$Id_Bsc_Objetivo]['proy']) + 2) * 100;
			}
		}
		
		$Porc_Anteri = 0;
		if(0 < $Comparar[$Anho_Anterior][$Id_Bsc_Objetivo]['proy'])
		{
			if('+' == $Objetivo['Con'])
			{
				$Porc_Anteri = $Comparar[$Anho_Anterior][$Id_Bsc_Objetivo]['real'] * 100 / $Comparar[$Anho_Anterior][$Id_Bsc_Objetivo]['proy'];
			}
			else
			{
				$Porc_Anteri = ( -($Comparar[$Anho_Anterior][$Id_Bsc_Objetivo]['real'] / $Comparar[$Anho_Anterior][$Id_Bsc_Objetivo]['proy']) + 2) * 100;
			}
		}
		
		
		$Labels .= '"% '.$Objetivo['Nom'].'"';
		$Valores .= '["'.$Porc_Anteri.'","'.$Porc_Actual.'"]';
		if($Porc_Anteri > $Maximo_Valor)
		{
			$Maximo_Valor = $Porc_Anteri;
		}
		if($Porc_Actual > $Maximo_Valor)
		{
			$Maximo_Valor = $Porc_Actual;
		}
	}
?>
	<script>
	$('#grafico<?=$Id_Perspectiva?>').gbarras(
	{
		'ymaximo': <?=$Maximo_Valor?>,
		'barras': [<?=$Valores?>],
		'xleyenda': [<?=$Labels?>],
		'series': ['<?=$Anho_Anterior?>','<?=$Ver_Anho?>']
	});
</script>
<?php
}
?>






<div id="grafiqco1"></div>
<script>
	$('#grafico1a').gbarras(
	{
		'ymaximo': 243524,
		'barras': [[128274,124307],[135015,177293],[153476,243524],[140683,148779],[131755,139513],[132923,135924],[118822,136797],[133028,121439],[129839,149469],[149199,0],[127095,0],[137630,0]],
		'xleyenda': ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		'series': ['Proyectado','Real']
	});
</script>


