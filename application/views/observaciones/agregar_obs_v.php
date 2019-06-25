
<form action="/observaciones/agregar" method="post" name="miform">
	
	<strong>OBSERVACIONES</strong>
	
<?
if(isset($Cancelar))
{
?>
	&nbsp; &nbsp;
	<input type="checkbox" name="apro" id="apro" />
	<label for="apro">Resaltar Cambios</label>
<?
}
?>
	
	<input type="hidden" name="obs_redireccion" value="<?=$Redir?>" />
	<input type="hidden" name="obs_pedido" id="obs_pedido" value="<?=(isset($Id_Pedido))?$Id_Pedido:''?>" />
	
	<table>
		<tr>
			<td><textarea cols="70" rows="5" name="obs_observacion"></textarea></td>
			<td>
				<input type="submit" value="Guardar Observaci&oacute;n" />
<?
if(isset($Cancelar))
{
?>
				<br />
				<input type="button" value="Cancelar" />
<?
}
?>
			</td>
		</tr>
	</table>
	
</form>
