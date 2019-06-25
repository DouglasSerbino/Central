
<style>
	th{
		background: #D0D0D0;
	}
	th, td{
		padding: 0px;
	}
	.porcen{
		text-align: center;
		width: 45px;
	}
	.detalle{
		font-weight: bold;
	}
	.trimestre
	{
		text-align: center;
		width: 45px;
		background: #eaeaea;
	}
</style>



<input type="text" id="bsc_anho" value="<?=$Ver_Anho?>" size="5" />
<input type="button" value="Cambiar" onclick="window.location='/balance/bsc/index/'+$('#bsc_anho').val()" />

<table style="width:115%;font-size:10px;">
<?

foreach($Def_Objetivos as $Id_Perspectiva => $Perspectiva)
{
?>
	<tr>
		<th colspan="19"><?=$Perspectiva['Nom']?></th>
	</tr>
	<tr class="detalle">
		<td>Objetivos Estrat&eacute;gicos</td>
		<td>Indicadores</td>
<?
	$a = 1;
	foreach($Meses as $iMes => $nMes)
	{
?>
		<td class="porcen"><?=$nMes?></td>
<?
		if($iMes == 3 or $iMes == 6 or $iMes == 9 or $iMes == 12)
		{
			?>
				<td class="trimestre">Trim <?=$a?></td>
			<?php
			$a++;
		}
	}
?>
	<td>Prom Final</td>
	</tr>
<?
	
	foreach($Perspectiva['Objs'] as $Id_Bsc_Objetivo => $Objetivo)
	{
		$porfinal = 0;
?>
	<tr>
		<td><?=$Objetivo['Nom']?></td>
		<td>[<?=$Objetivo['Con']?>] <?=$Objetivo['Ind']?></td>
<?
//print_r($Datos[$Id_Bsc_Objetivo]);
	$realt = 0;
	$proyect = 0;
	$trimestre = 0;
	$real = 0;
	$proyec = 0;
	foreach($Datos[$Id_Bsc_Objetivo] as $Mes => $Valores)
	{
		$Porcentaje = 0;
		if(0 < $Valores['proy'])
		{
			if(0 < $Valores['real'])
			{
				if('+' == $Objetivo['Con'])
				{
					$Porcentaje = $Valores['real'] * 100 / $Valores['proy'];
				}
				else
				{
					$Porcentaje = ( -($Valores['real'] / $Valores['proy']) + 2) * 100;
				}
			}
		}
		$Imagen = 'r';
		if($Porcentaje > 90)
		{
			$Imagen = 'v';
		}
		elseif($Porcentaje > 74)
		{
			$Imagen = 'a';
		}
		


	if(2012 >= $Ver_Anho)
	{
?>
		<td class="porcen"><?=number_format($Porcentaje, 0)?>%<br /><img src="/html/img/semaforo_<?=$Imagen?>.png" /></td>
<?
	}
	elseif($Mes <= date('m'))
	{
	?>
		<td class="porcen"><?=number_format($Porcentaje, 0)?>%<br /><img src="/html/img/semaforo_<?=$Imagen?>.png" /></td>
	<?
	}
	else{
?>
			<td class="porcen"></td>
<?
	}

		$real += $Valores['real'];
		$proyec += $Valores['proy'];
		$realt += $Valores['real'];
		$proyect += $Valores['proy'];
		if($Mes == 3 or $Mes == 6 or $Mes == 9 or $Mes == 12)
		{
			$trimestre = 0;
			if(0 < $proyec)
			{
				if(0 < $real)
				{
					if('+' == $Objetivo['Con'])
					{
						$trimestre = $real * 100 / $proyec;
					}
					else
					{
						$trimestre = ( -($real / $proyec) + 2) * 100;
					}
				}
			}
			
			$real = 0;
			$proyec = 0;
			$Imagen = 'r';
			if($trimestre > 90)
			{
				$Imagen = 'v';
			}
			elseif($trimestre > 74)
			{
				$Imagen = 'a';
			}
		?>
			<td class="trimestre"><?=number_format($trimestre, 0)?>%<br /><img src="/html/img/semaforo_<?=$Imagen?>.png" /></td>
		<?php
		}
	}
		$Imagen = 'r';
		$porfinal = 0;
		if(0 < $proyect)
		{
			if(0 < $realt)
			{
				if('+' == $Objetivo['Con'])
				{
					$porfinal = $realt * 100 / $proyect;
				}
				else
				{
					$porfinal = ( -($realt / $proyect) + 2) * 100;
				}
			}
		}
		
		//echo $realt.'---'.$proyect.'<br>';
		if($porfinal > 90)
		{
			$Imagen = 'v';
		}
		elseif($porfinal > 74)
		{
			$Imagen = 'a';
		}
?>
	<td class="porcen"><?=number_format($porfinal, 0)?>%<br /><img src="/html/img/semaforo_<?=$Imagen?>.png" /></td>
	</tr>
<?
	}	
?>

<?
}
?>
</table>