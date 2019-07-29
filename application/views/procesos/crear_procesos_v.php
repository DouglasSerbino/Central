
<script type="text/javascript" src="/html/js/procesos.js?n=1"></script>
<form id="crear_proceso" name="miform" method="post" action="/procesos/crear_procesos/crear" onsubmit="return validar('crear_proceso');">
	
	<input type="hidden" name="cliente" id="cliente" />
	<table>
		<tr>
			<td><label for="codigo_cliente">C&oacute;digo:</label></td>
			<td><input type="text" id="codigo_cliente" size="10" onblur="vercliente(this.value)" class="requ" />*</td>
		</tr>
		<tr<?=('cli'==$this->session->userdata('tipo_grupo'))?' style="display:none;"':''?>>
			<td><label for="proceso">Proceso:</label></td>
			<td>
				<input type="text" id="proceso" class="requ" name="proceso" value="<?=('cli'==$this->session->userdata('tipo_grupo'))?'11':''?>" />*
				<input type="button" class="boton" value="Generar" onclick="genera_correlativo()" />
			</td>
		</tr>
		<tr>
			<td>Cliente:</td>
			<td>
				<input type="text" id="nombre_cliente" disabled="disabled" class="requ" />*
				<input type="button" class="boton" value="Agregar Cliente"  onclick="javascript:window.location='/clientes/agregar'" />
			</td>
		</tr>
		<tr>
			<td><label for="producto">Producto:</label></td>
			<td><input type="text" id="producto" name="producto" size="50" class="requ" />*</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" class="boton" value="Agregar Proceso" /></td>
		</tr>
	</table>
</form>

<script>
  $('#codigo_cliente').focus();
</script>
