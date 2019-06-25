<style>
	.negrita{
		font-weight: bold;
	}
	.clase_titulo{
		text-align: center;
		color: #404040;
		background: #c0c0ff;
	}
	
	#mc_titulos_lineas{
		float:left;
		width: 270px;
		border-collapse: collapse;
		table-layout: fixed;
	}
	#mc_listado_lineas{
		border-collapse: collapse;
		table-layout: fixed;
	}
	#mc_titulos_lineas td, #mc_listado_lineas td{
		padding: 1px 3px;
		height: 22px;
		line-height: 15px;
		font-size: 12px;
		overflow: hidden;
		white-space: nowrap;
		border: 1px solid #aaaaaa;
	}
	#mc_titulos_lineas a{
		border-bottom: 1px dotted #777777;
	}
	#mc_titulos_lineas a:hover{
		text-decoration: none;
		border-bottom: 1px solid #444444;
	}
	
	#div_consolidado{
		float: left;
		width: 640px;
		overflow-x: scroll;
	}
</style>



<strong>A&ntilde;o</strong>

<select id="mc_anho_cambia">
<?
$Fecha_Fin = date('Y') + 2;
for($i = 2015; $i <= $Fecha_Fin; $i++)
{
?>
	<option value="<?=$i?>"<?=($i==$Anho)?' selected="selected"':''?>><?=$i?></option>
<?
}
?>
</select>

<select id="mc_mes_inicio">
	<option value="anual">Anual</option>
<?
foreach($Meses_v as $iMes => $nMes)
{
?>
			<option value="<?=$iMes?>"<?=($iMes==$Mes_Inicio)?' selected="selected"':''?>><?=$nMes?></td>
<?
}
?>
</select>

<select id="mc_mes_fin"<?=('anual'==$Mes_Inicio)?' style="display:none;"':''?>>
<?
foreach($Meses_v as $iMes => $nMes)
{
?>
			<option value="<?=$iMes?>"<?=($iMes==$Mes_Fin)?' selected="selected"':''?>><?=$nMes?></td>
<?
}
?>
</select>


&nbsp;
<input type="button" value="Ver Consolidado" id="mc_ver_consolidado" />
<br />


<table id="mc_titulos_lineas">
	<tr>
		<td class="clase_titulo negrita">L&Iacute;NEA</td>
	</tr>
	<tr>
		<td class="clase_titulo negrita">&nbsp;</td>
	</<tr>
<?
recorrer_lineas($Lineas[0], 0, $Lineas, 0, $Mes_Inicio, $Anho);

function recorrer_lineas(
	$Recorrer,
	$Id_Padre,
	array & $Lineas,
	$Gris,
	$Mes_Inicio,
	$Anho
)
{
	foreach($Recorrer as $Id_Mc_Linea => $Datos)
	{
		
		$Hijos = false;
		if(isset($Lineas[$Id_Mc_Linea]))
		{
			
			$Hijos = true;
			
			recorrer_lineas(
				$Lineas[$Id_Mc_Linea],
				$Id_Mc_Linea,
				$Lineas,
				($Gris + 1),
				$Mes_Inicio,
				$Anho
			);
			
		}
		
		$Grises = array(
			'dddddd',
			'f6e2d2',
			'f4d1d1',
			'ecebc9',
			'd2f1ce',
			'cfcff3',
			'f1cdef',
			'f1cdef'
		);
		
		$Background = $Grises[$Gris];
		if(!$Hijos)
		{
			$Background = 'ffffff';
		}
?>
	<tr id="mc_ti_<?=$Datos['codigo']?>" style="display:none;" conta="<?=strlen($Datos['codigo'])?>">
		<td style="background: #<?=$Background?>">
			<a href="/conta/movimientos/detalle/<?=$Id_Mc_Linea?>/<?=$Anho?>/<?=$Mes_Inicio?>" target="_blank" tittle="Ver Detalle"><?=$Datos['codigo']?></a>
			- [<?=$Datos['mas_menos']?>] <?=$Datos['linea']?>
		</td>
	</tr>
<?
		
	}
}
?>
</table>


<div id="div_consolidado">
	
	<table id="mc_listado_lineas">
		<tr>
<?
foreach($Meses_v as $iMes => $nMes)
{
	if('anual' != $Mes_Inicio && $iMes < $Mes_Inicio)
	{
		continue;
	}
?>
			<td colspan="4" class="clase_titulo negrita"><?=$nMes?></td>
<?
	
	if('anual' != $Mes_Inicio && $iMes == $Mes_Fin)
	{
		break;
	}
	
}
?>
			<td colspan="4" class="clase_titulo negrita">CONSOLIDADO</td>
		</tr>
		<tr>
<?
foreach($Meses_v as $iMes => $nMes)
{
	if('anual' != $Mes_Inicio && $iMes < $Mes_Inicio)
	{
		continue;
	}
?>
			<td class="clase_titulo negrita" style="width: 65px;">PLAN</td>
			<td class="clase_titulo negrita" style="width: 65px;">PORC.</td>
			<td class="clase_titulo negrita" style="width: 65px;">REAL</td>
			<td class="clase_titulo negrita" style="width: 65px;">PORC.</td>
<?
	
	if('anual' != $Mes_Inicio && $iMes == $Mes_Fin)
	{
		break;
	}
	
}
?>
			<td class="clase_titulo negrita" style="width: 65px;">PLAN</td>
			<td class="clase_titulo negrita" style="width: 65px;">PORC.</td>
			<td class="clase_titulo negrita" style="width: 65px;">REAL</td>
			<td class="clase_titulo negrita" style="width: 65px;">PORC.</td>
		</tr>
<?

//Al final ordenare las filas segun su codigo
$Lineas_JS = array();

recorrer_lineasB(
	$Lineas[0],
	0,
	$Lineas,
	0,
	$Meses_v,
	'+',
	$Consolidado,
	$Presupuesto,
	$Lineas_JS,
	$Mes_Inicio,
	$Mes_Fin
);


//Se recorerra el array que contenga las lineas deseadas
function recorrer_lineasB(
	$Recorrer,//Lineas a recorrer
	$Id_Padre,//Id del padre
	array & $Lineas,//Array con todas las lineas
	$Gris,//Variable no deseada para resaltar las lineas que poseen mas lineas
	array & $Meses_v,//Array de los meses (capitan obvio al rescate)
	$Mas_Menos,//Indica si el padre es suma o resta
	array & $Consolidado,//Valores reales de cada linea por mes
	array & $Presupuesto,//Valores presupuestados de cada linea por mes
	array & $Lineas_JS,//Capturara el codigo de las lineas recorridas,
	$Mes_Inicio,
	$Mes_Fin
)
{
	//Este array tomara los valores de las lineas recorridas y se regresara
	$Total_Hijos = array();
	
	
	foreach($Meses_v as $iMes => $nMes)
	{
		//Inicializacion de la variable por mes
		$Total_Hijos['real'][$iMes] = 0;
		$Total_Hijos['plan'][$iMes] = 0;
	}
	
	//Recorrer las lineas hijas
	foreach($Recorrer as $Id_Mc_Linea => $Datos)
	{
		
		//Guardo la linea actual
		$Lineas_JS[] = $Datos['codigo'];
		
		//Es necesario saber si esta linea posee hijos o no, asi se realizaran
		//algunas acciones u otras
		$Hijos = false;
		
		//Si esta linea es padre de otras
		if(isset($Lineas[$Id_Mc_Linea]))
		{
			
			//Entonces tiene hijos
			$Hijos = true;
			
			//Y debemos recorrerlos, la explicacion de cada campo esta mas arriba
			//Lo que recibire es un array con la suma de las lineas recorridas
			$Hijos_recorridos = recorrer_lineasB(
				$Lineas[$Id_Mc_Linea],
				$Id_Mc_Linea,
				$Lineas,
				($Gris + 1),
				$Meses_v,
				$Datos['mas_menos'],
				$Consolidado,
				$Presupuesto,
				$Lineas_JS,
				$Mes_Inicio,
				$Mes_Fin
			);
			
		}
		
?>
		<tr id="mc_li_<?=$Datos['codigo']?>" style="display:none;">
<?
		//Sumatoria mes por mes de los valores reales y presupuestados
		$Plan_Linea = 0;
		$Real_Linea = 0;
		
		//Recorrido de la linea mes por mes
		foreach($Meses_v as $iMes => $nMes)
		{
			
			if('anual' != $Mes_Inicio && $iMes < $Mes_Inicio)
			{
				continue;
			}
			
			//Inicializacion de las variables a imprimir en el html
			$Plan = 0;
			$Real = 0;
			
			//Si ya se han ingresado valores reales para esta linea
			if(isset($Consolidado[$Id_Mc_Linea][$iMes]) && !$Hijos)
			{
				
				//Valor para el TD mensual
				$Real = $Consolidado[$Id_Mc_Linea][$iMes];
				
				//Dependiendo del signo del padre se sumara o restara al compararlo
				//con sus hijos y se regresara ese valor
				if($Mas_Menos == $Datos['mas_menos'])
				{
					$Total_Hijos['real'][$iMes] += $Real;
				}
				else
				{
					$Total_Hijos['real'][$iMes] -= $Real;
				}
				
			}
			
			//Valor presupuestado
			if(isset($Presupuesto[$Id_Mc_Linea][$iMes]) && !$Hijos)
			{
				$Plan = $Presupuesto[$Id_Mc_Linea][$iMes];
				
				if($Mas_Menos == $Datos['mas_menos'])
				{
					$Total_Hijos['plan'][$iMes] += $Plan;
				}
				else
				{
					$Total_Hijos['plan'][$iMes] -= $Plan;
				}
			}
			
			
			//Si es un padre
			if($Hijos)
			{
				
				//Valor para el TD mensual
				$Real = $Hijos_recorridos['real'][$iMes];
				$Plan = $Hijos_recorridos['plan'][$iMes];
				
				//Igual debe contribuir al total de su propio padre segun sus signos
				//Solo que utiliza la informacion que le proporcionaron sus hijos
				if($Mas_Menos == $Datos['mas_menos'])
				{
					$Total_Hijos['real'][$iMes] += $Hijos_recorridos['real'][$iMes];
					$Total_Hijos['plan'][$iMes] += $Hijos_recorridos['plan'][$iMes];
				}
				else
				{
					$Total_Hijos['real'][$iMes] -= $Hijos_recorridos['real'][$iMes];
					$Total_Hijos['plan'][$iMes] -= $Hijos_recorridos['plan'][$iMes];
				}
			}
			
			//Valor para el TD anual
			$Real_Linea += $Real;
			$Plan_Linea += $Plan;
			
			$Porc_Real = 0;
			if(0 < $Real && 0 < $Consolidado[18][$iMes])
			{
				$Porc_Real = $Real * 100 / $Consolidado[18][$iMes];
			}
			
			$Porc_Plan = 0;
			if(0 < $Plan && 0 < $Presupuesto[18][$iMes])
			{
				$Porc_Plan = $Plan * 100 / $Presupuesto[18][$iMes];
			}
			
			
			
			if('101' == $Datos['codigo'])
			{
				if(0 < $Real && 0 < $Presupuesto[18][$iMes])
				{
					$Porc_Real = $Real * 100 / $Presupuesto[18][$iMes];
				}
				$Porc_Plan = 100;
			}
			
			$Grises = array(
				'dddddd',
				'f6e2d2',
				'f4d1d1',
				'ecebc9',
				'd2f1ce',
				'cfcff3',
				'f1cdef'
			);
?>
			<td style="background:#<?=($Hijos)?$Grises[$Gris]:''?>;" class="derecha">$<?=number_format($Plan, 0)?></td>
			<td style="background:#<?=($Hijos)?$Grises[$Gris]:'ffffbb'?>;" class="derecha"><?=number_format($Porc_Plan, 1)?>%</td>
			<td style="background:#<?=($Hijos)?$Grises[$Gris]:'eeeeee'?>;" class="derecha">$<?=number_format($Real, 0)?></td>
			<td style="background:#<?=($Hijos)?$Grises[$Gris]:'ffffbb'?>;" class="derecha"><?=number_format($Porc_Real, 1)?>%</td>
<?
			
			if('anual' != $Mes_Inicio && $iMes == $Mes_Fin)
			{
				break;
			}
			
		}
		
		
		$Porcentaje = 0;
		if(0 < $Real_Linea && 0 < $Plan_Linea)
		{
			$Porcentaje = $Real_Linea * 100 / $Plan_Linea;
		}
?>
			<td style="background:#<?=($Hijos)?$Grises[$Gris]:''?>;" class="derecha">$<?=number_format($Plan_Linea, 0)?></td>
			<td style="background:#<?=($Hijos)?$Grises[$Gris]:'ffffbb'?>;" class="derecha"><?=number_format($Porcentaje, 0)?>%</td>
			<td style="background:#<?=($Hijos)?$Grises[$Gris]:'eeeeee'?>;" class="derecha">$<?=number_format($Real_Linea, 0)?></td>
			<td style="background:#<?=($Hijos)?$Grises[$Gris]:'ffffbb'?>;" class="derecha"><?=number_format($Porcentaje, 0)?>%</td>
		</tr>
<?
		
	}
	
	return $Total_Hijos;
	
}

sort($Lineas_JS);
?>
	</table>
</div>
<br />


<script>
	
	var Codigos = [<?=implode(', ', $Lineas_JS);?>];
	
	for(i in Codigos)
	{
		if(3 == $('#mc_ti_'+Codigos[i]).attr('conta'))
		{
			var TR = $('#mc_ti_'+Codigos[i]);
			$('#mc_ti_'+Codigos[i]).remove();
			$('#mc_titulos_lineas').append(TR);
			$('#mc_ti_'+Codigos[i]).show();
			
			TR = $('#mc_li_'+Codigos[i]);
			$('#mc_li_'+Codigos[i]).remove();
			$('#mc_listado_lineas').append(TR);
			$('#mc_li_'+Codigos[i]).show();
		}
	}
	
	
	$('#mc_mes_inicio').change(function()
	{
		if('anual' == $(this).val())
		{
			$('#mc_mes_fin').hide();
		}
		else
		{
			$('#mc_mes_fin').show();
		}
	});
	
	
	$('#mc_ver_consolidado').click(function()
	{
		window.location = '/conta/consolidado/index/'+$('#mc_anho_cambia').val()+'/'+$('#mc_mes_inicio').val()+'/'+$('#mc_mes_fin').val();
	});
	
	
</script>


