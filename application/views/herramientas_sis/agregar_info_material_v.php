<?php
foreach($Mostrar_materiales as $Datos)
{
 $nombre_material = $Datos['nombre_material'];
}
?>
<div class="informacion">
 <form method="post" action='/herramientas_sis/agregar_info_material/agregar_info'>
 <input type="hidden" name="id_inventario_material" value="<?=$Id_inventario_material?>" />
	 <table>
		<tr>
		 <td colspan='2' style='font-size: 15px;'><strong><?=strtoupper($nombre_material)?></strong><br />&nbsp;</td>
		</tr>
		<tr>
		 <td><strong>Proveedor</strong></td>
		 <td>
			<select name="proveedor">
			 <option value="0">-- Seleccione --</option>
<?php
foreach($Mostrar_Proveedor as $Datos_proveedor)
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
		<td><strong>Espesor</strong></td>
		<td>
		 <select name="cod_plancha">
		  <option value=''>-- Seleccione --</option>
<?php
foreach($tipo_planchas as $Datos_planchas)
{
?>
			<option value='<?=$Datos_planchas["cod_plancha"]?>'><?=$Datos_planchas["grosor"]."&nbsp;".$Datos_planchas["tipo"]?></option>
<?php
}

?>
			</select> &nbsp;
		 </td>
		</tr>
		<tr>
			<td><strong>IN2 &oacute; PZAS por Placa</strong></td>
			<td><input type='text' name='numero_individual'>
					<select name="tipo">
						<option value="IN2">IN2</option>
						<option value="PZA" >PZA</option>
						<option value="PLGO" >PLGO</option>
						<option value="GAL" >GAL</option>
						<option value="ROL" >ROL</option>
						<option value="JGO" >JGO</option>
						<option value="RES" >RES</option>
					</select></td>
		</tr>
		<tr>
			<td><strong>Placas por Caja</strong></td>
			<td><input type='text' name='numero_cajas'></td>
		</tr>
		<tr>
		 <td><strong>Tipo de Plancha</strong></td>
		 <td>
			<select name="plancha_tipo">
			 <option value="0">-- Seleccione --</option>
<?php
foreach($plancha_tipo as $Datos_tipo)
{
?>
			 <option value="<?=$Datos_tipo["cod_tipo"];?>"><?=$Datos_tipo["nombre_tipo"]?></option>
<?php
}
?>
			</select>
		 </td>
		</tr>
		<tr>
			<td><strong>Tama&ntilde;o</strong></td>
			<td><input type='text' name='tamanho'></td>
		</tr>
		<tr>
		 <td colspan='2' style='text-align: center;'><br /><input type='submit' value='Guardar'></td>
		</tr>
	 </table>
 </form>
</div>