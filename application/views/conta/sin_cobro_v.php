

<div id="contenido-conta">
	
	<table class="tabular" style="width:100%;">
<?
$Contador = 1;
$Total_Venta = 0;
foreach($Sin_Cobro['clientes'] as $iCliente => $nCliente)
{
?>
		<tr>
			<th colspan="2"> &nbsp; <?=$nCliente?></th>
			<th>Entregado</th>
			<th>Factura</th>
			<th class="derecha">Cotizado</th>
			<th class="derecha">F.Cobro</th>
			<th class="derecha">Opciones</th>
		</tr>
<?
	foreach($Sin_Cobro['trabajos'][$iCliente] as $Fila)
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
			<td class="derecha">
				<a href="/conta/modi_factura/index/<?=$Fila['id_pedido']?>/<?=$Anho?>/<?=$Mes?>/sin_cobro" class="iconos ieditar toolder">
					<span>Editar Factura</span>
				</a>
				<a href="#" class="iconos iterminado toolder" info="<?=$Fila['id_pedido']?>" venta="<?=$Fila['venta']?>" proc="<?=$Fila['codigo_cliente'].'-'.$Fila['proceso']?>" nomb="<?=$Fila['nombre']?>">
					<span>Confirmar Cobro</span>
				</a>
			</td>
		</tr>
<?
		$Contador++;
		$Total_Venta += $Fila['venta'];
	}
}
?>
		<tr>
			<th colspan="5" class="derecha">TOTAL</th>
			<th class="derecha">$<?=number_format($Total_Venta, 2)?></th>
			<th>&nbsp;</th>
		</tr>
	</table>
	
</div>






<script>
	$('#contenido-conta .iterminado').click(function()
	{
		
		if(!confirm('Confirmar Cobro?'))
		{
			return false;
		}
		
		var pedi = $(this).attr('info');
		var datos = 'pedi='+$(this).attr('info');
		
		$.ajax({
			type: "POST",
			url: "/conta/sin_cobro/cobrar",
			data: datos,
			success: function(msg)
			{
				if(msg == "ok")
				{
					$('#fcont-'+pedi).hide();
				}
				else alert("Ocurrio un error: "+msg+".<br />Haga una captura de pantalla para realizar una verificacion del problema.");
			},
			error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
		});
	});
	
	
</script>

