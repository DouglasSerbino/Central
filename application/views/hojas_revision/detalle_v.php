
<table>
	<tr>
		<td>Proceso:</td><th><?=$Info_Proceso['codigo_cliente'].'-'.$Info_Proceso['proceso']?></th></tr>
	<tr>
		<td>Cliente:</td>
		<th><?=$Info_Proceso['nombre']?></th></tr>
	<tr>
		<td>Producto:</td>
		<th><?=$Info_Proceso['nombre_proceso']?></th>
	</tr>
	<tr>
		<td>Revisado por:</td>
		<th><?=$Detalle['nombre'].' &nbsp; '.$Detalle['fecha']?></th>
	</tr>
</table>


<br />


<?php
$Revision = json_decode($Detalle['revision'], true);
foreach ($Items_Revision as $Index => $Item)
{
?>
<br />
<table style="width: 75%;" class="tabular">
	<tr>
		<th style="width: 80%;"><?=$Item['item']?></th>
		<th>Revisado</th>
	</tr>
<?php
	foreach ($Item['sub_item'] as $SubIndex => $Sub_Item)
	{
?>
	<tr>
		<td><?=$Sub_Item['sub_item']?></td>
		<td><?=(isset($Revision[$SubIndex])?$Revision[$SubIndex]:'')?></td>
	</tr>
<?php
	}
?>
</table>
<?php
}
?>


<br />


<strong>OBSERVACIONES</strong>
<br />
<?=nl2br($Detalle['observacion'])?>

