


<div id="datos_vent_clie">


	<table class="tabular" style="width: 750px;">
		<tr>
			<th style="width: 100px;">Proceso</th>
			<th>Trabajo</th>
			<th class="numero" style="width: 100px;">Venta</th>
			<th class="numero" style="width: 100px;">Factura</th>
		</tr>
		<tr>
<?php
$Total_Venta = 0;
foreach($Vent_Clie as $Fila)
{
	$Total_Venta += $Fila['venta'];
?>
		<tr>
			<td><?=$Fila['codigo_cliente'].'-'.$Fila['proceso']?></td>
			<td><?=$Fila['nombre']?></td>
			<td class="numero vent100">$<?=number_format($Fila['venta'], 2)?></td>
			<td class="numero"><?=$Fila['es_factura']?></td>
		</tr>
<?php
}
?>
		<tr>
			<th class="numero" colspan="2">TOTAL</th>
			<th class="numero vent100">$<?=number_format($Total_Venta, 2)?></th>
			<th>&nbsp;</th>
		</tr>
	</table>
	
</div>


