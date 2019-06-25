
<form id="crear_menu" action="/menu/crear/menu" method="post">
	
	<table>
		<tr>
			<td>Etiqueta</td>
			<td><input type="text" name="etiqueta" value="" /></td>
		</tr>
		<tr>
			<td>Enlace</td>
			<td><input type="text" name="enlace" value="" /></td>
		</tr>
		<tr>
			<td>Men&uacute;</td>
			<td>
				<select name="grupo">
					<option value="0">Principal</option>
<?
foreach($Menu_Padre as $Menu)
{
?>
					<option value="<?=$Menu['id_menu']?>"><?=$Menu['etiqueta']?></option>
<?
}
?>
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="Guardar" /></td>
		</tr>
	</table>
	
</form>


