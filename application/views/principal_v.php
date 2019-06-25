<?php
/*****************************************
 *DANIEL NO SE TE OCURRA BORRAR EL CODIGO QUE PUEDA TENER ESTA PAGINA
 *LA VAS A REGAR SI LO HACES Y TE VA A TOCAR HACERLO VOS...
****************************************
?>
<table class='tabular' style='width: 90%'>
	<tr>
		<th>Proceso</th>
		<th>Descripcion</th>
		<th>Fecha de entrega</th>
		<th>Fecha Real</th>
		<th>Estado</th>
	</tr>
<?php
$atra = 0;
$tie = 0;
foreach($prueba as $Datos)
{
?>
	<tr>
		<td><?=$Datos['codigo_cliente']?>-<?=$Datos['proceso']?></td>
		<td style='width: 50%'><?=$Datos['nombre']?></td>
		<td><?=$Datos['fecha_entrega']?></td>
		<td><?=$Datos['fecha_reale']?></td>
		<td>
			<?php
			if($Datos['fecha_reale'] > $Datos['fecha_entrega'])
			{
				$atra++;
				echo 'Atrasado';
			}
			else
			{
				$tie++;
				echo 'Tiempo';
			}
			?>
		</td>
	</tr>
<?php
}
?>
<tr>
	<th colspan='5' style='text-align: right;'>Trabajos a Tiempo <?=$tie?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th>
</tr>
<tr>
	<th colspan='5' style='text-align: right;'>Trabajos Atrasado <?=$atra?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
</tr>
</table>

*/

?>