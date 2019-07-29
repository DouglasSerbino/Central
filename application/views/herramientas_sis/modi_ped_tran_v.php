<script type="text/javascript" src="/html/js/inventario.js?n=1"></script>
<?php
foreach($info_material as $Datos)
{
	$id_inventario_material = $Datos['id_inventario_material'];
	$nombre_material = $Datos['nombre_material'];
	$orden = $Datos['orden'];
	$cantidad = $Datos['cantidad_solicitada'];
	$detalle = $Datos['detalle'];
	$tipo = $Datos['tipo'];
}
?>
<div id='contenedor'>
	<form method='post' action='/herramientas_sis/agregar_ped_tran/modificar' id='modificar' onsubmit="return validar('modificar')">
		<input type='hidden' name='orden_ant' value='<?=$orden?>'>	
		<table>
		<tr>
			<td>Nombre del Material</td>
			<td><input type='text' name='nombre' value='<?=$nombre_material?>' size='50px' disabled='disabled'>
				<input type='hidden' name='id_inventario' value='<?=$id_inventario_material?>'></td>
		</tr>
			<tr>
				<td>N&uacute;mero de Orden</td>
				<td><input type='text' name='orden' id='orden' value='<?=$orden?>' class="requ">*</td>
			</tr>
			<tr>
				<td>Cantidad a ingresar</td>
				<td>
					<input type='text' name='cantidad' id='cantidad' value='<?=$cantidad?>' class="requ">*
						<select name="tipo" id='tipo'>
							<option value=''>----</option>
							<option value="IN2" <?=($tipo=='IN2')?' selected="selected"':''?>>IN2</option>
							<option value="PZA" <?=($tipo=='PZA')?' selected="selected"':''?>>PZA</option>
							<option value="GAL" <?=($tipo=='GAL')?' selected="selected"':''?>>GAL</option>
							<option value="ROL" <?=($tipo=='ROL')?' selected="selected"':''?>>ROL</option>
							<option value="JGO" <?=($tipo=='JGO')?' selected="selected"':''?>>JGO</option>
							<option value="RES" <?=($tipo=='RES')?' selected="selected"':''?>>RES</option>
						</select>
				</td>
			</tr>
			<tr>
				<td>Detalle del pedido</td>
				<td><textarea name='detalle' style='width: 320px; height: 75px; resize: none;' class="requ"><?=$detalle?></textarea>*</td>
			</tr>
			<tr>
				<td colspan='2' style='text-align: center;'> 
					<input type='submit' value='Modificar'>
				</td>
			</tr>
		</table>
	</form>
</div>