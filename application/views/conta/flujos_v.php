
<style>
	#menu-lateral{ display: none;}
	#cont-pagina{ width: 100%; }
</style>



Rango de tiempo:
<select id="flujo_rango">
	<option value="anual">Anual</option>
	<option value="1semestre"<?=('1semestre'==$Rango)?' selected="selected"':''?>>1&deg; Semestre</option>
	<option value="2semestre"<?=('2semestre'==$Rango)?' selected="selected"':''?>>2&deg; Semestre</option>
	<option value="1trimestre"<?=('1trimestre'==$Rango)?' selected="selected"':''?>>1&deg; Trimestre</option>
	<option value="2trimestre"<?=('2trimestre'==$Rango)?' selected="selected"':''?>>2&deg; Trimestre</option>
	<option value="3trimestre"<?=('3trimestre'==$Rango)?' selected="selected"':''?>>3&deg; Trimestre</option>
	<option value="4trimestre"<?=('4trimestre'==$Rango)?' selected="selected"':''?>>4&deg; Trimestre</option>
</select>



<?
function mostrar($nMes, $Rango)
{
	
	if('Anual' == $nMes)
	{
		return false;
	}
	
	$nMes += 0;
	
	$Tiempos = array(
		'anual' => array(1, 12),
		'1semestre' => array(1, 6),
		'2semestre' => array(7, 12),
		'1trimestre' => array(1, 3),
		'2trimestre' => array(4, 6),
		'3trimestre' => array(7, 9),
		'4trimestre' => array(10, 12)
	);
	
	//echo 'if('.$nMes.' >= '.$Tiempos[$Rango][0].' && '.$nMes.' <= '.$Tiempos[$Rango][1].')<br />';
	if($nMes >= $Tiempos[$Rango][0] && $nMes <= $Tiempos[$Rango][1])
	{
		return true;
	}
	
	return false;
	
}
?>



<table class="tabular" style="width:100%;">
	<tr>
		<th rowspan="2">DESCRIPCION | SEMANAS</th>
<?
foreach($Meses as $iMes => $nMes)
{
	if(!mostrar($iMes, $Rango))
	{
		continue;
	}
?>
		<th class="derecha" colspan="2"><?=substr($nMes, 0, 3)?></th>
<?
}
?>
	</tr>
	<tr>
<?
foreach($Meses as $iMes => $nMes)
{
	if(!mostrar($iMes, $Rango))
	{
		continue;
	}
?>
		<th class="derecha">Proy.</th>
		<th class="derecha">Real</th>
<?
}
?>
	</tr>
	<tr>
		<td style="width:220px;">Saldo Inicial [Más]</td>
<?
foreach($Meses as $iMes => $nMes)
{
	if(!mostrar($iMes, $Rango))
	{
		continue;
	}
?>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
<?
}
?>
	</tr>
	<tr>
		<td>INGRESOS</td>
<?
foreach($Meses as $iMes => $nMes)
{
	if(!mostrar($iMes, $Rango))
	{
		continue;
	}
	
	if(!isset($Detalle['General']['Proyectado'][$iMes]))
	{
		$Detalle['General']['Proyectado'][$iMes] = 0;
	}
	if(!isset($Detalle['General']['Real'][$iMes]))
	{
		$Detalle['General']['Real'][$iMes] = 0;
	}
?>
		<td class="derecha">$<?=number_format($Detalle['General']['Proyectado'][$iMes], 0)?></td>
		<td class="derecha">$<?=number_format($Detalle['General']['Real'][$iMes], 0)?></td>
<?
}
?>
	</tr>
	<tr>
		<th>CUENTAS POR COBRAR</th>
<?
foreach($Meses as $iMes => $nMes)
{
	if(!mostrar($iMes, $Rango))
	{
		continue;
	}
	
	if(!isset($Detalle['General']['Proyectado'][$iMes]))
	{
		$Detalle['General']['Proyectado'][$iMes] = 0;
	}
	if(!isset($Detalle['General']['Real'][$iMes]))
	{
		$Detalle['General']['Real'][$iMes] = 0;
	}
?>
		<th class="derecha">$<?=number_format($Detalle['General']['Proyectado'][$iMes], 0)?></th>
		<th class="derecha">$<?=number_format($Detalle['General']['Real'][$iMes], 0)?></th>
<?
}
?>
	</tr>
	<tr>
		<td>EXTERIOR/ NACIONALES</td>
<?
foreach($Meses as $iMes => $nMes)
{
	if(!mostrar($iMes, $Rango))
	{
		continue;
	}
	
	if(!isset($Detalle['General']['Proyectado'][$iMes]))
	{
		$Detalle['General']['Proyectado'][$iMes] = 0;
	}
	if(!isset($Detalle['General']['Real'][$iMes]))
	{
		$Detalle['General']['Real'][$iMes] = 0;
	}
?>
		<td class="derecha">$<?=number_format($Detalle['General']['Proyectado'][$iMes], 0)?></td>
		<td class="derecha">$<?=number_format($Detalle['General']['Real'][$iMes], 0)?></td>
<?
}
?>
	</tr>
	
	
	
<?
foreach($Detalle['Clientes'] as $Cod_Clie => $Datos)
{
?>
	<tr>
		<td><?=$Datos['Nombre']?></td>
<?
foreach($Meses as $iMes => $nMes)
{
	if(!mostrar($iMes, $Rango))
	{
		continue;
	}
	
	if(!isset($Datos['Proyectado'][$iMes]))
	{
		$Datos['Proyectado'][$iMes] = 0;
	}
	if(!isset($Datos['Real'][$iMes]))
	{
		$Datos['Real'][$iMes] = 0;
	}
?>
		<td class="derecha">$<?=number_format($Datos['Proyectado'][$iMes], 0)?></td>
		<td class="derecha">$<?=number_format($Datos['Real'][$iMes], 0)?></td>
<?
}
?>
	</tr>
<?
}
?>
	
	
	
	
	
	
	
	<tr>
		<th>SUB TOTAL DISPONIBLE [Menos]</th>
<?
foreach($Meses as $iMes => $nMes)
{
	if(!mostrar($iMes, $Rango))
	{
		continue;
	}
?>
		<th class="derecha">$0</th>
		<th class="derecha">$0</th>
<?
}
?>
	</tr>
	<tr>
		<td>EGRESOS</td>
<?
foreach($Meses as $iMes => $nMes)
{
	if(!mostrar($iMes, $Rango))
	{
		continue;
	}
?>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
<?
}
?>
	</tr>
	<tr>
		<td>CUENTAS POR PAGAR</td>
<?
foreach($Meses as $iMes => $nMes)
{
	if(!mostrar($iMes, $Rango))
	{
		continue;
	}
?>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
<?
}
?>
	</tr>
	<!--tr>
		<td>PROVEEDORES NACIONALES</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>SOLVENTE</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>KODAK (6)  MANTO. 3 - 6</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>JAIME SANTAMARIA (12) 7-12</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>PBS IMPRESOR (12) 7-12</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>COMPUPART STORE, ENERGIA (4)</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>COMPUPART STORE, AIRE (3)</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>BCO. AMERICA CENTRAL</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>ANTICIPO OBRA ELECTRICA</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>PORFIRIO MORILLO</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>PRIMA SERVIDOR DE COLOR</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>PRIMA iMAC 27" PARA NUEVO PUESTO DE ARTE</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>PROVEEDORES DEL EXTERIOR</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>SUMIFLEX </td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>LEASING T. Y LAMINADORA</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>GMG, COLOR</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>ZELAR CONNECTION</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>PAGOS OPERATIVOS NETOS</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>SUELDOS</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>ALQUILER LOCAL</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>ISSS</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>AFPs</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>SERVICIOS DE LIMPIEZA </td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>SERVICIOS DE JARDINERIA </td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>FRANCISCO GOMEZ, CONTABILIDAD</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>ALCALDIA DE SAN SALVADOR</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>NAHUM HENRIQUEZ</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>TRULYN, FUMIGACION</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>CAESS, CASA</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>CAESS, TRIFASICA</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>TELEFONOS</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>GASTOS/ FLETES</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>CAJA CHICA</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr>
	<tr>
		<td>TRANSPORTE SOLVENTE</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
		<td class="derecha">$0</td>
	</tr-->
	<tr>
		<th>TOTAL DISPONIBLE</th>
<?
foreach($Meses as $iMes => $nMes)
{
	if(!mostrar($iMes, $Rango))
	{
		continue;
	}
?>
		<th class="derecha">$0</th>
		<th class="derecha">$0</th>
<?
}
?>
	</tr>
</table>



<script>
	$('#flujo_rango').change(function()
	{
		window.location = '/conta/flujos/index/'+$('#flujo_rango').val();
	});
</script>