
<table class="tabular" style="width:100%;">
	<tr>
		<th>Proceso</th>
		<th>Trabajo</th>
		<th>Finalizado</th>
		<th>Consumo</th>
	</tr>
<?
foreach($Notificaciones as $Fila)
{
?>
	<tr>
		<td><?=$Fila['codigo_cliente'].'-'.$Fila['proceso']?></td>
		<td><?=$Fila['nombre']?></td>
		<td><?=date('d-m-Y', strtotime($Fila['fecha_reale']))?></td>
		<td class="derecha"><?=number_format($Fila['cantidad'], 0)?></td>
	</tr>
<?
}
?>
</table>