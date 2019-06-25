

<div id="contenido-conta">
	
	<table class="tabular" style="width:100%;">
<?
$Contador = 1;
$Total_Venta = 0;
foreach($Cobrado['clientes'] as $iCliente => $nCliente)
{
?>
		<tr>
			<th colspan="2"> &nbsp; <?=$nCliente?></th>
			<th>Entregado</th>
			<th>Factura</th>
			<th class="derecha">Cotizado</th>
			<th class="derecha">F.Cobro</th>
		</tr>
<?
	foreach($Cobrado['trabajos'][$iCliente] as $Fila)
	{
?>
		<tr id="fcont-<?=$Fila['id_pedido']?>">
			<td>
				<?=$Contador?>)
				<a href="/pedidos/pedido_detalle/index/<?=$Fila['id_pedido']?>" target="_blank"><?=$Fila['codigo_cliente'].'-'.$Fila['proceso']?><a>
			</td>
			<td><?=$Fila['nombre']?></td>
			<td><?=date('d-m-Y', strtotime($Fila['fecha_reale']))?></td>
			<td><?=$Fila['sap']?></td>
			<td class="derecha">$<?=number_format($Fila['venta'], 2)?></td>
			<td class="derecha"><?=date('d-m-Y', strtotime($Fila['factura']))?></td>
		</tr>
<?
		$Contador++;
		$Total_Venta += $Fila['venta'];
	}
}
?>
		<tr>
			<th colspan="4" class="derecha">TOTAL</th>
			<th class="derecha">$<?=number_format($Total_Venta, 2)?></th>
			<th>&nbsp;</th>
		</tr>
	</table>
	
</div>





