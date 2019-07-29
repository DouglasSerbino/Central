

<div id="datos_vent_clie">


<table class="tabular table table-bordered table-hover table">
	<tr>
		<th rowspan="2" >Cliente</th>
<?php

$Total_Venta = 0;
$Total_Proye = 0;


//Perdon, no pude mantener esto fuera del codigo php
$Fila_Tr = '';
$Colspan = '';
if('todo' == $vista || 'proyeccion' == $vista)
{
	$Fila_Tr .= '<th class="numero">Proy.</th>';
}
if('todo' == $vista || 'venta' == $vista)
{
	$Fila_Tr .= '<th class="numero">Venta</th>';
}
if('todo' == $vista)
{
	$Fila_Tr .= '<th class="numero">Porcen.</th>';
	$Colspan = ' colspan="3"';
}

foreach($Vent_Clie['Meses_Rango'] as $Anho_Mes)
{
?>
		<th<?=$Colspan?> style="text-align:center;"><?=$meses_abr_v[date('M', strtotime($Anho_Mes.'-01'))]?> <?=date('Y', strtotime($Anho_Mes.'-01'))?></th>
<?php
}
?>
		<th <?=$Colspan?> style="text-align:center;">Total por Cliente</th>
	</tr>
	<tr>
<?php
foreach($Vent_Clie['Meses_Rango'] as $Anho_Mes)
{
	echo $Fila_Tr;
}
echo $Fila_Tr;
?>
	</tr>
<?php
foreach($Clientes as $Cliente)
{
	if('todos' != $filtro_vendedor && $Cliente['id_usuario'] != $filtro_vendedor)
	{
		continue;
	}
	
	
	if(
		('todo' == $vista || 'venta' == $vista)
		&& 'sin' == $filtro_venta
		&& isset($Vent_Clie['Venta'][$Cliente['id_cliente']])
	)
	{
		continue;
	}
	
	
	if(
		('todo' == $vista || 'venta' == $vista)
		&& 'con' == $filtro_venta
		&& !isset($Vent_Clie['Venta'][$Cliente['id_cliente']])
	)
	{
		continue;
	}
	
	
	
?>
	<tr>
		<td><?=$Cliente['nombre']?></td>
<?php
	$Total_Cliente_Proy = 0;
	$Total_Cliente_Vent = 0;
	foreach($Vent_Clie['Meses_Rango'] as $Anho_Mes)
	{
		if('todo' == $vista || 'proyeccion' == $vista)
		{
			
			$Proyeccion = 0;
			if((isset($Vent_Clie['Proyeccion'][$Cliente['id_cliente']][$Anho_Mes])))
			{
				if(!isset($Proye_Division[$Anho_Mes]))
				{
					$Proye_Division[$Anho_Mes] = 0;
				}
				$Proye_Division[$Anho_Mes] += $Vent_Clie['Proyeccion'][$Cliente['id_cliente']][$Anho_Mes];
				$Proyeccion = $Vent_Clie['Proyeccion'][$Cliente['id_cliente']][$Anho_Mes];
				$Total_Cliente_Proy += $Proyeccion;
			}
?>
		<td class="numero vent100">$<?=number_format($Proyeccion, 2)?></td>
<?php
		}
		if('todo' == $vista || 'venta' == $vista)
		{
			
			$Venta = 0;
			if((isset($Vent_Clie['Venta'][$Cliente['id_cliente']][$Anho_Mes])))
			{
				if(!isset($Venta_Division[$Anho_Mes]))
				{
					$Venta_Division[$Anho_Mes] = 0;
				}
				$Venta_Division[$Anho_Mes] += $Vent_Clie['Venta'][$Cliente['id_cliente']][$Anho_Mes];
				$Venta = $Vent_Clie['Venta'][$Cliente['id_cliente']][$Anho_Mes];
				$Total_Cliente_Vent += $Venta;
			}
?>
		<td class="numero vent100">$<?=number_format($Venta, 2)?></td>
<?php
		}
		if('todo' == $vista)
		{
			$Porcentaje = 0;
			if(0 < $Venta)
			{
				$Porcentaje = 100;
			}
			if(0 < $Proyeccion)
			{
				$Porcentaje = $Venta * 100;
				$Porcentaje = $Porcentaje / $Proyeccion;
			}
?>
		<td class="numero vent100 ven_cli_porcen"><?=number_format($Porcentaje, 2)?>%</td>
<?php
		}
		
	}
	if('todo' == $vista || 'proyeccion' == $vista)
	{
?>
		<td class="numero vent100">$<?=number_format($Total_Cliente_Proy, 2)?></td>
<?php
	}
	if('todo' == $vista || 'venta' == $vista)
	{
?>
		<td class="numero vent100">$<?=number_format($Total_Cliente_Vent, 2)?></td>
<?php
	}
	if('todo' == $vista)
	{
		$Porcentaje = 0;
		if(0 < $Total_Cliente_Proy)
		{
			$Porcentaje = $Total_Cliente_Vent * 100;
			$Porcentaje = $Porcentaje / $Total_Cliente_Proy;
		}
?>
		<td class="numero vent100"><?=number_format($Porcentaje, 2)?>%</td>
<?php
	}
?>
	</tr>
<?php
}
?>
	<tr>
		<th class="numero">TOTAL</th>
		
		

<?php
$Total_Proye = 0;
$Total_Venta = 0;
foreach($Vent_Clie['Meses_Rango'] as $Anho_Mes)
{
	if('todo' == $vista || 'proyeccion' == $vista)
	{
		$Proyeccion = 0;
		if(isset($Proye_Division[$Anho_Mes]))
		{
			$Proyeccion = $Proye_Division[$Anho_Mes];
		}
		$Total_Proye += $Proyeccion;
?>
		<th class="numero vent100">$<?=number_format($Proyeccion, 2)?></th>
<?php
	}
	if('todo' == $vista || 'venta' == $vista)
	{
		$Venta = 0;
		if(isset($Venta_Division[$Anho_Mes]))
		{
			$Venta = $Venta_Division[$Anho_Mes];
		}
		$Total_Venta += $Venta;
?>
		<th class="numero vent100">$<?=number_format($Venta, 2)?></th>
<?php
	}
	if('todo' == $vista)
	{
		$Porcentaje = 100;
		if(0 < $Proyeccion)
		{
			$Porcentaje = $Venta * 100 / $Proyeccion;
		}
?>
		<th class="numero vent100"><?=number_format($Porcentaje, 2)?>%</th>
<?php
	}
}





if('todo' == $vista || 'proyeccion' == $vista)
{
?>
		<th class="numero vent100">$<?=number_format($Total_Proye, 2)?></th>
<?php
}
if('todo' == $vista || 'venta' == $vista)
{
?>
		<th class="numero vent100">$<?=number_format($Total_Venta, 2)?></th>
<?php
}
if('todo' == $vista)
{
	$Porcentaje = 100;
	if(0 < $Total_Proye)
	{
		$Porcentaje = $Total_Venta * 100 / $Total_Proye;
	}
?>
		<th class="numero vent100"><?=number_format($Porcentaje, 2)?>%</th>
<?php
}
?>
	</tr>
</table>




</div>


