
<script type="text/javascript" src="/html/js/pedido.003.js?n=1"></script>

<form name="miform" id="miform" action="/pedidos/administrar" method="post">
	
	<input type="hidden" name="redir" value="<?=$Redir?>" />
	<input type="hidden" name="direccion" value="<?=$Direccion?>" />
	
	<strong>Buscar por Proceso</strong>
	
	<br />
	<input type="text" name="cliente" id="bus_cliente" size="8" />
	<strong>-</strong>
	<input type="text" name="proceso" id="bus_proceso" size="15" />
	
	<br /><br />
	<strong>Buscar por Descripci&oacute;n</strong>
	
	<br />
	<input type="text" name="producto" id="producto" size="20" />
	
	<select name="clientes" id="clientes" onchange="buscar_pedido_desc()">
		<option value="">Seleccionar cliente</option>
<?php
foreach($Clientes as $Cliente)
{
?>
		<option value="<?=$Cliente['id_cliente']?>"><?=$Cliente["codigo_cliente"]?> - <?=$Cliente['nombre']?></option>
<?php
}
?>
	</select>
	
	<br />
	<select name="productos" id="productos">
		<option value="--">--</option>
	</select>
	
	<br /><br />


	<!--input type="text" id="busca_ajax" size="50" />
	<input type="hiddena" name="proceso_ajax" id="proceso_ajax" />

	<br /-->
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


		$('#bus_cliente, #bus_proceso').keypress(function(e)
		{
			if(13 == e.which)
			{
				$('#miform').submit();
			}
		});
		
	});

/*
	$('#busca_ajax').autocomplete(
	{
		source: function (request, response)
		{
			$.post('/pedidos/buscar/completo/', request, response);
		},
		minLength: 3,
		select: function(event, ui)
		{
			$('#proceso_ajax').val(ui.item.id );
		}
	});
	*/
</script>