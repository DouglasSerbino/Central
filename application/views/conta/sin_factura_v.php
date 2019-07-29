

<div id="contenido-conta">
	
	
	<div id="conta_facturar">
		
		<strong>FACTURAR PEDIDO</strong>
		<br />
		<span id="conta_fact_trabajo"></span>
		<input type="hidden" id="conta_fact_pedido" />
		
		<br /><br />
		<table>
			<tr>
				<td>Factura</td>
				<td><input type="text" id="conta_fact_factura" /></td>
			</tr>
			<tr>
				<td>Venta</td>
				<td><input type="text" id="conta_fact_venta" /></td>
			</tr>
		</table>
		
		<br />
		<input type="button" id="conta_fact_ok" value="Facturar" />
		&nbsp; &nbsp;
		<input type="button" id="conta_fact_na" value="Cancelar" />
		
		<br /><br />
		
	</div>
	
	
	<table class="tabular" style="width:100%;">
<?php
$Contador = 1;
$Total_Venta = 0;
foreach($Sin_Factura['clientes'] as $iCliente => $nCliente)
{
?>
		<tr>
			<th colspan="2"> &nbsp; <?=$nCliente?></th>
			<th>Entregado</th>
			<th class="derecha">Cotizado</th>
			<th class="derecha">Opciones</th>
		</tr>
<?php
	foreach($Sin_Factura['trabajos'][$iCliente] as $Fila)
	{
?>
		<tr id="fcont-<?=$Fila['id_pedido']?>">
			<td>
				<?=$Contador?>)
				<a href="/pedidos/pedido_detalle/index/<?=$Fila['id_pedido']?>" target="_blank"><?=$Fila['codigo_cliente'].'-'.$Fila['proceso']?><a>
			</td>
			<td><?=$Fila['nombre']?></td>
			<td><?=date('d-m-Y', strtotime($Fila['fecha_reale']))?></td>
			<td class="derecha">$<?=number_format($Fila['venta'], 2)?></td>
			<td class="derecha">
				<a href="/conta/modi_factura/index/<?=$Fila['id_pedido']?>/<?=$Anho?>/<?=$Mes?>/sin_factura" class="iconos ieditar toolder">
					<span>Editar Factura</span>
				</a>
				<a href="#" class="iconos iterminado toolder" info="<?=$Fila['id_pedido']?>" venta="<?=$Fila['venta']?>" proc="<?=$Fila['codigo_cliente'].'-'.$Fila['proceso']?>" nomb="<?=$Fila['nombre']?>">
					<span>Pedido Facturado</span>
				</a>
			</td>
		</tr>
<?php
		$Contador++;
		$Total_Venta += $Fila['venta'];
	}
}
?>
		<tr>
			<th colspan="3" class="derecha">TOTAL</th>
			<th class="derecha">$<?=number_format($Total_Venta, 2)?></th>
			<th>&nbsp;</th>
		</tr>
	</table>
	
</div>






<script>
	$('#contenido-conta .iterminado').click(function()
	{
		
		$('#conta_fact_trabajo').empty().append($(this).attr('proc')+'<br />'+$(this).attr('nomb'));
		$('#conta_fact_venta').val($(this).attr('venta'));
		$('#conta_fact_pedido').val($(this).attr('info'))
		$('#conta_facturar').show('blind');
		$('#conta_fact_factura').val('').focus();
		
	});
	
	$('#conta_fact_ok').click(function()
	{
		if('' == $('#conta_fact_factura').val())
		{
			$('#conta_fact_factura').focus();
			return false;
		}
		
		var datos = 'pedi='+$('#conta_fact_pedido').val();
		datos = datos + '&venta='+$('#conta_fact_venta').val();
		datos = datos + '&factura='+$('#conta_fact_factura').val();
		
		$.ajax({
			type: "POST",
			url: "/conta/sin_factura/facturar",
			data: datos,
			success: function(msg)
			{
				if(msg == "ok")
				{
					$('#fcont-'+$('#conta_fact_pedido').val()).hide();
					$('#conta_facturar').hide();
				}
				else alert("Ocurrio un error: "+msg+".<br />Haga una captura de pantalla para realizar una verificacion del problema.");
			},
			error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
		});
	});
	
	
	$('#conta_fact_na').click(function()
	{
		$('#conta_facturar').hide();
	});
	
</script>

