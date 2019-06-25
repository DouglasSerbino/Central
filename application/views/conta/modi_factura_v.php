
<form action="/conta/modi_factura/actualizar/<?=$Id_Pedido?>/<?=$Anho?>/<?=$Mes?>/<?=$Pagina?>" method="post">

	<table>
		<tr>
			<th>Cliente</th>
			<td><?=$Factura['nom_clie']?></td>
		</tr>
		<tr>
			<th>Proceso</th>
			<td><?=$Factura['codigo_cliente'].'-'.$Factura['proceso']?></td>
		</tr>
		<tr>
			<th>Factura</th>
			<td><input type="text" name="factura" value="<?=$Factura['sap']?>" /></td>
		</tr>
		<tr>
			<th>Fecha</th>
			<td><input type="text" name="fecha" readonly="readonly" value="<?=(''==$Factura['fecha'])?'':date('d-m-Y', strtotime($Factura['fecha']))?>" /></td>
		</tr>
		<tr>
			<th>Venta</th>
			<td>$<input type="text" name="venta" value="<?=$Factura['venta']?>" /></td>
		</tr>
	</table>

	<input type="button" value="Cancelar" id="modi_cancelar" />
	<input type="submit" value="Guardar" />

</form>


<script>
	$("[name=fecha]").datepicker({dateFormat: 'dd-mm-yy', showButtonPanel: true});

	$('#modi_cancelar').click(function()
	{
		window.location = '/conta/<?=$Pagina?>/index/<?=$Anho?>/<?=$Mes?>';
	})
</script>
