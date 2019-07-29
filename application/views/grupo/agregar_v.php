<form id="agr_grupo" method="POST" action="/grupo/agregar/guardar" onsubmit="return validar('agr_grupo');">
	<table>
		<tr>
			<td>Nombre del Grupo*</td>
			<td><input type="text" name="nombre" id="id_nombre" class="requ" /></td>
		</tr>
		<tr>
			<td>Abreviatura*</td>
			<td><input type="text" name="abrev" id="id_abrev" class="requ" /></td>
		</tr>
		<tr>
			<td>Tipo de Grupo</td>
			<td>
				<select name="tipo">
					<option value="pri">Principal</option>
					<option value="sub">Sub Grupo</option>
					<option value="cli">Cliente</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Cliente Referencia</td>
			<td>
				<select name="cliente">
					<option value="--">No Asignar</option>
<?php
foreach($Clientes as $Cliente)
{
?>
					<option value="<?=$Cliente['id_cliente']?>"><?=$Cliente['codigo_cliente']?> - <?=$Cliente['nombre']?></option>
<?php
}
?>
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="submit" value="Guardar" />
			</td>
		</tr>
	</table>
</form>