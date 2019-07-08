<div class="informacion">
	<form name="miform" id='agregar_material' action="/inventario/material_agr/agregar_material" method="post" onsubmit="return validar('agregar_material');">
		<table> 
			<tr>
				<td>Producto:</td>
				<td><input type="text" name="codigo" size="15" class="requ" />* </td>
				<td>Material</td>
				<td>
				<select name="mmaterial">
				<?php
				foreach($Material_C as $mMaterial => $pNomb)
				{
				?>
					<option value="<?php=$mMaterial?>"><?=$pNomb?></option>
				<?php
				}
				?>
				</select>
				<span class="pais_sv" id="span_pais"></span>
				</td>
			</tr>
			<tr>
				<td>Cantidad:</td>
				<td>
					<input type="text" name="cantidad_unidad" size="15" class="requ" >*
				</td>
			</tr>
			<tr>
				<td></td>
				<td colspan="3"><input type="submit" class="boton" value="Agregar Producto" /></td>
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