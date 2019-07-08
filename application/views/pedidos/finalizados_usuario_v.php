


<?=$Paginacion?>

<table class="tabular" style="width: 100%;">
	<tr>
		<th style="width: 95px;">Proceso</th>
		<th>Trabajo</th>
		<th style="width: 170px;">Fecha Inicio</th>
		<th style="width: 170px;">Fecha Fin</th>
		<th>Material Solicitado</th>
	</tr>
<?php

//print_r($Listado_Pedidos);

if(0 < count($Listado_Pedidos))
{
	foreach($Listado_Pedidos as $Pedido)
	{
?>
	<tr>
		<td><a href="/pedidos/pedido_detalle/index/<?=$Pedido['id_pedido']?>"><?=$Pedido['codigo_cliente'].'-'.$Pedido['proceso']?></a></td>
		<td><?=$Pedido['nombre']?></td>
		<td><?=('0000-00-00 00:00:00'==$Pedido['fecha_inicio'])?'':date(('d-m-Y H:i:s'), strtotime($Pedido['fecha_inicio']))?></td>
		<td><?=date(('d-m-Y H:i:s'), strtotime($Pedido['fecha_fin']))?></td>
		<td><?=$Pedido['producto']?></td>
	</tr>
<?php
	}
}
?>
</table>

<?=$Paginacion?>
