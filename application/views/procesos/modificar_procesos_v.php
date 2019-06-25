
<script type="text/javascript" src="/html/js/procesos.js?n=1"></script>

<?
if($Info_producto == '')
{
  echo  '';
}
else
{
?>
<form id="modificar_proceso" name="miform" method="post" action="/procesos/modificar_procesos/modificar" onsubmit="return validar('modificar_proceso')">
	
	
	<input type="hidden" name="id_proceso" value="<?=$Info_producto["id_proceso"]?>" />
	<input type="hidden" name="cliente" id="cliente" value="<?=$Info_producto["id_cliente"]?>" />
	<input type="hidden" name="proceso_ant" id="proceso_ant" value="<?=$Info_producto["proceso"]?>" />
	<input type="hidden" name="codigo_clienteant" id="codigo_clienteant" value="<?=$Info_producto["codigo_cliente"]?>" />
	
	<table>
		<tr>
			<td><label for="codigo_cliente">C&oacute;digo:</label></td>
			<td><input type="text" id="codigo_cliente" name="codigo_cliente" size="10" value="<?=$Info_producto["codigo_cliente"]?>" class="requ" onblur="vercliente(this.value)" />*</td>
		</tr>
		<tr<?=('cli'==$this->session->userdata('tipo_grupo'))?' style="display:none;"':''?>>
			<td><label for="proceso">Proceso:</label></td>
			<td><input type="text" id="proceso" class="requ" value="<?=$Info_producto["proceso"]?>" name="proceso" />*</td>
		</tr>
		<tr>
			<td>Cliente:</td>
			<td>
				<input type="text" id="nombre_cliente" disabled="disabled" value="<?=$Info_producto["nombre"]?>" class="requ" />*&nbsp;
				<input type="button" class="boton" value="Agregar Cliente"  onclick="javascript:window.location='/clientes/agregar'" />
			</td>
		</tr>
		<tr>
			<td><label for="producto">Producto:</label></td>
			<td><input type="text" id="producto" name="producto" value="<?=$Info_producto["nombre_proc"]?>" size="50" class="requ" />*</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" class="boton" value="Modificar Proceso" /></td>
		</tr>
  </table>
	
</form>


<script type="text/javascript">
	$('#codigo_cliente').focus();
</script>

<?
}
?>

