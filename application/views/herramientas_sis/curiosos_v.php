<table class="tabular" style="width: 100%;">
	<tr>
		<th>Proceso</th>
		<th>Producto</th>
		<th>Entrada</th>
		<th>Entrega</th>
		<th>Real</th>
	</tr>
<?php
foreach($Curiosos['Pedidos'] as $Pedido)
{
?>
	<tr>
		<td>
			<a href="javascript:ventana_externa('/pedidos/pedido_detalle/index/<?=$Pedido['id_pedido']?>');" class="iconos iexterna toolizq"><span>Ver detalle en ventana externa.</span></a>
			<?=$Pedido['codigo_cliente'].'-'.$Pedido['proceso']?>
		</td>
		<td><?=$Pedido['nombre']?></td>
		<td><?=date('d-m-Y', strtotime($Pedido['fecha_entrada']))?></td>
		<td><?=date('d-m-Y', strtotime($Pedido['fecha_entrega']))?></td>
		<td><?=($Pedido['fecha_reale']!='0000-00-00')?date('d-m-Y', strtotime($Pedido['fecha_reale'])):'0000-00-00'?></td>
	</tr>
<?php
}
?>
</table>



