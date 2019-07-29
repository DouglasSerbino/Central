
<style>
	#mapaest{
		background: url(/html/img/mapaestrategico.png) no-repeat;
		width: 712px;
		height: 454px;
		position: absolute;
		padding-bottom: 60px;
	}
	#mapaest .perspectiva{
		position: absolute;
	}
	#mapaest .objetivos{
		height: 43px;
		position: absolute;
	}
	#mapaest img{
		margin-top: 13px;
		float: left;
		margin-left: 5px;
	}
	#mapaest .objetivos div{
		margin-top: 1px;
		float: left;
		width: 130px;
		font-size: 11px;
		line-height: 14px;
		text-align: center;
	}
</style>



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
	
	<input type="checkbox" name="comparar" id="comparar" />
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

<div id="mapaest">
<?php
$Altos = array(
	2,
	70,
	70,
	138,
	206,
	206,
	274,
	342,
	274,
	274,
	342,
	410,
	410,
	410
);
$Izqui = array(
	385,
	298,
	471,
	385,
	471,
	298,
	558,
	298,
	211,
	385,
	471,
	558,
	385,
	211
);
$p = 0;
$o = 0;
foreach($Def_Objetivos as $Id_Perspectiva => $Perspectiva)
{
	
	foreach($Perspectiva['Objs'] as $Id_Bsc_Objetivo => $Objetivo)
	{
		$Porcentaje = 0;
		if(0 < $Datos[$Id_Bsc_Objetivo][$Mes]['proy'])
		{
			if('+' == $Objetivo['Con'])
			{
				$Porcentaje = $Datos[$Id_Bsc_Objetivo][$Mes]['real'] * 100 / $Datos[$Id_Bsc_Objetivo][$Mes]['proy'];
			}
			else
			{
				$Porcentaje = ( -($Datos[$Id_Bsc_Objetivo][$Mes]['real'] / $Datos[$Id_Bsc_Objetivo][$Mes]['proy']) + 2) * 100;
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
?>
	<div class="objetivos" style="top:<?=$Altos[$o]?>px;left:<?=$Izqui[$o]?>px;">
		<img src="/html/img/semaforo_<?=$Imagen?>.png" />
		<div><?=$Objetivo['Nom']?><br /><?=number_format($Porcentaje, 2)?>%</div>
	</div>
<?php
		$o++;
	}
	$p++;
	
}
?>
</div>