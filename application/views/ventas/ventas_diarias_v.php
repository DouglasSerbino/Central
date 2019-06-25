<script type="text/javascript" src="/html/js/venta.js?n=1"></script>
<script>
	$(function()
	{
		$("[name=fecha]").datepicker({dateFormat: 'yy-mm-dd', showButtonPanel: true});
	});
</script>
<div class="informacion">
	<form method='post' name='ventas_diarias' action='/ventas/ventas_diarias/index'>
		<input type="text" name="fecha" id="fecha" size="12" value="<?=$Fecha?>" readonly="readonly" />
		&nbsp; <input type="Submit" class="boton" value="Ver" />
	</form>
<?php
if($Fecha != '')
{
?>
	<table style='width: 80%;'>
		<tr>
			<th style='width: 25%;'>Cliente</th>
			<th style='width: 25%;'>Pedido</th>
			<th style='width: 25%;'>Venta</th>
			<th style='width: 10%; text-align: center;'>Facturado</th>
			<th>N Factura</th>
		</tr>
<?
$suma = 0;
foreach($mostrar_clientes as $Datos_clientes)
{
	$cliente = $Datos_clientes["nombre"];
?>
		<tr>
			<th colspan="5" style='background-color: #d7d7d7;'><?=$cliente?></th>
		</tr>
<?php		
		$suma_cli = 0;
		$a = 0;
		foreach($Datos_clientes['ventas'] as $Ventas_diarias)
		{
			$venta = $Ventas_diarias["venta"];
			$suma = $suma + $venta;
			$suma_cli = $suma_cli + $venta;
		
			$a++;
?>
		<tr>
			<td>&nbsp;</td>
			<td><?=$Ventas_diarias["sap"]?></td>
			<td>$ <?=$venta?></td>
			<td style='text-align: center;'><strong><?=$Ventas_diarias["confirmada"]?></strong></td>
			<td><strong><?=$Ventas_diarias["factura"]?></strong>
			</td>
		</tr>
<?php	
		}
?>
			<tr>
				<td colspan="2"><strong>Total Cliente: &nbsp; </strong></td>
				<td><strong>$ <?=number_format($suma_cli, 2)?></strong><br /></td>
				
			</tr>
<?php	
	}
?>
			<tr>
				<td colspan="2"><br /><strong>Total: &nbsp; </strong></td>
				<td><br><strong>$ <?=number_format($suma, 2)?></strong></td>
			</tr>
	</table>
<?php
}
?>
</div>