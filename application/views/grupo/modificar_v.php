<form method="POST" action="/grupo/modificar/modificar_datos">
	<input type="hidden" name="id_grupo" value="<?=$Grupos["id_grupo"]?>" />
	<table>
		<tr>
			<td>Nombre del Grupo</td>
			<td><input type="text" name="nombre" id="id_nombre" value="<?=$Grupos["nombre_grupo"]?>" /></td>
		</tr>
		<tr>
			<td>Abreviatura</td>
			<td><input type="text" name="abrev" id="id_abrev" value="<?=$Grupos["abreviatura"]?>" /></td>
		</tr>
		<tr>
			<td>Tipo de Grupo</td>
			<td>
				<select name="tipo">
					<option value="pri"<?=('pri'==$Grupos['tipo_grupo'])?' selected="selected"':''?>>Principal</option>
					<option value="sub"<?=('sub'==$Grupos['tipo_grupo'])?' selected="selected"':''?>>Sub Grupo</option>
					<option value="cli"<?=('cli'==$Grupos['tipo_grupo'])?' selected="selected"':''?>>Cliente</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Cliente Referencia</td>
			<td>
				<select name="cliente">
					<option value="--">No Asignar</option>
<?
foreach($Clientes as $Cliente)
{
?>
					<option value="<?=$Cliente['id_cliente']?>"<?=($Cliente['id_cliente']==$Grupos['id_cliente'])?' selected="selected"':''?>><?=$Cliente['codigo_cliente']?> - <?=$Cliente['nombre']?></option>
<?
}
?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center;">
				<input type="submit" value="Modificar" />
			</td>
		</tr>
	</table>
</form>