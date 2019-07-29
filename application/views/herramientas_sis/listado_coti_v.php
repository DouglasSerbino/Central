<table class='tabular' width='250px;'>
	<th>Pedido Sap</th>
	<th colspan='2'>Proceso</th>
<?php
foreach($Listado as $Datos)
{
?>
	<tr>
		<td><?=$Datos['sap']?></td>
		<td><?=$Datos['codigo_cliente']?>-<?=$Datos['proceso']?></td>
		<td><a onclick='eliminar_coti(<?=$Datos['id_pedido']?>)' class="iconos iterminado toolder"><span>Finalizar</span></a></td>
	</tr>
<?php
}
?>
</table>
<script>
	function eliminar_coti(id_pedido)
	{
		if(confirm("Desea Finalizar?"))
		{
			window.location = "/herramientas_sis/modi_coti/eliminar/"+id_pedido;
		}
	}
</script>

<style>
	.posicion
	{
		position: absolute;
		left: 500px;
		height: 190px;
		background: #ffffff;
	}
</style>
<?php
/*
<div class="posicion" style='display: block'>
	<form method='post' action='herramientas_sis/modi_coti/modificar_sap'>
		<table>
			<tr>
				<td>Pedido Sap</td>
				<td><input type='text' name='pedsap' id='pedsap'></td>
			</tr>
			<tr>
				<td>Ordenes</td>
				<td>
					<?php
						for($a = 1; $a< 5; $a++)
						{
							//<?=$Orden[$a]?>
						<input type='text' name='orden<?=$a?>' id='orden<?=$a?>' value='' ><br />
					<?php
						}
					?>
				</td>
			</tr>
			<tr>
				<td>Venta</td>
				<td><input type='text' name='venta' id='venta' value='<?=$Datos['venta']?>'</td>
			</tr>
			
		</table>
	</form>
</div>

*/