
<script type="text/javascript" src="/html/js/pedido.003.js?n=1"></script>

<form name="miform" id="miform" action="/pedidos/administrar" method="post">
	
	<input type="hidden" name="redir" value="/pedidos/v_buscar/index" />
	<input type="hidden" name="direccion" value="/pedidos/administrar/info" />
	
	<strong>Buscar por Proceso</strong>
	
	<br />
	<input type="hidden" name="cliente" id="cliente" value="<?=$this->session->userdata('codigo_cliente')?>" />
	<strong><?=$this->session->userdata('codigo_cliente')?></strong>
	<strong>-</strong>
	<input type="text" name="proceso" size="15" />
	
	
	<br /><br />
	<strong>Buscar por Descripci&oacute;n</strong>
	
	<br />
	<input type="text" name="producto" id="producto" size="20" />
	
	<input type="hidden" name="clientes" id="clientes" value="<?=$this->session->userdata('id_cliente')?>" />
	
	<br />
	<select name="productos" id="productos">
		<option value="--">--</option>
	</select>
	
	
	<br /><br />
	<input type="button" class="boton" value="Buscar Pedidos" onclick="$('#miform').submit();" />
	
</form>


<script>
  
	$('[name=cliente]').focus();
	
	$(function()
	{
		$('#producto').keypress(function(e)
		{
			if(13 == e.which)
			{
				buscar_pedido_desc();
			}
		});
		
	});
	
</script>