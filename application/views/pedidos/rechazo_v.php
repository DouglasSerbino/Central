<!--Recharzar trabajo a-->
<div id="rechazar" class="sombra">
	
	<form name="rech_form" id='rech_form' method="post" action="/pedidos/tiempo/accion/rechazar" onsubmit="return validar('rech_form');">
		
		<input type="hidden" name="rech_pedido" id="rech_pedido" value="" />
		<input type="hidden" name="rech_peus" id="rech_peus" value="" />
	
		<strong>Tipo</strong>
		<select name="tipo" id="tipo">
			<option value='rechazo'>Rechazo</option>
			<option value='reasignar'>Reasignaci&oacute;n</option>
		</select>
		&nbsp; <strong>A:</strong>
		<!--Se rechaza a un puesto de la ruta-->
		<select name="rech_a" id="rech_a"></select>
		
		<br />
		
		<table>
<?
$Contador = 0;

if(isset($Rech_Razones))
{
foreach($Rech_Razones as $Razon)
{
	if(0 == $Contador)
	{
?>
			<tr>
<?
	}
?>
				<td>
					<input type="checkbox" name="rz_<?=$Razon['id_rechazo_razones']?>" id="rz_<?=$Razon['id_rechazo_razones']?>" />
					<label for="rz_<?=$Razon['id_rechazo_razones']?>"><?=$Razon['rechazo_razon']?></label>
				</td>
<?
	$Contador++;
	if(3 == $Contador)
	{
		$Contador = 0;
?>
			</tr>
<?
	}
}
}
?>
		</table>
		
		<textarea name="rech_comentario" id="rech_comentario" class='requ' cols="70" rows="4"></textarea>
		
		<br />
		<input type="button" value="Cancelar" onclick="$('#rechazar').hide();" />
		<input type="button" value="Rechazar" onclick="rechazar_trabajo()" />
		
	</form>
	
</div>
