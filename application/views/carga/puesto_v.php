<form name="rango_fechas" action="/carga/puestos" method="post">
	<select name="mes">
<?php
foreach($Meses as $iMes => $nMes){
?>
		<option value="<?php echo $iMes; ?>"<?php echo ($Mes==$iMes)?' selected="selected"':''; ?>><?php echo $nMes; ?></option>
<?php
}
?>
	<option value='Anual' <?=('Anual'==$Mes)?' selected="selected"':''?>>Anual</option>
	</select> &nbsp; 
	<input type="text" name="anho" value="<?=$Anho?>" size="5" />
	<input type="submit" value="Crear Reporte" />
</form>


<?php
foreach($Usuarios as $Departamentos)
{
	
	if(
		'Gerente de Grupo' == $Departamentos['dpto']
		|| 'Sistemas Inform&aacute;ticos' == $Departamentos['dpto']
	)
	{
		continue;
	}
?>

<br />

<strong><?=$Departamentos['dpto']?></strong>

<table class="tabular">
	<tr>
		<th style="width: 135px;">Puesto</th>
		<th style="width: 80px;">Trabajos</th>
		<th style="width: 100px;">Rechazos</th>
		<th style="width: 105px;">Programado</th>
		<th style="width: 160px;" colspan="2">Utilizado</th>
<?php
			if('s' == $Departamentos['tiempo'])
			{
?>
		<th style="width: 160px;" colspan="2">Disponible</th>
<?php
			}
?>
	</tr>
<?php
	foreach($Departamentos['usuarios'] as $Id_Usuario => $Usuario)
	{
		if(isset($Trabajos[$Id_Usuario]))
		{
			$Rech_Usuario = 0;
			$Rech_Porcentaje = 0;
			if(isset($Rechazos[$Id_Usuario]))
			{
				$Rech_Usuario = $Rechazos[$Id_Usuario];
				$Rech_Porcentaje = ($Rech_Usuario * 100) / $Trabajos[$Id_Usuario];
				$Rech_Porcentaje =number_format($Rech_Porcentaje, 2);
			}
			
			$Utili_Usuario = 0;
			$Utili_Porcentaje = 0;
			$Tiempo_Dispo = 0;
			$Tiempo_Porce = 0;
			$Tipo_Tiempo = 'habil';
			
			if(
				'Planificaci&oacute;n' == $Departamentos['dpto']
				|| 'Ventas' == $Departamentos['dpto']
				|| 'Grupo Externo' == $Departamentos['dpto']
			)
			{
				$Tipo_Tiempo = 'real';
			}
			
			if(isset($TUtilizado[$Id_Usuario][$Tipo_Tiempo]['horas']))
			{
				
				$Utili_Usuario = $TUtilizado[$Id_Usuario][$Tipo_Tiempo]['horas'];
				if(0 < $TProgramado[$Id_Usuario]['minutos'])
				{
					$Utili_Porcentaje = ($TUtilizado[$Id_Usuario][$Tipo_Tiempo]['minutos'] * 100);
					$Utili_Porcentaje = $Utili_Porcentaje / $TProgramado[$Id_Usuario]['minutos'];
					$Utili_Porcentaje = number_format($Utili_Porcentaje, 2);
				}
				
				if(
					'Planificaci&oacute;n' == $Departamentos['dpto']
					|| 'Ventas' == $Departamentos['dpto']
					|| 'Grupo Externo' == $Departamentos['dpto']
				)
				{
					$Utili_Usuario = $TUtilizado[$Id_Usuario][$Tipo_Tiempo]['minutos'];
					
					if(0 < $Trabajos[$Id_Usuario])
					{
						$Utili_Usuario = $this->fechas_m->minutos_a_hora(($Utili_Usuario / $Trabajos[$Id_Usuario]));
					}
				}
				
			}
			
				
			if(
				isset($TUtilizado[$Id_Usuario][$Tipo_Tiempo]['minutos'])
				&& 's' == $Departamentos['tiempo']
			)
			{
				$Tiempo_Dispo = $THabil - $TUtilizado[$Id_Usuario][$Tipo_Tiempo]['minutos'];
				if(0 > $Tiempo_Dispo)
				{
					$Tiempo_Dispo = 0;
				}
				
				$Tiempo_Porce = ($Tiempo_Dispo * 100) / $THabil;
				$Tiempo_Porce = number_format($Tiempo_Porce, 2);
				
				$Tiempo_Dispo = $this->fechas_m->minutos_a_hora($Tiempo_Dispo);
			}
			
?>
	<tr>
		<td>
			<a href="javascript:ventana_externa('/carga/puestos/usuario/<?=$Id_Usuario?>/<?=$Anho?>/<?=$Mes?>');" class="toolizq iconos iexterna"><span>Ver Detalle en ventana externa</span></a>
			<?=$Usuario['usuario']?>
		</td>
		<td><?=$Trabajos[$Id_Usuario]?></td>
		<td><?=$Rech_Usuario?> (<?=$Rech_Porcentaje?>%)</td>
		<td><?=$TProgramado[$Id_Usuario]['horas']?></td>
		<td><?=$Utili_Usuario?></td>
		<td>(<?=$Utili_Porcentaje?>%)</td>
<?php
			if('s' == $Departamentos['tiempo'])
			{
?>
		<td><?=$Tiempo_Dispo?>h</td>
		<td>(<?=$Tiempo_Porce?>%)</td>
<?php
			}
?>
	</tr>
<?php
		}
	}
?>
</table>
<?php
}
?>