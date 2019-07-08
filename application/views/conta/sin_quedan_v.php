

<div id="contenido-conta">
	
	
	<div id="conta_quedan">
		
		<strong>AGREGAR QUEDAN</strong>
		<br />
		<span id="conta_qued_trabajo"></span>
		<input type="hidden" id="conta_qued_pedido" />
		
		<br /><br />
		<table>
			<tr>
				<td>Fecha de Cobro</td>
				<td><input type="text" id="conta_qued_fecha" /></td>
			</tr>
		</table>
		
		<br />
		<input type="button" id="conta_qued_ok" value="Confirmar" />
		&nbsp; &nbsp;
		<input type="button" id="conta_qued_na" value="Cancelar" />
		
		<br /><br />
		
	</div>
	
	
	<table class="tabular" style="width:100%;">
<?php
$Contador = 1;
$Total_Venta = 0;
foreach($Sin_Quedan['clientes'] as $iCliente => $nCliente)
{
?>
		<tr>
			<th colspan="2"> &nbsp; <?=$nCliente?></th>
			<th>Entregado</th>
			<th>Factura</th>
			<th class="derecha">Cotizado</th>
			<th class="derecha">Opciones</th>
		</tr>
<?php
	foreach($Sin_Quedan['trabajos'][$iCliente] as $Fila)
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
			<td class="derecha">
				<a href="/conta/modi_factura/index/<?=$Fila['id_pedido']?>/<?=$Anho?>/<?=$Mes?>/sin_quedan" class="iconos ieditar toolder">
					<span>Editar Factura</span>
				</a>
				<a href="#" class="iconos iterminado toolder" info="<?=$Fila['id_pedido']?>" venta="<?=$Fila['venta']?>" proc="<?=$Fila['codigo_cliente'].'-'.$Fila['proceso']?>" nomb="<?=$Fila['nombre']?>">
					<span>Confirmar Quedan</span>
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
			<th colspan="4" class="derecha">TOTAL</th>
			<th class="derecha">$<?=number_format($Total_Venta, 2)?></th>
			<th>&nbsp;</th>
		</tr>
	</table>
	
</div>






<script>
	$('#contenido-conta .iterminado').click(function()
	{
		
		$('#conta_qued_trabajo').empty().append($(this).attr('proc')+'<br />'+$(this).attr('nomb'));
		$('#conta_qued_pedido').val($(this).attr('info'))
		$('#conta_quedan').show('blind');
		$('#conta_qued_fecha').val('').focus();
		
	});
	
	$('#conta_qued_ok').click(function()
	{
		if('' == $('#conta_qued_fecha').val())
		{
			$('#conta_qued_fecha').focus();
			return false;
		}
		
		var datos = 'pedi='+$('#conta_qued_pedido').val();
		datos = datos + '&fecha='+$('#conta_qued_fecha').val();
		
		$.ajax({
			type: "POST",
			url: "/conta/sin_quedan/quedan",
			data: datos,
			success: function(msg)
			{
				if(msg == "ok")
				{
					$('#fcont-'+$('#conta_qued_pedido').val()).hide();
					$('#conta_quedan').hide();
				}
				else alert("Ocurrio un error: "+msg+".<br />Haga una captura de pantalla para realizar una verificacion del problema.");
			},
			error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
		});
	});
	
	
	$('#conta_qued_na').click(function()
	{
		$('#conta_quedan').hide();
	});
	
	
	$("#conta_qued_fecha").datepicker({dateFormat: 'dd-mm-yy', showButtonPanel: true});
	
	
</script>

