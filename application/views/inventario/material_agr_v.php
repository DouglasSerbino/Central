<div class="informacion">
	<form name="miform" id='agregar_material' action="/inventario/material_agr/agregar_material" method="post" onsubmit="return validar('agregar_material');">
		<table>
			<tr>
				<td>C&oacute;digo:</td>
				<td><input type="text" name="codigo" size="15" class="requ" />* </td>
				<td>Pa&iacute;s</td>
				<td>
				<select name="mpais">
<?
foreach($Paises_C as $pCod => $pNomb)
{
?>
					<option value="<?=$pCod?>"><?=$pNomb?></option>
<?
}
?>
				</select>
				<span class="pais_sv" id="span_pais"></span>
				</td>
			</tr>
			<tr>
				<td>Cantidad/Unidad:</td>
				<td>
					<input type="text" name="cantidad_unidad" size="15" class="requ" >*
				</td>
				<td>Valor/Unidad $:</td>
				<td><input type="text" name="valor" size="15" class="requ" /> *</td>
			</tr>
			<tr>
				<td>Tipo:</td>
				<td>
					<select name="tipo">
						<option value=''>----</option>
						<option value="IN2">IN2</option>
						<option value="PZA"?>PZA</option>
						<option value="PLGO">PlGO</option>
						<option value="GAL">GAL</option>
						<option value="ROL">ROL</option>
						<option value="JGO">JGO</option>
						<option value="RES">RES</option>
					</select>
				</td>
				<td>Proveedor:</td>
				<td>
					<select name="proveedor">
						<option value="0">Proveedor Principal</option>
<?php
foreach($Mostrar_proveedor as $Datos_proveedor)
{
?>
						<option value="<?=$Datos_proveedor['id_inventario_proveedor']?>"><?=$Datos_proveedor['proveedor_nombre']?></option>
<?php
}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Tipo de Material:</td>
				<td>
					<select name="mp_mt">
						<option value="mp">Materia Prima</option>
						<option value="mt">Materiales</option>
					</select>
				</td>
				<td>Area:</td>
				<td>
					<select name="equipo">
						<option value="0">Seleccionar</option>
<?
foreach($Mostrar_equipos as $Datos_equipo)
{
	$id_equipo = $Datos_equipo["id_inventario_equipo"];
?>
						<option value="<?=$id_equipo?>"><?=$Datos_equipo["nombre_equipo"]?></option>
<?php
}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Pulgadas por placa:</td>
				<td><input type="text" name="numero_individual" size="15" /></td>
				<td>Placas por caja:</td>
				<td><input type="text" name="numero_cajas" size="15"/></td>
			</tr>
			<tr>
				<td>Nombre:</td>
				<td colspan="3"><input type="text" name="nombre" size="70" class="requ" />*</td>
			</tr>
			<tr>
				<td>Observaciones:</td>
				<td colspan="3"><input type="text" name="observacion" size="70" class="requ"/>*</td>
			</tr>
			<tr>
				<td></td>
				<td colspan="3"><input type="submit" class="boton" value="Agregar" /></td>
			</tr>
		</table>
		
	</form>
	
</div>

<script>
	
	$('[name="mpais"]').change(function()
	{
		$('#span_pais').removeClass();
		$('#span_pais').addClass('pais_'+$(this).val());
	});

</script>