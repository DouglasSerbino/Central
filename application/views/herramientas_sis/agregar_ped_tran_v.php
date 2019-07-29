<script type="text/javascript" src="/html/js/inventario.js?n=1"></script>
<link rel="stylesheet" href="/html/css/pedido.css" />
<div id='contenedor'>
	<form method='post' id='agregar' action='/herramientas_sis/agregar_ped_tran/agregar' onsubmit="return validar('agregar');">
		<table>
		<tr>
			<td>Nombre del Material</td>
			<td><input type='text' name='nombre' value='<?=$info_material['nombre_material']?>' size='50px' disabled='disabled'>
				<input type='hidden' name='id_inventario' value='<?=$info_material['id_inventario_material']?>'></td>
		</tr>
			<tr>
				<td>N&uacute;mero de Orden</td>
				<td><input type='text' name='orden' id='orden' class='requ'>*</td>
			</tr>
			<tr>
				<td>Cantidad a ingresar</td>
				<td>
					<input type='text' name='cantidad' id='cantidad' class='requ'>
						<select name="tipo">
							<option value=''>----</option>
							<option value="IN2">IN2</option>
							<option value="PZA"?>PZA</option>
							<option value="GAL">GAL</option>
							<option value="ROL">ROL</option>
							<option value="JGO">JGO</option>
							<option value="RES">RES</option>
						</select>*
				</td>
			</tr>
			<tr>
				<td>Detalle del pedido</td>
				<td><textarea name='detalle' style='width: 320px; height: 50px;' class='requ'></textarea>*</td>
			</tr>
			<tr>
				<td colspan='2' style='text-align: center;'> 
					<input type='submit' value='Agregar'>
				</td>
			</tr>
		</table>
	</form>
</div>