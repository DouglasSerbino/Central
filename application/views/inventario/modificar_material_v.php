<?
if(count($Id_proveedor) != 0)
{
	//print_r($Id_proveedor);
	$id_inventario_proveedor = $Id_proveedor['id_inventario_proveedor'];
}
else{
	$id_inventario_proveedor = '0';
}
//print_r($Mostrar_materiales);
if($Mostrar_materiales > 0)
{
	foreach($Mostrar_materiales as $Datos_material)
	{
		$tipo = $Datos_material["tipo"];
		$equipo = $Datos_material["id_inventario_equipo"];
	
?>
<style>
	.posicion
	{
		position: absolute;
		right: 300px;
		top: 200px;
	}
</style>
<div class="informacion">
	
	<form name="miform" action="/inventario/modificar_material/modificar_datos" method="post">
		<input type="hidden" name="id_inventario_material" value="<?=$Id_material?>" />
		<table class="tabla_100">
			<tr>
				<td>C&oacute;digo:</td>
				<td>
					<input type="text" name="codigo" size="15" value="<?=$Datos_material["codigo_sap"]?>" />
				</td>
				<td>Pa&iacute;s</td>
				<td>
				<select name="mpais">
<?
foreach($Paises_C as $pCod => $pNomb)
{
?>
					<option value="<?=$pCod?>"<?=($pCod==$Datos_material["mat_pais"])?' selected="selected"':''?>><?=$pNomb?></option>
<?
}
?>
				</select>
				<span class="pais_<?=$Datos_material["mat_pais"]?>" id="span_pais"></span>
				</td>
			</tr>
			<tr>
				<td>
					 Cantidad/Unidad:
				</td>
				<td>
					<input type="text" name="cantidad_unidad" size="15" value="<?=$Datos_material["cantidad_unidad"]?>" />
				</td>
				<td>Valor/Unidad $:</td>
				<td>
					<input type="text" name="valor" size="15" value="<?=$Datos_material["valor"];?>" />
				</td>
			</tr>
			<tr>
				<td>Tipo:</td>
				<td>
					<select name="tipo">
						<option value="IN2" <? if($tipo == "IN2") echo "selected=\"selected\""; ?>>IN2</option>
						<option value="PZA" <? if($tipo == "PZA") echo "selected=\"selected\""; ?>>PZA</option>
						<option value="PLGO" <? if($tipo == "PLGO") echo "selected=\"selected\""; ?>>PLGO</option>
						<option value="GAL" <? if($tipo == "GAL") echo "selected=\"selected\""; ?>>GAL</option>
						<option value="ROL" <? if($tipo == "ROL") echo "selected=\"selected\""; ?>>ROL</option>
						<option value="JGO" <? if($tipo == "JGO") echo "selected=\"selected\""; ?>>JGO</option>
						<option value="RES" <? if($tipo == "RES") echo "selected=\"selected\""; ?>>RES</option>
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
						<option value="<?=$Datos_proveedor['id_inventario_proveedor']?>" <?=($id_inventario_proveedor==$Datos_proveedor['id_inventario_proveedor'])?' selected="selected"':''?>><?=$Datos_proveedor['proveedor_nombre']?></option>
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
						<option value="mt"<?=('mt'==$Datos_material["mp_mt"])?' selected="selected"':''?>>Materiales</option>
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
						<option value="<?=$id_equipo?>" <?=($id_equipo==$equipo)?' selected="selected"':''?>><?=$Datos_equipo["nombre_equipo"]?></option>
<?php
}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Pulgadas por placa:</td>
				<td><input type="text" name="numero_individual" size="15" value="<?=$Datos_material["numero_individual"]?>" /></td>
				<td>Placas por caja:</td>
				<td><input type="text" name="numero_cajas" size="15" value="<?=$Datos_material["numero_cajas"]?>" /></td>
			</tr>
			<tr>
				<td>Nombre:</td>
				<td colspan="3"><input type="text" name="nombre" size="70" value="<?=$Datos_material["nombre_material"]?>" /></td>
			</tr>
			<tr>
				<td>Observaciones:</td>
				<td colspan="3"><input type="text" name="observacion" size="70" value="<?=$Datos_material["observacion"]?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td><td colspan="3"><input type="submit" class="boton" value="Modificar" /></td>
			</tr>
		</table>
		
	</form>
	
</div>

<?php
	}
}
?>


<script>
	
	$('[name="mpais"]').change(function()
	{
		$('#span_pais').removeClass();
		$('#span_pais').addClass('pais_'+$(this).val());
	});

</script>